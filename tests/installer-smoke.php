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
// Exercise upgrading our own old theme with a real native extraction and backup.
$style=get_theme_root().'/sexclick/style.css';$current=file_get_contents($style);
file_put_contents($style,str_replace('Version: 2.0.0','Version: 1.0.0',$current));
wp_clean_themes_cache(false);
sch_assert(is_wp_error(sch_install('theme',false)),'Atualização sem opção explícita deve parar.');
$result=sch_install('theme',true);
sch_assert(!is_wp_error($result),'Atualização com cópia falhou.');
$copies=glob(WP_CONTENT_DIR.'/sexclick-backups/sexclick-*/style.css');
sch_assert(!empty($copies),'Cópia anterior não foi criada.');
sch_assert(str_contains(file_get_contents($copies[0]),'Version: 1.0.0'),'Cópia não preservou código anterior.');
wp_clean_themes_cache(false);
sch_assert(wp_get_theme('sexclick')->get('Version')==='2.0.0','Atualização não instalou versão atual.');
update_option('scops_sales_enabled',true);
sch_assert(is_wp_error(sch_run('configure')),'Instalação não pode alterar uma loja com vendas ativas.');
update_option('scops_sales_enabled',false);
sch_assert(get_stylesheet()==='sexclick','Tema incorreto.');
sch_assert(is_plugin_active('woocommerce/woocommerce.php') && is_plugin_active('sexclick-operations/sexclick-operations.php'),'Plugins não ficaram ativos.');
wp_set_current_user(0);
WP_CLI::success('Instalador: permissões, passos permitidos, integridade e proteção de loja ativa verificados.');
