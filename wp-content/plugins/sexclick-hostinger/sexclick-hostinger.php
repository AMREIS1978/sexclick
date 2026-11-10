<?php
/**
 * Plugin Name: SEXCLICK — Instalar na Hostinger
 * Description: Instalação guiada e recuperável de WooCommerce, tema SEXCLICK e preparação da loja.
 * Version: 2.0.0
 * Requires at least: 6.5
 * Requires PHP: 8.1
 * License: GPL-2.0-or-later
 */
if (!defined('ABSPATH')) exit;
define('SCH_VERSION','2.0.0');
define('SCH_DIR',__DIR__);
function sch_allowed(){return current_user_can('manage_options') && current_user_can('install_plugins') && current_user_can('activate_plugins') && current_user_can('install_themes') && current_user_can('switch_themes') && !is_multisite();}
function sch_steps(){return ['preflight'=>'Verificar alojamento','woocommerce-install'=>'Instalar WooCommerce','woocommerce-activate'=>'Ativar WooCommerce','theme-install'=>'Instalar tema SEXCLICK','operations-install'=>'Instalar preparação da loja','operations-activate'=>'Ativar preparação da loja','configure'=>'Preparar páginas e navegação','verify'=>'Confirmar instalação'];}
// WooCommerce may create pages during activation, before Operations is loaded.
// Supply PT-PT defaults only for new pages while this new-site setup is pending.
add_filter('woocommerce_create_pages',function($pages){
 if (get_option('sch_configured_version')===SCH_VERSION) return $pages;
 foreach(['shop'=>'Coleção','cart'=>'Carrinho','checkout'=>'Finalizar compra','myaccount'=>'A minha conta'] as $slug=>$title) if(isset($pages[$slug])) {
  $pages[$slug]['title']=$title;
  $codes=['cart'=>'woocommerce_cart','checkout'=>'woocommerce_checkout','myaccount'=>'woocommerce_my_account'];
  if (isset($codes[$slug])) $pages[$slug]['content']='['.$codes[$slug].']';
 }
 return $pages;
});
add_action('admin_menu',function(){add_menu_page('Instalar SEXCLICK','Instalar SEXCLICK','manage_options','sexclick-hostinger','sch_page','dashicons-store',58);});
add_action('admin_notices',function(){
 if (!sch_allowed() || get_option('sch_configured_version')===SCH_VERSION || (isset($_GET['page']) && $_GET['page']==='sexclick-hostinger')) return;
 echo '<div class="notice notice-info"><p><strong>SEXCLICK:</strong> o instalador está pronto. <a href="'.esc_url(admin_url('admin.php?page=sexclick-hostinger')).'">Preparar a loja</a></p></div>';
});
add_filter('plugin_action_links_'.plugin_basename(__FILE__),function($links){array_unshift($links,'<a href="'.esc_url(admin_url('admin.php?page=sexclick-hostinger')).'">Preparar loja</a>');return $links;});
add_action('admin_enqueue_scripts',function($hook){
 if ($hook!=='toplevel_page_sexclick-hostinger') return;
 wp_enqueue_style('sch',plugins_url('wizard.css',__FILE__),[],SCH_VERSION);
 wp_enqueue_script('sch',plugins_url('wizard.js',__FILE__),[],SCH_VERSION,true);
 wp_localize_script('sch','sexclickInstaller',['url'=>admin_url('admin-ajax.php'),'nonce'=>wp_create_nonce('sch_setup'),'steps'=>array_keys(sch_steps())]);
});
function sch_page(){
 if (!sch_allowed()) {echo '<div class="wrap"><h1>SEXCLICK</h1><p>Entre como administrador numa instalação WordPress individual, com permissão para instalar plugins e temas.</p></div>';return;}
 echo '<div class="wrap sch-wrap"><p class="sch-label">SEXCLICK · HOSTINGER</p><h1>A sua loja, passo a passo.</h1><p class="sch-intro">Instala os componentes locais, prepara as páginas e confirma o resultado. Se houver uma falha, pare aqui e retome neste mesmo ecrã.</p><div class="sch-card"><h2>Antes de começar</h2><p>Use uma instalação WordPress nova, de preferência em <strong>/loja</strong>, preservando o site atual. Este instalador não é um backup para o serviço de Migrações.</p><p>Não ativa vendas, não configura pagamentos e não liga a Dreamlove. A emissão de SSL é feita pelo alojamento.</p><label><input type="checkbox" id="sch-overwrite"> Atualizar o código SEXCLICK que já exista (cria primeiro uma cópia dos ficheiros; mantém as opções e as imagens).</label><label><input type="checkbox" id="sch-home" checked> Criar a página inicial se ainda não existir uma atribuída.</label><label><input type="checkbox" id="sch-catalog" checked> Tornar a montra pública, mantendo as compras bloqueadas até à configuração comercial.</label><p><button type="button" class="button button-primary button-hero" id="sch-start">Preparar a minha loja</button></p><noscript><p>Ative JavaScript para executar o assistente. O tema e o WooCommerce não precisam deste assistente para funcionar depois de instalados.</p></noscript></div><ol class="sch-steps">';
 foreach(sch_steps() as $key=>$label) echo '<li data-step="'.esc_attr($key).'"><span class="sch-state">Por executar</span><strong>'.esc_html($label).'</strong><p class="sch-detail"></p></li>';
 echo '</ol><div id="sch-result" role="status" aria-live="polite"></div><p><a class="button" href="'.esc_url(home_url('/')).'">Ver o site</a> <a class="button" href="'.esc_url(admin_url('admin.php?page=sexclick-operations')).'">Preparação Dreamlove</a></p><p class="description">O diagnóstico HTTPS confirma apenas a configuração dos endereços WordPress. Não certifica o SSL público nem a segurança de toda a loja.</p></div>';
}
function sch_manifest(){
 $path=SCH_DIR.'/payload/manifest.json';
 $data=is_readable($path)?json_decode(file_get_contents($path),true):null;
 return is_array($data)?$data:[];
}
function sch_error($code,$message){return new WP_Error($code,$message);}
function sch_preflight(){
 global $wp_version;
 if (!sch_allowed()) return sch_error('permission','Faltam permissões de administrador ou esta é uma instalação multisite.');
 if (version_compare(PHP_VERSION,'8.1','<')) return sch_error('php','Selecione PHP 8.1 ou superior no alojamento.');
 $manifest=sch_manifest();
 $available=disk_free_space(WP_CONTENT_DIR);
 if ($available!==false && $available<($manifest['minimum_free_bytes']??0)) return sch_error('space','Falta espaço livre para extrair os pacotes. Liberte espaço no alojamento e retome.');
 $required=$manifest['woocommerce']['requires_wp']??'6.5';
 if (version_compare($wp_version,$required,'<')) return sch_error('wordpress','Atualize WordPress para '.$required.' ou superior antes de instalar este pacote.');
 if (get_option('scops_sales_enabled')) return sch_error('live','Esta loja já tem vendas ativadas. Use uma instalação de testes para preparar esta versão.');
 if (function_exists('wc_get_orders') && wc_get_orders(['limit'=>1,'return'=>'ids'])) return sch_error('orders','Existem encomendas nesta loja. O assistente de instalação nova não altera uma loja em funcionamento.');
 if (!function_exists('wc_get_orders') && get_posts(['post_type'=>'shop_order','post_status'=>'any','numberposts'=>1,'fields'=>'ids'])) return sch_error('orders','Existem encomendas nesta instalação. Prepare a versão numa instalação de testes.');
 require_once ABSPATH.'wp-admin/includes/file.php';
 if (get_filesystem_method([],WP_CONTENT_DIR)!=='direct') return sch_error('filesystem','O WordPress não consegue escrever diretamente em wp-content. Corrija as permissões no alojamento; o assistente não vai pedir nem guardar credenciais FTP.');
 if (!WP_Filesystem()) return sch_error('filesystem','Não foi possível abrir o sistema de ficheiros do WordPress. Verifique permissões e espaço no alojamento.');
 foreach(['woocommerce','theme','operations'] as $key){
  $entry=$manifest[$key]??[];$path=SCH_DIR.'/payload/'.$key.'.zip';
  if (empty($entry['sha256']) || !is_readable($path) || !hash_equals($entry['sha256'],hash_file('sha256',$path))) return sch_error('payload','O pacote '.$key.' está incompleto ou corrompido. Volte a instalar o ZIP do assistente.');
 }
 return ['message'=>'PHP, WordPress, permissões e integridade dos três pacotes verificados.'];
}
function sch_backup($type,$path){
 if (!is_dir($path)) return true;
 require_once ABSPATH.'wp-admin/includes/file.php';
 $root=WP_CONTENT_DIR.'/sexclick-backups';
 if (!wp_mkdir_p($root)) return sch_error('backup','Não foi possível criar a cópia de segurança. Nenhum ficheiro foi substituído.');
 // Only theme/plugin source is copied, never wp-config, uploads or database exports.
 foreach(['index.php'=>"<?php http_response_code(403); exit;",'.htaccess'=>"Require all denied\n"] as $name=>$content) {
  if (file_put_contents($root.'/'.$name,$content)===false) return sch_error('backup','Não foi possível proteger a pasta de cópias. A atualização foi interrompida.');
 }
 $destination=$root.'/'.$type.'-'.gmdate('Ymd-His').'-'.wp_generate_password(6,false,false);
 if (!wp_mkdir_p($destination)) return sch_error('backup','Não foi possível criar a pasta de cópia.');
 if (!WP_Filesystem()) return sch_error('filesystem','Falhou o acesso aos ficheiros para a cópia.');
 return copy_dir($path,$destination);
}
function sch_install($key,$overwrite){
 require_once ABSPATH.'wp-admin/includes/plugin.php';
 require_once ABSPATH.'wp-admin/includes/file.php';
 require_once ABSPATH.'wp-admin/includes/class-wp-upgrader.php';
 wp_raise_memory_limit('admin');
 $manifest=sch_manifest();$entry=$manifest[$key]??[];
 $theme=$key==='theme';
 $slug=$key==='woocommerce'?'woocommerce':($theme?'sexclick':'sexclick-operations');
 $file=$key==='woocommerce'?'woocommerce/woocommerce.php':'sexclick-operations/sexclick-operations.php';
 $path=$theme?get_theme_root().'/sexclick':WP_PLUGIN_DIR.'/'.$slug;
 $exists=$theme?wp_get_theme('sexclick')->exists():file_exists(WP_PLUGIN_DIR.'/'.$file);
 if ($exists) {
  $version=$theme?wp_get_theme('sexclick')->get('Version'):(get_plugin_data(WP_PLUGIN_DIR.'/'.$file,false,false)['Version']??'0');
  if ($key==='woocommerce' || version_compare($version,$entry['version']??'0','>=')) return ['message'=>'Já está instalado. Ficheiros preservados.'];
  if (!$overwrite) return sch_error('existing','Existe uma versão anterior de SEXCLICK. Se pretende atualizá-la, selecione a opção de atualização no início e volte a executar.');
  $backup=sch_backup($slug,$path);
  if (is_wp_error($backup)) return $backup;
 }
 $package=SCH_DIR.'/payload/'.$key.'.zip';
 if (empty($entry['sha256']) || !is_readable($package) || !hash_equals($entry['sha256'],hash_file('sha256',$package))) return sch_error('package','Pacote incompleto: '.$key.'.');
 if (!WP_Filesystem()) return sch_error('filesystem','Não foi possível escrever no alojamento.');
 $skin=new Automatic_Upgrader_Skin();
 $upgrader=$theme?new Theme_Upgrader($skin):new Plugin_Upgrader($skin);
 $result=$upgrader->install($package,['overwrite_package'=>$exists,'clear_update_cache'=>false]);
 if (is_wp_error($result)) return $result;
 if (!$result) return sch_error('install','Falhou a extração de '.$slug.'. Verifique permissões e espaço livre; retome depois.');
 return ['message'=>'Instalado a partir do pacote local.'];
}
function sch_run($step,$overwrite=false,$set_home=false,$show_catalog=false){
 if (!sch_allowed()) return sch_error('permission','Só o administrador pode preparar a loja.');
 if (!array_key_exists($step,sch_steps())) return sch_error('step','Passo de instalação desconhecido.');
 if ($step==='preflight') return sch_preflight();
 if (get_option('scops_sales_enabled')) return sch_error('live','As vendas estão ativadas. Instalação interrompida para preservar a loja.');
 require_once ABSPATH.'wp-admin/includes/plugin.php';
 if (str_ends_with($step,'-install')) return sch_install(substr($step,0,-8),$overwrite);
 if (str_ends_with($step,'-activate')) {
  $file=str_starts_with($step,'woocommerce')?'woocommerce/woocommerce.php':'sexclick-operations/sexclick-operations.php';
  if (is_plugin_active($file)) return ['message'=>'Já está ativo.'];
  if (!file_exists(WP_PLUGIN_DIR.'/'.$file)) return sch_error('missing','Execute primeiro a instalação do plugin.');
  $result=activate_plugin($file);
  return is_wp_error($result)?$result:['message'=>'Ativado. O próximo passo será executado num novo pedido.'];
 }
 if (!function_exists('scops_setup_pages') || !class_exists('WooCommerce')) return sch_error('dependencies','WooCommerce e SEXCLICK Operations têm de estar ativos. Retome a instalação desde o início.');
 if ($step==='configure') {
  if (!wp_get_theme('sexclick')->exists()) return sch_error('theme','O tema SEXCLICK ainda não está instalado.');
  if (get_stylesheet()!=='sexclick') switch_theme('sexclick');
  $result=scops_setup_pages($set_home);
  if (is_wp_error($result)) return $result;
  if ($show_catalog) update_option('woocommerce_coming_soon','no');
  update_option('sch_configured_version',SCH_VERSION,false);
  return ['message'=>'Tema ativo; páginas e menu em falta preparados. Configurações, imagens e páginas existentes preservadas.'];
 }
 $missing=[];
 foreach(['shop','cart','checkout','myaccount'] as $slug) {
  $id=wc_get_page_id($slug);
  if ($id<=0 || get_post_type($id)!=='page' || get_post_status($id)!=='publish') $missing[]=$slug;
 }
 if ($missing || get_stylesheet()!=='sexclick') return sch_error('verify','A instalação ainda não está completa. Reveja as páginas e o tema.');
 return ['message'=>'Instalação concluída. Reveja moeda, pagamentos, envios, fotografia e conector Dreamlove. As compras continuam bloqueadas.','complete'=>true];
}
add_action('wp_ajax_sch_step',function(){
 if (!sch_allowed()) wp_send_json_error(['message'=>'A sessão não tem permissões de administrador.'],403);
 if (!check_ajax_referer('sch_setup','nonce',false)) wp_send_json_error(['message'=>'A sessão expirou. Recarregue este ecrã e retome a instalação.'],403);
 // Atomic short-lived lock prevents concurrent tabs from replacing the same files.
 $lock=get_option('sch_install_lock');
 if ($lock && (int)$lock<time()-180) delete_option('sch_install_lock');
 if (!add_option('sch_install_lock',time(),'','no')) wp_send_json_error(['message'=>'Outro passo ainda está em execução. Aguarde um momento e retome.'],409);
 $step=isset($_POST['step'])?sanitize_key(wp_unslash($_POST['step'])):'';
 $buffer_level=ob_get_level();ob_start();
 try {
  $result=sch_run($step,!empty($_POST['overwrite']),!empty($_POST['set_home']),!empty($_POST['show_catalog']));
  if (is_wp_error($result)) {$response=['success'=>false,'data'=>['message'=>$result->get_error_message()]];}
  else {$response=['success'=>true,'data'=>$result];}
 } catch(Throwable $error) {
  // Do not expose exception traces, filesystem paths or configuration secrets.
  $response=['success'=>false,'data'=>['message'=>'O servidor interrompeu este passo. Verifique o registo de erros PHP na Hostinger e retome depois.']];
 } finally {
  while(ob_get_level()>$buffer_level) ob_end_clean();
  delete_option('sch_install_lock');
 }
 wp_send_json($response);
});
