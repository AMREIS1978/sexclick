<?php
/** Run via WP-CLI only, after theme and plugins have been copied to staging. */
if (!defined('WP_CLI') || !WP_CLI) { exit(1); }
if (!in_array(wp_get_environment_type(), ['local','development','staging'], true)) {
 WP_CLI::error('Defina WP_ENVIRONMENT_TYPE=staging. Este instalador não modifica produção.');
}
if (!class_exists('WooCommerce')) WP_CLI::error('Instale e ative WooCommerce antes de configurar.');
if (get_option('scops_sales_enabled',false)) WP_CLI::error('As vendas já estão ativadas. Reveja a instalação manualmente.');
if (!is_callable(['WC_Install','create_pages'])) WP_CLI::error('Esta versão WooCommerce não disponibiliza o instalador de páginas esperado.');
$before=[];foreach(['shop','cart','checkout','myaccount'] as $slug)$before[$slug]=wc_get_page_id($slug);
WC_Install::create_pages();
$shortcodes=['cart'=>'woocommerce_cart','checkout'=>'woocommerce_checkout','myaccount'=>'woocommerce_my_account'];
$pages=['shop'=>'Coleção','cart'=>'Carrinho','checkout'=>'Finalizar compra','myaccount'=>'A minha conta'];
foreach($pages as $slug=>$title){
 $id=wc_get_page_id($slug);
 if($id>0 && $before[$slug]<=0){
  $update=['ID'=>$id,'post_title'=>$title];
  if($slug!=='shop')$update['post_content']='['.$shortcodes[$slug].']';
  wp_update_post($update);
 }
}
if(!get_option('page_on_front')){
 $page=wp_insert_post(['post_type'=>'page','post_status'=>'publish','post_title'=>'Início','post_name'=>'inicio'],true);
 if(is_wp_error($page))WP_CLI::error($page->get_error_message());
 update_option('show_on_front','page'); update_option('page_on_front',$page);
}
if(!get_option('sexclick_staging_configured')) {
 update_option('woocommerce_currency','EUR');
 update_option('woocommerce_default_country','PT');
 update_option('woocommerce_hide_out_of_stock_items','yes');
 update_option('woocommerce_enable_guest_checkout','yes');
 update_option('timezone_string','Europe/Lisbon');
 // No tax rate, address, shipping tariff or payment credentials are invented.
 update_option('sexclick_staging_configured',gmdate('c'),false);
}
$locations=get_theme_mod('nav_menu_locations',[]);
if(empty($locations['primary'])){
 $menu=wp_create_nav_menu('SEXCLICK — Principal');
 if(!is_wp_error($menu)){
  foreach(['shop','myaccount'] as $slug)wp_update_nav_menu_item($menu,0,['menu-item-title'=>$pages[$slug],'menu-item-object'=>'page','menu-item-object-id'=>wc_get_page_id($slug),'menu-item-type'=>'post_type','menu-item-status'=>'publish']);
  $locations['primary']=$menu;set_theme_mod('nav_menu_locations',$locations);
 }
}
update_option('blog_public',0);
update_option('scops_sales_enabled',false,false);
flush_rewrite_rules();
WP_CLI::success('Staging configurado em EUR/PT. Vendas bloqueadas. Dados fiscais, logística, pagamentos e Dreamlove aguardam configuração real.');
