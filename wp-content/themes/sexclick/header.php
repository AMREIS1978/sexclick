<?php if (!defined('ABSPATH')) exit; ?><!doctype html>
<html <?php language_attributes(); ?>><head><meta charset="<?php bloginfo('charset'); ?>"><meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover"><?php wp_head(); ?></head>
<body <?php body_class(); ?>><?php wp_body_open(); ?>
<a class="skip-link" href="#conteudo"><?php esc_html_e('Ir para o conteúdo','sexclick'); ?></a>
<header class="sc-header"><a class="sc-logo" href="<?php echo esc_url(home_url('/')); ?>" aria-label="SEXCLICK — Início">SEX<span>CLICK</span></a>
<nav class="sc-desktop-nav" aria-label="<?php esc_attr_e('Menu principal','sexclick'); ?>"><a href="<?php echo esc_url(sc_url('shop')); ?>"><?php esc_html_e('Explorar a coleção','sexclick'); ?></a><a href="<?php echo esc_url(sc_url('myaccount')); ?>"><?php esc_html_e('A minha conta','sexclick'); ?></a></nav>
<div class="sc-tools"><?php if (function_exists('pll_the_languages')): ?><div class="sc-languages"><?php pll_the_languages(['dropdown'=>1,'show_names'=>0,'show_flags'=>0]); ?></div><?php endif; ?>
<button class="sc-icon" type="button" data-search-open aria-controls="sc-search-dialog" aria-label="<?php esc_attr_e('Pesquisar','sexclick'); ?>"><?php echo sc_icon('search'); ?></button>
<a class="sc-icon sc-cart" href="<?php echo esc_url(sc_url('cart')); ?>" aria-label="<?php esc_attr_e('Carrinho','sexclick'); ?>"><?php echo sc_icon('bag'); ?></a></div></header>
<?php if (has_nav_menu('primary')) wp_nav_menu(['theme_location'=>'primary','container'=>'nav','container_class'=>'sc-category-nav','fallback_cb'=>false]); ?>
<dialog id="sc-search-dialog" class="sc-dialog"><div class="sc-dialog-head"><strong><?php esc_html_e('Pesquisar na SEXCLICK','sexclick'); ?></strong><button type="button" class="sc-icon" data-search-close aria-label="<?php esc_attr_e('Fechar','sexclick'); ?>"><?php echo sc_icon('close'); ?></button></div><?php sc_search_form(); ?></dialog>
