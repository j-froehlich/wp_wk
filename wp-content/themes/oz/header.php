<?php
/**
 * The template for displaying the header
 *
 * Displays all of the head element and everything up until the "site-content" div.
 *
 * @package WilokeThemes
 * @subpackage OZ
 * @since  1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head id="wiloke-head">
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="format-detection" content="telephone=no"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>

    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
    <?php wp_head(); ?>
</head>
<?php
global $wiloke, $post;

$bgColor = $bgType = $aPostMeta =  $aPageSettings = '';

if ( function_exists('is_shop') && is_shop()  ){
    $postID = get_option('woocommerce_shop_page_id');
}elseif( isset($post->ID) ){
    $postID = $post->ID;
}

if ( isset($postID) ){
    $aPostMeta      = Wiloke::getPostMetaCaching($postID, 'project_general_settings');
    $aPageSettings  = Wiloke::getPostMetaCaching($postID, 'single_page_settings');
}

if ( is_singular('portfolio') ) {
    $bgType = !isset($aPostMeta['project_bg_type']) || $aPostMeta['project_bg_type'] == 'inherit'  ? $wiloke->aThemeOptions['project_bg_type'] : $aPostMeta['project_bg_type'];
    $bgColor = $sColor = $fColor = '';

    if( $bgType == 'gradient' ) {
        if ( !empty($aPostMeta['project_bg_gradient_f']) || !empty($aPostMeta['project_bg_gradient_s']) && ($aPostMeta['project_bg_type'] != 'inherit') ) {
            $fColor = $aPostMeta['project_bg_gradient_f'];
            $sColor = $aPostMeta['project_bg_gradient_s'];
        }else{
            $fColor = $wiloke->aThemeOptions['project_bg_gradient_f']['rgba'];
            $fColor = $wiloke->aThemeOptions['project_bg_gradient_s']['rgba'];
        }

        if ( strpos($fColor, 'rgba') !== false ){
            $fColor = str_replace( array('rgba'), array('rgb'), $fColor );
            $cutFrom = strrpos($fColor, ',');
            $fColor = substr($fColor, 0, $cutFrom) . ')';
        }

        if ( strpos($sColor, 'rgba') !== false ){
            $sColor = str_replace( array('rgba'), array('rgb'), $sColor );
            $cutFrom = strrpos($sColor, ',');
            $sColor = substr($sColor, 0, $cutFrom) . ')';
        }

    }else{
        if ( !empty($aPostMeta['project_bg_color']) && ($aPostMeta['project_bg_type'] != 'inherit') ) {
            $bgColor = $aPostMeta['project_bg_color'];
        }elseif ( isset($wiloke->aThemeOptions['project_bg_color']['color']) ) {
            $bgColor = $wiloke->aThemeOptions['project_bg_color']['color'];
        }

        if ( strpos($bgColor, 'rgba') !== false ){
            $bgColor = str_replace( array('rgba'), array('rgb'), $bgColor );
            $cutFrom = strrpos($bgColor, ',');
            $bgColor = substr($bgColor, 0, $cutFrom) . ')';
        }
    }
}
?>
<body <?php body_class(); ?>>
    <?php if ( $bgType == 'gradient' ) : ?>
    <div id="wiloke-body-area" style="background-image:linear-gradient(to top right, <?php echo esc_attr($fColor); ?> 40%, <?php echo esc_attr($sColor); ?> 80%); background-color: <?php echo esc_attr($fColor); ?>">
        <div id="wiloke-where-replace">
    <?php else: ?>
    <div id="wiloke-body-area" style="background-color: <?php echo esc_attr($bgColor); ?>;">
        <div id="wiloke-where-replace">
    <?php endif; ?>

    <?php
    /**
     * @hooked render_preloader
     */
    do_action('wiloke/oz/before_wil_wrapper');
    ?>
    <div id="wil-wrapper" class="<?php echo esc_attr(apply_filters('wiloke_oz_filter_main_wrapper_class', 'wil-wrapper wil-wrapper--boxed')); ?>">

        <?php
        /**
         * Before Header
         * @hooked render_project_background
         */
        do_action('wiloke/oz/before_header');
        ?>
        <!-- Header-->
        <header data-breakpoint="<?php echo esc_attr($wiloke->aThemeOptions['general_breakpoint_menu']); ?>" data-animation-children="<?php echo esc_attr(apply_filters('wiloke/oz/set_header_animation', '.wil-logo, .wil-menu-list &gt; .menu-item, .wil-toggle-search, .wil-text-box, .wil-toggle-menu, .wil-minicart')); ?>" class="<?php echo esc_attr(apply_filters('wiloke/oz/header/css_class', 'wil-header wil-animation wil-animation--children', $aPageSettings)); ?>">
            <div class="wil-tb wil-header__tb">
                <div class="wil-tb__cell wil-header__tb__cell">
                    <div class="wil-logo">
                        <?php $wiloke->frontEnd->render_logo($aPostMeta, $aPageSettings); ?>
                    </div>
                </div>
                <div class="wil-tb__cell wil-header__tb__cell">
                   <?php do_action('wiloke/oz/menu_place', 'desktop'); ?>
                </div>

                <?php if ( $wiloke->aThemeOptions['general_header_information_box'] == 'enable' ) : ?>
                <div class="wil-tb__cell wil-header__tb__cell">
                    <div class="wil-text-box-wrapper">
                        <?php if ( $wiloke->aThemeOptions['general_header_information_box_following'] != 'disable' ) : ?>
                        <div class="wil-text-box"><?php echo esc_html($wiloke->aThemeOptions['general_header_information_box_following']); ?>
                            <?php WilokeSocialNetworks::render_socials($wiloke->aThemeOptions, ', '); ?>
                        </div>
                        <?php endif; ?>
                        <?php do_action('wiloke/oz/inside_information_box'); ?>
                    </div>
                </div>
                <?php endif; ?>

            </div>
            <div data-animation-type="wil-anim--header" class="wil-header__divider wil-animation"></div>
        </header>
        <div id="wil-menu-mobile" class="wil-box-toggle wil-box-toggle--anim-1 body-overflow-hidden">
            <div class="wil-tb">
                <div class="wil-tb__cell"><span class="wil-box-toggle__close"><span></span><span></span></span>
                    <?php do_action('wiloke/oz/menu_place', 'mobile'); ?>
                </div>
            </div>
            <div class="wil-text-box-wrapper">
                <?php if ( $wiloke->aThemeOptions['general_header_information_box_following'] != 'disable' ) : ?>
                    <div class="wil-text-box"><?php echo esc_html($wiloke->aThemeOptions['general_header_information_box_following']); ?>
                        <?php WilokeSocialNetworks::render_socials($wiloke->aThemeOptions, ', '); ?>
                    </div>
                <?php endif; ?>
                <?php do_action('wiloke/oz/inside_information_box'); ?>
            </div>
        </div>
        <?php if ( $wiloke->aThemeOptions['general_toggle_add_search_to_menu'] == 'enable' ) : ?>
        <div id="wil-search" class="wil-box-toggle wil-box-toggle--anim-1 body-overflow-hidden">
            <div class="wil-tb">
                <div class="wil-tb__cell"><span class="wil-box-toggle__close"><span></span><span></span></span>
                    <div class="widget_search">
                        <?php get_search_form(); ?>
                    </div>
                    <div class="wil-trend-search">
                        <h2 class="wil-trend-search__title"><?php echo esc_html($wiloke->aThemeOptions['general_search_suggestion_title']); ?></h2>
                        <ul id="wiloke-search-suggesstion" class="wil-trend-search__list" data-expired="<?php echo esc_attr($wiloke->aThemeOptions['general_search_suggestion_update']); ?>">
                            <?php
                            $aSearchSuggestion = $wiloke->frontEnd->wiloke_render_search_suggestion(true, $wiloke->aThemeOptions);
                            if ( !empty($aSearchSuggestion) ) :
                                foreach ( $aSearchSuggestion as $aInfo ) :
                                    if ( isset($post->ID) && ($aInfo['ID'] === $post->ID) ){
                                        continue;
                                    }
                            ?>
                                    <li><a href="<?php echo esc_url($aInfo['link']); ?>"><?php Wiloke::wiloke_kses_simple_html($aInfo['title']); ?></a></li>
                            <?php
                                endforeach;
                            endif;
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <!-- End / Header-->
        <?php
        /**
         * After Header
         * @hooked breadcrumb
         */
        do_action('wiloke/oz/after_header');
