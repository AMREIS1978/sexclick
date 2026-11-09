<?php
/** Plugin Name: SEXCLICK — Primeira instalação
 * Description: Assistente local para uma instalação WordPress nova. Não importa dados nem emite certificados.
 */
if (!defined('ABSPATH')) exit;
add_action('admin_notices',function(){
 if (!current_user_can('manage_options') || get_option('sexclick_full_bootstrap_done')) return;
 echo '<div class="notice notice-info"><h2>Preparar SEXCLICK</h2><p>Depois de instalar WordPress, este botão ativa o WooCommerce, o tema SEXCLICK e o assistente da loja. Apenas para uma loja nova, sem produtos nem encomendas. Não liga a Dreamlove nem ativa vendas.</p><form method="post" action="'.esc_url(admin_url('admin-post.php')).'"><input type="hidden" name="action" value="sexclick_first_run">';
 wp_nonce_field('sexclick_first_run');
 echo '<p><button class="button button-primary">Preparar a minha loja SEXCLICK</button></p></form></div>';
});

function scfull_prepare() {
 if (!current_user_can('manage_options') || !current_user_can('activate_plugins') || !current_user_can('switch_themes')) return new WP_Error('permission','É necessário entrar como administrador.');
 if (get_option('sexclick_full_bootstrap_done')) return true;
 if (get_option('scops_sales_enabled')) return new WP_Error('live','Já existem vendas ativadas. Não foi alterada a loja.');
 require_once ABSPATH.'wp-admin/includes/plugin.php';
 foreach (['woocommerce/woocommerce.php','sexclick-operations/sexclick-operations.php'] as $plugin) if (!file_exists(WP_PLUGIN_DIR.'/'.$plugin)) return new WP_Error('missing','Falta um plugin do pacote. Confirme que todos os ficheiros foram extraídos.');
 $theme=wp_get_theme('sexclick');
 if (!$theme->exists() || $theme->errors()) return new WP_Error('theme','O tema SEXCLICK não está completo.');
 // Refuse an existing catalogue before installing a launch gate into a running shop.
 if (get_posts(['post_type'=>['product','shop_order'],'post_status'=>'any','numberposts'=>1,'fields'=>'ids'])) return new WP_Error('existing','Esta instalação contém produtos ou encomendas. Use os pacotes separados e reveja a configuração existente.');
 $result=activate_plugin('woocommerce/woocommerce.php');
 if (is_wp_error($result)) return $result;
 if (!function_exists('wc_get_orders')) return new WP_Error('woocommerce','WooCommerce não carregou. Verifique a versão PHP e os requisitos do servidor.');
 if (wc_get_orders(['limit'=>1,'return'=>'ids'])) return new WP_Error('orders','Já existem encomendas. O assistente não vai substituir a loja.');
 $result=activate_plugin('sexclick-operations/sexclick-operations.php');
 if (is_wp_error($result)) return $result;
 switch_theme('sexclick');
 $result=scops_setup_pages(true);
 if (is_wp_error($result)) return $result;
 update_option('woocommerce_currency','EUR');
 update_option('woocommerce_default_country','PT');
 update_option('timezone_string','Europe/Lisbon');
 update_option('sexclick_full_bootstrap_done',gmdate('c'),false);
 return true;
}

add_action('admin_post_sexclick_first_run',function(){
 if (!current_user_can('manage_options')) wp_die('É necessário entrar como administrador.','',['response'=>403]);
 check_admin_referer('sexclick_first_run');
 $result=scfull_prepare();
 if (is_wp_error($result)) wp_die(esc_html($result->get_error_message()),'Instalação SEXCLICK',['back_link'=>true]);
 wp_safe_redirect(admin_url('admin.php?page=sexclick-operations'));
 exit;
});
