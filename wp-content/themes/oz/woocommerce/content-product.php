<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

global $product, $woocommerce_loop, $wiloke;
// Ensure visibility
if ( empty( $product ) || ! $product->is_visible() ) {
    return;
}

$cssClass = 'grid-item ';
$size = 'wiloke_925_925';
if ( is_product() ){
    $size = 'wiloke_925_925';
}else{
    if ( isset($woocommerce_loop['is_wiloke']) && $woocommerce_loop['is_wiloke'] ) {    
        if ( $woocommerce_loop['settings']['is_real_image'] != 'yes' ){
            if ( !isset($woocommerce_loop['layout_settings']['items_size'][$woocommerce_loop['loop']]) || empty($woocommerce_loop['layout_settings']['items_size'][$woocommerce_loop['loop']]) ) {
                $numberOfItems = count($woocommerce_loop['layout_settings']['items_size']);
                if ( $woocommerce_loop['loop']/$numberOfItems > 0 ) {
                    $quotient = floor($woocommerce_loop['loop']/$numberOfItems);
                    $woocommerce_loop['loop'] = $woocommerce_loop['loop'] - ($quotient*$numberOfItems + 1);
                }
            }
            $size = 'wiloke_925_925';
            $cssClass .= ' ' . $woocommerce_loop['layout_settings']['items_size'][$woocommerce_loop['loop']];
        }else{
            $size = 'large';
        }
    }
}
?>
<div class='<?php echo esc_attr($cssClass); ?>'>
    <div class="grid-item__inner">
        <div class="grid-item__content-wrapper">
            <div  <?php post_class('product'); ?>>
                <div class="product__anim">
                    <?php
                    /**
                     * woocommerce_before_shop_loop_item hook.
                     *
                     * @hooked woocommerce_template_loop_product_link_open - 10 x
                     */
                    do_action( 'woocommerce_before_shop_loop_item' );

                    /**
                     * woocommerce_before_shop_loop_item_title hook.
                     *
                     * @hooked product_header 5
                     * @hooked product_add_to_cart 5
                     * @hooked woocommerce_show_product_loop_sale_flash - 10
                     * @hooked woocommerce_template_loop_product_thumbnail - 10 x
                     */
                    do_action( 'woocommerce_before_shop_loop_item_title', $size );

                    /**
                     * woocommerce_shop_loop_item_title hook.
                     *
                     * @hooked woocommerce_template_loop_product_title - 10 x
                     */
                    do_action( 'woocommerce_shop_loop_item_title' );

                    /**
                     * woocommerce_after_shop_loop_item_title hook.
                     *
                     * @hooked product_out_of_stock 5
                     * @hooked product_header_close 10
                     * @hooked woocommerce_template_loop_rating - 5 x
                     * @hooked woocommerce_template_loop_price - 10 x
                     */
                    do_action( 'woocommerce_after_shop_loop_item_title' );

                    /**
                     * woocommerce_after_shop_loop_item hook.
                     *
                     * @hoooked product_before_info 5
                     * @hooked product_info 10
                     * @hooked product_before_info_close 15
                     * @hooked woocommerce_template_loop_product_link_close - 5 x
                     * @hooked woocommerce_template_loop_add_to_cart - 10 x
                     */
                    do_action( 'woocommerce_after_shop_loop_item' );
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
