<?php
// Unit test of the launch gate; this does not validate WooCommerce or Dreamlove.
define('ABSPATH',__DIR__);
$options=[];$filters=[];$actions=[];
function get_option($key,$fallback=false){global $options;return $options[$key]??$fallback;}
function add_action($name,$callback,...$args){global $actions;$actions[$name]=$callback;}
function add_filter($name,$callback,...$args){global $filters;$filters[$name]=$callback;}
function wc_add_notice(...$args){}
require __DIR__.'/../wp-content/plugins/sexclick-operations/sexclick-operations.php';
function ensure($bool,$message){if(!$bool)throw new Exception($message);}
ensure(!scops_live(),'Default must disable sales.');
ensure(!$filters['woocommerce_is_purchasable'](true),'Purchases must be blocked.');
$options['scops_sales_enabled']=true;
ensure(!scops_live(),'A single toggle is not sufficient.');
$options['scops_verified']=array_fill_keys(array_keys(scops_requirements()),1);
ensure(scops_live(),'All requirements and sales toggle allow launch.');
ensure($filters['woocommerce_is_purchasable'](true),'Allow native purchasability.');
ensure(!$filters['woocommerce_is_purchasable'](false),'Do not turn unavailable products into purchasable ones.');
$options['scops_verified']['orders']=0;
ensure(!scops_live(),'Missing order verification must block checkout.');
echo "PASS: launch gate conditions.\n";
