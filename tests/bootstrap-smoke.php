<?php
if (!defined('WP_CLI') || !WP_CLI) exit(1);
if (wp_get_environment_type()!=='local' || getenv('SEXCLICK_CI')!=='1') WP_CLI::error('Apenas CI local descartável.');
function sc_boot_assert($test,$message){if(!$test)WP_CLI::error($message);}
sc_boot_assert(function_exists('scfull_prepare'),'Assistente inicial ausente.');
sc_boot_assert(is_wp_error(scfull_prepare()),'Visitante não deve poder preparar a loja.');
$admin=get_users(['role'=>'administrator','number'=>1]);
wp_set_current_user($admin[0]->ID);
$result=scfull_prepare();
sc_boot_assert($result===true,is_wp_error($result)?$result->get_error_message():'Falha no assistente.');
sc_boot_assert(is_plugin_active('woocommerce/woocommerce.php'),'WooCommerce não foi ativado.');
sc_boot_assert(is_plugin_active('sexclick-operations/sexclick-operations.php'),'Operations não foi ativado.');
sc_boot_assert(get_stylesheet()==='sexclick','Tema não foi ativado.');
sc_boot_assert(!scops_live(),'Assistente não deve ativar vendas.');
sc_boot_assert((int)get_option('page_on_front')>0,'Página inicial não foi atribuída.');
sc_boot_assert(get_option('woocommerce_currency')==='EUR','Moeda incorreta.');
$pages=wp_count_posts('page')->publish;
sc_boot_assert(scfull_prepare()===true && wp_count_posts('page')->publish===$pages,'Repetição duplicou páginas.');
wp_set_current_user(0);
WP_CLI::success('Primeira instalação real: permissões, ativação local, tema, páginas, EUR e repetição verificados.');
