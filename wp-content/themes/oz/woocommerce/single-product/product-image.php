<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version 3.0.2
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

global $post, $product;
?>
<div class="product-media">
    <?php
    $attachments = $product->get_gallery_image_ids();
    $attachment_count = count($attachments);

    if ( $attachment_count > 0 ) {
        $attachments = implode(',', $attachments);
        
        echo '<div class="product-media">';
        echo do_shortcode('[wiloke_swiper_slider post_type="attachment" attachments="'.$attachments.'"]');
        echo '</div>';
    } else {
        if ( has_post_thumbnail() ) {
            the_post_thumbnail('large');
        }else{
            echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<img src="%s" alt="%s" />', wc_placeholder_img_src(), esc_html__( 'Placeholder', 'oz' ) ), $post->ID );
        }
    }
    ?>
</div>
