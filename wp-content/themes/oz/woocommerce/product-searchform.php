<?php
/**
 * The template for displaying product search form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/product-searchform.php.
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
 * @version 2.5.0
 */
?>
<form role="search" method="get" class="search-form woocommerce-product-search" action="<?php echo esc_url( home_url('/')); ?>">
	<label>
        <input type="search" placeholder="<?php esc_html_e( 'Search Products&hellip;', 'oz' ); ?>" value="" name="s" title="<?php esc_html_e('Search for:', 'oz'); ?>" class="search-field">
    </label>
	<button type="submit" value="" class="search-submit"><i class="flaticon flaticon-magnifying-glass"></i></button>
	<input type="hidden" name="post_type" value="product" />
</form>