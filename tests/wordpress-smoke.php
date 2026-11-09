<?php
if(!defined('WP_CLI') || !WP_CLI)exit(1);
if(wp_get_environment_type()!=='local' || getenv('SEXCLICK_CI')!=='1')WP_CLI::error('Apenas ambiente local efémero de CI.');
function sc_assert($test,$message){if(!$test)WP_CLI::error($message);}
sc_assert(class_exists('WooCommerce'),'WooCommerce ausente.');
sc_assert(wp_get_theme()->get_stylesheet()==='sexclick','Tema incorreto.');
sc_assert(!scops_live(),'A loja deve iniciar bloqueada.');
foreach(['shop','cart','checkout','myaccount'] as $page)sc_assert(wc_get_page_id($page)>0,'Página em falta: '.$page);
$product=new WC_Product_Simple();$product->set_name('Produto CI');$product->set_status('publish');$product->set_sku('SC-CI-001');$product->set_regular_price('19.90');$product->set_manage_stock(true);$product->set_stock_quantity(2);$product->save();
sc_assert(!$product->is_purchasable(),'Produto não pode ser comprado antes do lançamento.');
update_option('scops_verified',array_fill_keys(array_keys(scops_requirements()),1));update_option('scops_sales_enabled',true);
sc_assert($product->is_purchasable(),'O gate deve preservar compra nativa quando ativado em CI.');
$product->set_price('');sc_assert(!$product->is_purchasable(),'Produto sem preço não pode ser comprado.');
$product->set_price('19.90');$product->set_stock_quantity(0);$product->save();sc_assert(!$product->is_in_stock(),'Stock zero deve ficar indisponível.');
$product->set_stock_quantity(2);$product->save();
// Synthetic fixture for UI tests, never a supplier product or real order.
$category=wp_insert_term('Coleção CI','product_cat');if(!is_wp_error($category))wp_set_object_terms($product->get_id(),[$category['term_id']],'product_cat');
update_option('scops_sales_enabled',false);update_option('scops_verified',[]);
WP_CLI::success('WooCommerce real: páginas, tema, proteção de compras e stock verificados.');
