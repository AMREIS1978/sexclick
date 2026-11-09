<?php
/** Small, explicit, repeatable setup inside an existing WordPress installation. */
if (!defined('ABSPATH')) exit;

add_action('admin_post_scops_setup', 'scops_setup_request');

function scops_setup_pages($set_home = false) {
 if (!current_user_can('manage_options')) return new WP_Error('permission', 'É necessário acesso de administrador.');
 if (!class_exists('WooCommerce') || !is_callable(['WC_Install','create_pages'])) return new WP_Error('woocommerce', 'Instale e ative WooCommerce primeiro.');
 if (scops_live()) return new WP_Error('live', 'A loja já aceita compras. Reveja as páginas nas definições WooCommerce.');
 $titles=['shop'=>'Coleção','cart'=>'Carrinho','checkout'=>'Finalizar compra','myaccount'=>'A minha conta'];
 $defaults=static function($pages) use ($titles) {
  $codes=['cart'=>'woocommerce_cart','checkout'=>'woocommerce_checkout','myaccount'=>'woocommerce_my_account'];
  foreach ($titles as $slug=>$title) if (isset($pages[$slug])) {
   $pages[$slug]['title']=$title;
   if (isset($codes[$slug])) $pages[$slug]['content']='['.$codes[$slug].']';
  }
  return $pages;
 };
 // Customize new-page defaults through WooCommerce's API; never rewrite existing pages.
 add_filter('woocommerce_create_pages',$defaults,20);
 try { WC_Install::create_pages(); }
 finally { remove_filter('woocommerce_create_pages',$defaults,20); }
 foreach ($titles as $slug=>$title) {
  $id=wc_get_page_id($slug);
  if ($id<=0 || get_post_type($id)!=='page' || get_post_status($id)!=='publish') return new WP_Error('page', 'Não foi possível preparar a página: '.$title.'. Verifique as páginas e permissões no WordPress.');
 }
 if ($set_home && !get_option('page_on_front')) {
  $home=get_page_by_path('inicio-sexclick',OBJECT,'page');
  $id=$home ? $home->ID : wp_insert_post(['post_type'=>'page','post_status'=>'publish','post_title'=>'Início','post_name'=>'inicio-sexclick'],true);
  if (is_wp_error($id)) return $id;
  if (get_post_status($id)!=='publish') return new WP_Error('home','Reveja a página inicio-sexclick antes de a definir como inicial.');
  update_option('page_on_front',$id);
  update_option('show_on_front','page');
 }
 // Do not touch another theme's menu locations.
 if (get_stylesheet()==='sexclick') {
  $locations=(array)get_theme_mod('nav_menu_locations',[]);
  if (empty($locations['primary'])) {
   $menu=wp_get_nav_menu_object('SEXCLICK — Principal');
   $menu_id=$menu ? $menu->term_id : wp_create_nav_menu('SEXCLICK — Principal');
   if (is_wp_error($menu_id)) return $menu_id;
   $items=wp_get_nav_menu_items($menu_id) ?: [];
   foreach (['shop','myaccount'] as $slug) {
    $page_id=wc_get_page_id($slug);
    $exists=false;
    foreach ($items as $item) if ($item->object==='page' && (int)$item->object_id===$page_id) $exists=true;
    if (!$exists) {
     $result=wp_update_nav_menu_item($menu_id,0,['menu-item-title'=>$titles[$slug],'menu-item-object'=>'page','menu-item-object-id'=>$page_id,'menu-item-type'=>'post_type','menu-item-status'=>'publish']);
     if (is_wp_error($result)) return $result;
    }
   }
   $locations['primary']=$menu_id;
   set_theme_mod('nav_menu_locations',$locations);
  }
 }
 return true;
}

function scops_setup_request() {
 if (!current_user_can('manage_options')) wp_die('É necessário acesso de administrador.','',['response'=>403]);
 check_admin_referer('scops_setup');
 $result=scops_setup_pages(!empty($_POST['set_home']));
 set_transient('scops_setup_result_'.get_current_user_id(),is_wp_error($result) ? $result->get_error_message() : 'Páginas e menu preparados. As configurações comerciais e a ligação Dreamlove continuam pendentes.',60);
 wp_safe_redirect(admin_url('admin.php?page=sexclick-operations'));
 exit;
}

function scops_setup_panel() {
 if (!current_user_can('manage_options')) return;
 $message=get_transient('scops_setup_result_'.get_current_user_id());
 if ($message) {
  delete_transient('scops_setup_result_'.get_current_user_id());
  echo '<div class="notice notice-info"><p>'.esc_html($message).'</p></div>';
 }
 echo '<div class="wrap"><h1>Instalação SEXCLICK</h1><p>Este assistente funciona dentro do WordPress. O ZIP do tema e o ZIP do plugin não são uma cópia completa para o serviço de Migrações da Hostinger.</p><table class="widefat striped"><tbody>';
 $checks=['PHP 8.1 ou superior'=>version_compare(PHP_VERSION,'8.1','>='),'WooCommerce ativo'=>class_exists('WooCommerce'),'Tema SEXCLICK ativo'=>get_stylesheet()==='sexclick'];
 foreach (['shop'=>'Coleção','cart'=>'Carrinho','checkout'=>'Finalizar compra','myaccount'=>'A minha conta'] as $slug=>$title) {
  $id=function_exists('wc_get_page_id') ? wc_get_page_id($slug) : -1;
  $checks['Página '.$title]=$id>0 && get_post_status($id)==='publish';
 }
 foreach ($checks as $label=>$ok) echo '<tr><th scope="row">'.esc_html($label).'</th><td>'.($ok?'Disponível':'Por preparar').'</td></tr>';
 echo '</tbody></table><p>HTTPS nas URLs do WordPress: <strong>'.((function_exists('wp_is_using_https') && wp_is_using_https())?'configurado':'por configurar').'</strong>. Este indicador não verifica a validade do certificado SSL do servidor.</p>';
 echo '<form method="post" action="'.esc_url(admin_url('admin-post.php')).'"><input type="hidden" name="action" value="scops_setup">';
 wp_nonce_field('scops_setup');
 echo '<p><label><input type="checkbox" name="set_home" value="1"> Definir a página inicial SEXCLICK se ainda não existir uma página inicial atribuída</label></p><p><button class="button button-primary"'.(scops_live()?' disabled':'').'>Preparar páginas e menu em falta</button></p></form><p>Não importa produtos, não envia encomendas e não ativa vendas. Não substitui páginas existentes nem instala programas em segundo plano.</p><p><a href="'.esc_url(admin_url('admin.php?page=wc-settings')).'">Configurar moeda, pagamentos e envios no WooCommerce</a> · <a href="'.esc_url(admin_url('themes.php')).'">Ver temas instalados</a></p></div>';
}
