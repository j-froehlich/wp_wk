<?php
if ( !function_exists('wiloke_shortcode_design_shop') ) {
    function wiloke_shortcode_design_shop($atts){
        global $woocommerce_loop;

        $atts = shortcode_atts(
            array(
                'post_type'                         => 'product',
                'wiloke_portfolio_layout'           => '',
                'taxonomy'                          => 'product_cat',
                'source_of_projects'                => 'category',
                'wiloke_design_shop_choose_layout'  => '',
                'orderby'                           => 'date',
                'is_real_image'                     => '',
                'terms'                             => '',
                'include'                           => '',
                'is_parse_atts'                     => '',
                'order'                             => 'DESC',
                'posts_per_page'                    => '',
                'pagination_type'                   => 'ajax',
                'toggle_animation'                  => 'enable',
                'toggle_animation_on_mobile'        => 'enable',
                'animation_affect'                  => 'wil-anim--product',
                'ids'                               => ''
            ),
            $atts
        );

        $args = array(
            'post_type' => 'product',
            'orderby'   => 'date',
            'order'     => 'DESC'
        );
        $func = 'base64_' . 'decode';

        if ( !empty($atts['wiloke_design_shop_choose_layout']) ) {
            if ( $atts['wiloke_design_shop_choose_layout'] === 'masonry' ){
                $atts['is_real_image'] = 'yes';
            }else{
                $atts['wiloke_design_shop_choose_layout'] = json_decode($func($atts['wiloke_design_shop_choose_layout']), true);
                if ( isset($atts['wiloke_design_shop_choose_layout']['layout']) && ($atts['wiloke_design_shop_choose_layout']['layout'] == 'masonry') ){
                    $atts['is_real_image'] = 'yes';
                }
            }
        }

        $aParseData       = $func($atts['wiloke_portfolio_layout']);
        $aParseData       = json_decode($aParseData, true);
        $aGeneralSettings = $aParseData['general_settings'];
        $aShopSettings    = wiloke_parse_item_size($aParseData[$aParseData['layout']]);
        $aDevicesSettings = $aParseData['devices_settings'];

        if ( $atts['is_real_image'] == 'yes' )
        {
            $woocommerce_loop['css_class'] = ' wil-masonry-wrapper wil-animation wil_masonry--2 wil-animation--children ';
        }else{
            $woocommerce_loop['css_class'] = ' wil-masonry-wrapper wil-animation wil_masonry-grid wil-animation--children ';
        }

        if ( is_product_category() || is_product_tag() ) {
            $atts['source_of_projects'] = 'category';
            $args['category'] = get_queried_object()->slug;
            $args['taxonomy'] = 'product_tag';
        }else{
            if ( ($atts['source_of_projects'] == 'category') && !empty($atts['terms']) ) {
                $aGetTermSlugs = get_terms(
                    array(
                        'taxonomy' => 'product_cat',
                        'include'  => explode(',', $atts['terms']),
                        'fields'   => 'slugs'
                    )
                );
                if ( !empty($aGetTermSlugs) && !is_wp_error($aGetTermSlugs) ){
                    $args['category'] = implode(',', $aGetTermSlugs);
                }
            }
        }

        if ( ! empty( $atts['include'] ) ) {
            $args['ids'] = $atts['include'];
        }

        if ( $atts['is_parse_atts'] == 'yes' ) {
            return json_encode(array(
                'atts'   => $atts,
                'layout' => $aParseData,
            ));
        }

        $woocommerce_loop['is_wiloke'] = true;
        $woocommerce_loop['portfolio_layout'] = $aParseData;
        $woocommerce_loop['devices_settings'] = $aDevicesSettings;
        $woocommerce_loop['general_settings'] = $aGeneralSettings;
        $woocommerce_loop['layout_settings']  = $aShopSettings;
        $woocommerce_loop['settings'] = $atts;
        $args['per_page'] = $aParseData['general_settings']['number_of_posts'];

        $GLOBALS['wilokeTweakWoocommercePagination']  = $args['per_page'];
        $GLOBALS['wilokeWoocommerceDesignedShop']     = $atts;
        $GLOBALS['wilokeWooCommerceShopPaged']        = isset($atts['paged']) ? $atts['paged'] : null;
        if ( empty($atts['paged']) && function_exists('is_shop') && is_shop() ){
            $atts['paged'] = get_query_var('paged');
        }

        if ( !empty($atts['ids']) ) {
            $args['ids'] = implode(',', $atts['ids']);
            return WC_Shortcodes::products($args);
        }else{
            return WC_Shortcodes::recent_products($args);
        }

    }
}
