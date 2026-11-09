<?php
/**
 * Plugin Name: SEXCLICK Operations
 * Description: Estado de preparação Dreamlove e proteção do lançamento comercial.
 * Version: 1.1.0
 * Requires PHP: 8.1
 * Requires Plugins: woocommerce
 */
if (!defined('ABSPATH')) exit;
require_once __DIR__.'/integration-contract.php';
require_once __DIR__.'/setup-tools.php';
add_action('admin_menu',function(){add_submenu_page('woocommerce','SEXCLICK · Dreamlove','SEXCLICK · Dreamlove','manage_woocommerce','sexclick-operations','scops_admin');});
function scops_requirements() {
 return [
 'catalogue'=>'Catálogo e variantes importados e revistos',
 'stock'=>'Sincronização de stock e novidades testada',
 'shipping'=>'Transportadoras, destinos e portes testados',
 'orders'=>'Encomenda paga enviada uma única vez e reconciliada',
 'tracking'=>'Tracking e estados de expedição recebidos',
 'languages'=>'Idiomas e categorias mapeados no conector',
 'payments'=>'Pagamentos e reembolsos testados',
 'policies'=>'Dados do vendedor, faturação e políticas configurados',
 'returns'=>'Pós-venda, devoluções e garantias validados',
 ];
}
function scops_ready() {
 $verified=(array)get_option('scops_verified',[]);
 foreach(array_keys(scops_requirements()) as $key) if(empty($verified[$key]))return false;
 return true;
}
function scops_live() {return (bool)get_option('scops_sales_enabled',false) && scops_ready();}
function scops_admin() {
 if(!current_user_can('manage_woocommerce'))return;
 if(isset($_POST['scops_save'])) {
  check_admin_referer('scops_settings');
  $input=isset($_POST['verified']) && is_array($_POST['verified']) ? wp_unslash($_POST['verified']) : [];
  $verified=[];foreach(array_keys(scops_requirements()) as $key)$verified[$key]=isset($input[$key])?1:0;
  update_option('scops_verified',$verified,false);
  update_option('scops_sales_enabled',isset($_POST['sales_enabled']) && scops_ready(),false);
  echo '<div class="notice notice-success"><p>Configuração guardada.</p></div>';
 }
 scops_setup_panel();
 $verified=(array)get_option('scops_verified',[]);
 echo '<div class="wrap"><h1>SEXCLICK · Dreamlove</h1><p><strong>'.(scops_live()?'Vendas ativadas':'Preparação — compras bloqueadas').'</strong></p><p>Estas verificações são declarações do administrador após testes reais. Não constituem deteção automática nem certificação da Dreamlove. Este módulo não transmite encomendas nem sincroniza stock.</p><form method="post">';
 wp_nonce_field('scops_settings');
 foreach(scops_requirements() as $key=>$label)echo '<p><label><input type="checkbox" name="verified['.esc_attr($key).']" value="1" '.checked(!empty($verified[$key]),true,false).'> '.esc_html($label).'</label></p>';
 echo '<hr><p><label><input type="checkbox" name="sales_enabled" value="1" '.checked((bool)get_option('scops_sales_enabled'),true,false).'> Ativar vendas após concluir os testes acima</label></p><button class="button button-primary" name="scops_save">Guardar configuração</button></form><h2>Integração preferencial</h2><p>Usar o conector WooCommerce fornecido ou validado pela Dreamlove. Não ativar um segundo emissor de encomendas. A integração personalizada só deve ser implementada a partir da documentação contratada da API.</p><p>Catálogo: produtos, imagens, descrições, idiomas e variantes no WooCommerce. Expedição e tracking: conector oficial. Carteira, faturas do fornecedor e pós-venda: validar funcionalidades contratadas na conta B2B.</p></div>';
}
add_filter('woocommerce_is_purchasable',function($value){return scops_live()?$value:false;},99);
add_filter('woocommerce_variation_is_purchasable',function($value){return scops_live()?$value:false;},99);
add_filter('woocommerce_add_to_cart_validation',function($valid){if(!scops_live()){wc_add_notice('A loja está em preparação. As compras estarão disponíveis em breve.','error');return false;}return $valid;},99);
add_action('woocommerce_checkout_process',function(){if(!scops_live())wc_add_notice('A loja ainda não está a aceitar encomendas.','error');});
add_action('woocommerce_store_api_cart_errors',function($errors){if(!scops_live())$errors->add('sexclick_prelaunch','A loja ainda não está a aceitar encomendas.');},99,1);
add_action('woocommerce_before_shop_loop',function(){if(!scops_live())echo '<p class="woocommerce-info">Estamos a preparar a coleção. As compras estarão disponíveis em breve.</p>';},5);
