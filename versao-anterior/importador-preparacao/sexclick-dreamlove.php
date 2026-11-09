<?php
/**
 * Plugin Name: SEXCLICK — Preparação Dreamlove
 * Description: Importação CSV em rascunho e fila de expedição. Sem chamadas ao fornecedor ou pagamentos.
 * Version: 0.1.0
 * Requires Plugins: woocommerce
 */
if (!defined('ABSPATH')) exit;
add_action('admin_menu', function () {
 add_submenu_page('woocommerce','SEXCLICK Dreamlove','SEXCLICK Dreamlove','manage_woocommerce','sexclick-dreamlove','scdl_admin');
});
function scdl_admin() {
 if (!current_user_can('manage_woocommerce')) return;
 echo '<div class="wrap"><h1>SEXCLICK · Dreamlove</h1><p><strong>Modo de preparação: ligação ao fornecedor desativada.</strong> Os produtos importados ficam em rascunho. Nenhuma encomenda é transmitida.</p>';
 if (!class_exists('WC_Product_Simple')) {echo '<p>Ative o WooCommerce.</p></div>';return;}
 if (isset($_POST['scdl_import'])) {
  check_admin_referer('scdl_import');
  $file = $_FILES['catalogue'] ?? null;
  if (!$file || $file['error'] !== UPLOAD_ERR_OK || $file['size'] > 5*1024*1024 || !is_uploaded_file($file['tmp_name'])) echo '<p>Ficheiro inválido. Limite: 5 MB.</p>';
  else {
   $handle = fopen($file['tmp_name'],'r');
   $columns = fgetcsv($handle,0,';', '"', '');
   if ($columns) $columns[0] = preg_replace('/^\xEF\xBB\xBF/','',$columns[0]);
   $expected = ['sku','name_pt','description_pt','price_eur','stock','image_url','category'];
   if ($columns !== $expected) echo '<p>Cabeçalho incompatível. Utilize o modelo fornecido.</p>';
   else {
    $count=0;$skipped=0;$errors=0;$lines=0;
    while (($row=fgetcsv($handle,0,';','"','')) !== false) {
     if (++$lines>2000) {echo '<p>Limite de 2000 linhas atingido. Divida o catálogo em ficheiros menores.</p>';break;}
     if (count($row)!==7) {$skipped++;continue;}
     $data=array_combine($expected,$row);$sku=sanitize_text_field($data['sku']);$name=sanitize_text_field($data['name_pt']);
     $price=str_replace(',','.',trim($data['price_eur']));$stock=trim($data['stock']);
     if (!$sku || !$name || !is_numeric($price) || (float)$price<=0 || !preg_match('/^\d+$/',$stock)) {$skipped++;continue;}
     try {
      $id=wc_get_product_id_by_sku($sku);
      // Never overwrite an unrelated product using the same SKU.
      if ($id && get_post_meta($id,'_scdl_source',true)!=='dreamlove') {$skipped++;continue;}
      $product=$id ? wc_get_product($id) : new WC_Product_Simple();
      if (!$product || !$product->is_type('simple')) {$skipped++;continue;}
      $product->set_name($name);$product->set_sku($sku);$product->set_status('draft');
      $product->set_description(wp_kses_post($data['description_pt']));
      $product->set_regular_price(wc_format_decimal($price));$product->set_manage_stock(true);
      $product->set_stock_quantity((int)$stock);$product->set_backorders('no');
      $product->update_meta_data('_scdl_source','dreamlove');
      $product->update_meta_data('_scdl_image_reference',esc_url_raw($data['image_url'],['https']));
      $product->update_meta_data('_scdl_category_reference',sanitize_text_field($data['category']));
      $product->update_meta_data('_scdl_imported_at',gmdate('c'));
      $product->save();$count++;
     } catch (Throwable $e) {$errors++;}
    }
    echo '<p>'.esc_html("Importados: $count. Ignorados: $skipped. Erros: $errors. Todos os produtos processados ficam em rascunho.").'</p>';
   }
   fclose($handle);
  }
 }
 echo '<h2>Importar catálogo normalizado</h2><p>CSV UTF-8, separado por ponto e vírgula. O preço é o preço final de venda, já calculado por si. Imagens e categorias são guardadas como referências para revisão; ainda não são publicadas.</p><form method="post" enctype="multipart/form-data">';
 wp_nonce_field('scdl_import');
 echo '<input type="file" name="catalogue" accept=".csv" required><p><button name="scdl_import" class="button button-primary">Importar em rascunho</button></p></form><h2>Antes de ativar vendas</h2><p>Configurar pagamentos, impostos, portes, identidade do vendedor, políticas da loja e conector oficial. Confirmar variantes, fotografias, traduções e disponibilidade. O catálogo real exige um mapeamento ao formato da Dreamlove.</p></div>';
}
add_action('woocommerce_payment_complete', function ($order_id) {
 $order=wc_get_order($order_id);if (!$order || $order->get_meta('_scdl_dispatch_state')) return;
 $contains=false;
 foreach ($order->get_items() as $item) {
  $product=$item->get_product();
  if ($product && $product->get_meta('_scdl_source')==='dreamlove') {$contains=true;break;}
 }
 if (!$contains) return;
 $order->update_meta_data('_scdl_dispatch_state','awaiting_connection');
 $order->add_order_note('SEXCLICK: pagamento confirmado. Ligação Dreamlove desativada; encomenda NÃO enviada ao fornecedor. Requer tratamento manual.');
 $order->save();
});
