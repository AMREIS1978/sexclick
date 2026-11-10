<?php
if (!defined('WP_CLI') || !WP_CLI) exit(1);
if (wp_get_environment_type()!=='local' || getenv('SEXCLICK_CI')!=='1') WP_CLI::error('Apenas CI local descartável.');
function sch_assert($test,$message){if(!$test)WP_CLI::error($message);}
sch_assert(is_wp_error(sch_run('configure')),'Visitante não deve preparar a loja.');
$admin=get_users(['role'=>'administrator','number'=>1]);wp_set_current_user($admin[0]->ID);
sch_assert(!is_wp_error(sch_run('verify')),'Instalação não ficou completa.');
sch_assert(is_wp_error(sch_run('arbitrary-step')),'Passo desconhecido deve ser recusado.');
$path=SCH_DIR.'/payload/theme.zip';$size=filesize($path);
file_put_contents($path,'!',FILE_APPEND);
try {sch_assert(is_wp_error(sch_preflight()),'Pacote alterado deve ser recusado pelo hash.');}
finally {$handle=fopen($path,'c');ftruncate($handle,$size);fclose($handle);}
sch_assert(!is_wp_error(sch_preflight()),'Pacote restaurado deve passar.');
update_option('scops_sales_enabled',true);
sch_assert(is_wp_error(sch_run('configure')),'Instalação não pode alterar uma loja com vendas ativas.');
update_option('scops_sales_enabled',false);
sch_assert(get_stylesheet()==='sexclick','Tema incorreto.');
sch_assert(is_plugin_active('woocommerce/woocommerce.php') && is_plugin_active('sexclick-operations/sexclick-operations.php'),'Plugins não ficaram ativos.');
wp_set_current_user(0);
WP_CLI::success('Instalador: permissões, passos permitidos, integridade e proteção de loja ativa verificados.');
