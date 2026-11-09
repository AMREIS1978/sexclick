<?php get_header(); ?><main id="conteudo" class="sc-page sc-shop">
<?php if(function_exists('is_shop') && (is_shop() || is_product_taxonomy())): ?>
<details class="sc-filters"><summary><?php esc_html_e('Filtrar a coleção','sexclick'); ?></summary><div class="sc-filter-grid">
<?php if(class_exists('WC_Widget_Product_Categories')) the_widget('WC_Widget_Product_Categories',['title'=>__('Categorias','sexclick'),'dropdown'=>1,'count'=>1,'hierarchical'=>1,'hide_empty'=>1]); ?>
<?php if(class_exists('WC_Widget_Price_Filter')) the_widget('WC_Widget_Price_Filter',['title'=>__('Preço','sexclick')]); ?>
</div></details><?php endif; ?>
<?php if(function_exists('woocommerce_content'))woocommerce_content(); ?></main><?php get_footer(); ?>
