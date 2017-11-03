<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
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
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
get_header();

$shopID = get_option( 'woocommerce_shop_page_id' );
$aShopSettings = Wiloke::getPostMetaCaching($shopID, 'woocommerce_settings');
$GLOBALS['wilokeWoocommerceShopSetings'] = $aShopSettings;
?>

<?php
/**
 * woocommerce_before_main_content hook.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked product_navigator - 15 x
 * @hooked woocommerce_breadcrumb - 20 x
 * @hooked product_mini_cart - 20 x
 * @hooked product_navigator_close - 30 x
 * @hooked product_section 30
 */
do_action( 'woocommerce_before_main_content' );

?>

<?php
// Wiloke Design
$GLOBALS['wilokeIsWooCommerceQuery'] = true;

if( isset($aShopSettings['is_wiloke_design_shop']) && ($aShopSettings['is_wiloke_design_shop'] === 'yes') ){
    $content_post = get_post($shopID);
    $content = $content_post->post_content;
    echo do_shortcode($content);
} else {
    /**
     * woocommerce_archive_description hook.
     *
     * @hooked woocommerce_taxonomy_archive_description - 10
     * @hooked woocommerce_product_archive_description - 10
     */
    do_action( 'woocommerce_archive_description' );
    
    ?>

    <?php if ( have_posts() ) : ?>

    <?php
    /**
     * woocommerce_before_shop_loop hook.
     *
     * @hooked woocommerce_result_count - 20 temporary x
     * @hooked woocommerce_catalog_ordering - 30 temporary x
     */
    do_action( 'woocommerce_before_shop_loop' );
    ?>

    <?php woocommerce_product_loop_start(); ?>

    <?php woocommerce_product_subcategories(); ?>

    <?php while ( have_posts() ) : the_post(); ?>

    <?php wc_get_template_part( 'content', 'product' ); ?>

    <?php endwhile; // end of the loop.?>

    <?php woocommerce_product_loop_end(); ?>

    <?php
    /**
     * woocommerce_after_shop_loop hook.
     *
     * @hooked woocommerce_pagination - 10
     */
    do_action( 'woocommerce_after_shop_loop' );
    ?>

    <?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>

    <?php wc_get_template( 'loop/no-products-found.php' ); ?>

    <?php endif; ?>
    <?php
    /**
     * woocommerce_after_main_content hook.
     *
     * @hooked product_section_close 5
     * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
     */
    do_action( 'woocommerce_after_main_content' );
    ?>

    <?php
}

?>

<?php get_footer(); ?>
