<?php
/**
 * Product Loop Start
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/loop-start.php.
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
 * @version     2.0.0
 */

global $woocommerce_loop;

if ( !isset($woocommerce_loop['css_class']) ) {
    $woocommerce_loop['css_class'] = 'wil-masonry-wrapper wil_masonry-grid ';
}

if ( isset($woocommerce_loop['portfolio_layout']) ) :

if ( $woocommerce_loop['settings']['toggle_animation'] === 'enable' ){
    $woocommerce_loop['settings']['product_animation'] = 'wil-animation';

    if ( $woocommerce_loop['settings']['toggle_animation_on_mobile'] != 'enable' ){
        $woocommerce_loop['settings']['product_animation'] .=' wil-animation--disable-mobile';
    }
}

?>
<!-- Loop / Start wil-masonry-wrapper -->
<div
    <?php
    WilokePublic::render_attributes(
        array(
            'class'                   => $woocommerce_loop['css_class'],
            'data-lg-vertical'        => $woocommerce_loop['devices_settings']['large']['vertical'],
            'data-lg-horizontal'      => $woocommerce_loop['devices_settings']['large']['horizontal'],
            'data-md-vertical'        => $woocommerce_loop['devices_settings']['medium']['vertical'],
            'data-md-horizontal'      => $woocommerce_loop['devices_settings']['medium']['horizontal'],
            'data-sm-vertical'        => $woocommerce_loop['devices_settings']['small']['vertical'],
            'data-sm-horizontal'      => $woocommerce_loop['devices_settings']['small']['horizontal'],
            'data-xs-vertical'        => $woocommerce_loop['devices_settings']['extra_small']['vertical'],
            'data-xs-horizontal'      => $woocommerce_loop['devices_settings']['extra_small']['horizontal'],
            'data-col-lg'             => $woocommerce_loop['devices_settings']['large']['items_per_row'],
            'data-col-md'             => $woocommerce_loop['devices_settings']['medium']['items_per_row'],
            'data-col-sm'             => $woocommerce_loop['devices_settings']['small']['items_per_row'],
            'data-col-xms'            => $woocommerce_loop['devices_settings']['extra_small']['items_per_row'],
            'data-animation-type'     => $woocommerce_loop['settings']['animation_affect'],
            'data-animation-children' => '.product__anim'
        )
    );
    ?>
>
<?php else : ?>
<div data-col-lg="4" data-col-md="2" data-col-sm="2" data-col-xs="1" data-gap="30" data-animation-children=".product__anim" data-animation-type="wil-anim--product" class="wiloke-woocommerce-wrapper wil-masonry-wrapper wil_masonry-grid wil-animation wil-animation--children wil-animation--disable-mobile">
<?php endif; ?>
    <div class="wil_masonry">
        <div class="grid-sizer"></div>