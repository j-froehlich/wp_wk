<?php
/**
 * Pagination - Show numbered pagination for catalog pages
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/pagination.php.
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
 * @version     2.2.2
 */
/*
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

global $wp_query, $wilokeWoocommerceDesignedShop, $wilokeWoocommerceShopSetings, $woocommerce_loop;

if ( $wp_query->max_num_pages < 1 ) {
    return;
}

$cssClass = 'wil-pagination wil-woocommerce-nav';
$isLoadMore = false;
if ( isset($wilokeWoocommerceShopSetings['is_wiloke_design_shop']) && ($wilokeWoocommerceShopSetings['is_wiloke_design_shop'] == 'yes') && !empty($wilokeWoocommerceDesignedShop) && $wilokeWoocommerceDesignedShop['pagination_type'] == 'ajax' ) {
    $cssClass .= ' ajax hidden';
    $isLoadMore = true;
}

?>

<?php  if ( $isLoadMore ) : ?>
<div id="wil-shop-loadmore" class="text-center work__loadmore-wrapper work__loadmore--2">
    <div class="wil-work-loading">
        <div class="work-loadmore__btn">
            <div class="wil-loader"></div>
        </div>
    </div>
    <button class="wil-btn wiloke-loadmore"><?php esc_html_e('Load more', 'oz'); ?></button>
</div>
<?php endif; ?>

<div class="<?php echo esc_attr($cssClass); ?>" data-settings="<?php echo esc_attr(json_encode($wilokeWoocommerceDesignedShop)); ?>">
    <?php
    echo paginate_links(apply_filters( 'woocommerce_pagination_args', array(
        'base'         => esc_url_raw( str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', get_pagenum_link( 999999999, false ) ) ) ),
        'format'       => '',
        'add_args'     => false,
        'current'      => max( 1, get_query_var( 'paged' ) ),
        'total'        => $wp_query->max_num_pages,
        'prev_text'    => '<i class="fa fa-angle-left"></i>',
        'next_text'    => '<i class="fa fa-angle-right"></i>',
        'end_size'     => 3,
        'mid_size'     => 3
    )));
    ?>
</div>
*/?>

<?php
/**
 * Pagination - Show numbered pagination for catalog pages
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/pagination.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.3.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$total   = isset( $total ) ? $total : wc_get_loop_prop( 'total_pages' );
$current = isset( $current ) ? $current : wc_get_loop_prop( 'current_page' );
$base    = isset( $base ) ? $base : esc_url_raw( str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', get_pagenum_link( 999999999, false ) ) ) );
$format  = isset( $format ) ? $format : '';

if ( $total <= 1 ) {
	return;
}
?>
<nav class="woocommerce-pagination">
	<?php
		echo paginate_links( apply_filters( 'woocommerce_pagination_args', array( // WPCS: XSS ok.
			'base'         => $base,
			'format'       => $format,
			'add_args'     => false,
			'current'      => max( 1, $current ),
			'total'        => $total,
			'prev_text'    => '&larr;',
			'next_text'    => '&rarr;',
			'type'         => 'list',
			'end_size'     => 3,
			'mid_size'     => 3,
		) ) );
	?>
</nav>
