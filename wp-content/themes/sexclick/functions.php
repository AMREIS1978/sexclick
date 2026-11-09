<?php
if (!defined('ABSPATH')) exit;
add_action('after_setup_theme', function () {
 add_theme_support('title-tag'); add_theme_support('post-thumbnails');
 add_theme_support('html5', ['search-form','gallery','caption','style','script']);
 add_theme_support('woocommerce');
 add_theme_support('wc-product-gallery-zoom'); add_theme_support('wc-product-gallery-lightbox'); add_theme_support('wc-product-gallery-slider');
 register_nav_menus(['primary'=>'Menu principal','footer'=>'Informações da loja']);
 load_theme_textdomain('sexclick', get_template_directory().'/languages');
});
add_action('wp_enqueue_scripts', function () {
 wp_enqueue_style('sexclick', get_template_directory_uri().'/assets/shop.css', [], '1.0.0');
 wp_enqueue_script('sexclick',get_template_directory_uri().'/assets/shop.js', [], '1.0.0', true);
});
add_filter('loop_shop_columns', function () { return 4; });
add_filter('loop_shop_per_page', function () { return 24; });
function sc_url($page) {
 if (function_exists('wc_get_page_permalink')) return wc_get_page_permalink($page);
 return home_url('/');
}
function sc_icon($name) {
 $paths=['search'=>'M21 21l-5-5M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0',
 'bag'=>'M5 7h14l1 14H4L5 7zm3 0V5a4 4 0 0 1 8 0v2',
 'user'=>'M20 21v-2a8 8 0 0 0-16 0v2M16 6a4 4 0 1 1-8 0 4 4 0 0 1 8 0',
 'home'=>'M3 10l9-8 9 8M5 9v12h14V9M9 21v-8h6v8',
 'grid'=>'M3 3h7v7H3zm11 0h7v7h-7zM3 14h7v7H3zm11 0h7v7h-7z',
 'menu'=>'M3 6h18M3 12h18M3 18h18','close'=>'M5 5l14 14M19 5L5 19'];
 return '<svg viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="'.esc_attr($paths[$name] ?? $paths['grid']).'"/></svg>';
}
function sc_search_form() {
 $field_id=wp_unique_id('sc-query-');
 ?><form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" class="sc-search"><label for="<?php echo esc_attr($field_id); ?>"><?php esc_html_e('O que procura?', 'sexclick'); ?></label><div><input type="search" id="<?php echo esc_attr($field_id); ?>" name="s" value="<?php echo esc_attr(get_search_query()); ?>" placeholder="<?php esc_attr_e('Pesquisar produtos', 'sexclick'); ?>"><input type="hidden" name="post_type" value="product"><button type="submit" aria-label="<?php esc_attr_e('Pesquisar','sexclick'); ?>"><?php echo sc_icon('search'); ?></button></div></form><?php
}
add_action('customize_register', function ($c) {
 $c->add_section('sc_visual',['title'=>'SEXCLICK — Imagem principal','priority'=>30]);
 $c->add_setting('sc_hero',['sanitize_callback'=>'absint']);
 $c->add_control(new WP_Customize_Media_Control($c,'sc_hero',['section'=>'sc_visual','label'=>'Fotografia principal','mime_type'=>'image']));
});
add_action('admin_notices',function(){if(!class_exists('WooCommerce') && current_user_can('activate_plugins'))echo '<div class="notice notice-warning"><p>SEXCLICK: instale e ative WooCommerce para usar a loja.</p></div>';});
