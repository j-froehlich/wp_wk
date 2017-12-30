<?php
/**
 * Single Product Meta
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/meta.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @author      WooThemes
 * @package     WooCommerce/Templates
 * @version     3.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

global $post, $product;

$tag_count = sizeof( get_the_terms( $post->ID, 'product_tag' ) );

?>
<div class="product-summary-footer">
    <ul>
    <?php do_action( 'woocommerce_product_meta_start' ); ?>
        <?php /*if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) : ?>

            <li><?php _e( 'SKU:', 'oz' ); ?> <span class="sku" itemprop="sku"><?php echo ( $sku = $product->get_sku() ) ? $sku : esc_html__( 'N/A', 'oz' ); ?></span></li>

        <?php endif;*/ ?>

        <?php echo wc_get_product_category_list( $product->get_id(), ' ', '<li>' . _n('Category:', 'Categories:', count($product->get_category_ids()), 'oz' ) . ' ', '</li>' ); ?>

        <?php echo wc_get_product_tag_list( $product->get_id(), ' ', '<li>' . _n( 'Tag:', 'Tags:', count($product->get_tag_ids()), 'oz' ) . ' ', '</li>' ); ?>
        <?php do_action( 'woocommerce_product_meta_end' ); ?>
    </ul>
</div>
