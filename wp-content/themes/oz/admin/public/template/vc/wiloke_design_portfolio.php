<?php
/**
 * Render Portfolio Layout here
 * @since 1.0
 */

function wiloke_shortcode_design_portfolio($atts)
{
    $atts = shortcode_atts(
        array(
            'source_of_projects'          => 'category',
            'taxonomy'                    => 'portfolio_category',
            'project_ids'                 => '',
            'terms'                       => '',
            'toggle_animation'            => 'enable',
            'toggle_animation_on_mobile'  => 'enable',
            'is_used_design_in_loadmore'  => 'yes',
            'portfolio_animation_affect'  => 'wil-anim--work',
            'loadmore_type'               => 'none',
            'is_navigation_filter'        => 'yes',
            'navigation_tooltip'          => 'Work Filter',
            'nav_filter_style'            => 'wil-work-filter--1',
            'all_text'                    => 'All Works',
            'loadmore_btn_name'           => 'Load more',
            'wiloke_portfolio_layout'     => '',
            'wiloke_design_portfolio_choose_layout' => '',
            'hover_effect'                => 'wil-work-item--over',
            'background_color'            => '#96bbd2',
            'background_image'            => '',
            'order_by'                    => 'post_date',
            'is_real_image'               => '',
            'extract_class'               => '',
            'style_navigator_item_color'  => '',
            'style_navigator_activated_item_color'  => '',
            'style_project_overlay_color' => '',
            'style_project_title_color'   => '',
            'style_button_color'          => '',
            'style_project_category_color'=> '',
            'general_settings'=> '',
            'css'                         => ''
        ),
        $atts
    );

    if ( empty($atts['wiloke_portfolio_layout']) && ($atts['is_real_image'] != 'yes') )
    {
        return WilokeAlert::message( esc_html__('Please put some settings for this shortcode.', 'oz') );
    }

    $func = 'base64_' . 'decode';

    if ( !empty($atts['wiloke_design_portfolio_choose_layout']) ) {
        if ( $atts['wiloke_design_portfolio_choose_layout'] === 'masonry' ){
            $atts['is_real_image'] = 'yes';
        }else{
            $atts['wiloke_design_portfolio_choose_layout'] = json_decode($func($atts['wiloke_design_portfolio_choose_layout']), true);
            if ( isset($atts['wiloke_design_portfolio_choose_layout']['layout']) && ($atts['wiloke_design_portfolio_choose_layout']['layout'] == 'masonry') ){
                $atts['is_real_image'] = 'yes';
            }
        }
    }

    if ( is_tax('portfolio_category') )
    {
        $atts['source_of_projects'] = 'category';
        $atts['terms'] = get_queried_object()->term_id;
        $atts['is_navigation_filter'] = 'no';
    }

    $aParseData             = $func($atts['wiloke_portfolio_layout']);
    $aParseData             = json_decode($aParseData, true);
    $aGeneralSettings       = $aParseData['general_settings'];
    $aPortfolioData         = wiloke_parse_item_size($aParseData[$aParseData['layout']]);

    $aDevicesSettings       = $aParseData['devices_settings'];
    $atts['navigation_animation'] = $atts['portfolio_animation'] = '';

    if ( $atts['toggle_animation'] === 'enable' ){
        $atts['navigation_animation'] .= 'wil-animation';
        $atts['portfolio_animation'] .= 'wil-animation wil-animation--children';

        if ( $atts['toggle_animation_on_mobile'] != 'enable' ){
            $atts['portfolio_animation'] .=' wil-animation--disable-mobile';
        }
    }

    $isCropImage = true;

    if ( !empty($aDevicesSettings) ){
        if ( Wiloke::$mobile_detect->isTablet() ){
            if ( absint($aDevicesSettings['small']['items_per_row']) <= 2 ){
                $isCropImage = false;
            }
        }else{
            if ( absint($aDevicesSettings['medium']['items_per_row']) <= 2 ){
                $isCropImage = false;
            }
        }
    }

    $atts['isotop_id'] = uniqid('wiloke_portfolio_');
    $args = array(
        'post_type'         => 'portfolio',
        'post_status'       => 'publish',
        'posts_per_page'    => isset($aGeneralSettings['number_of_posts']) ? $aGeneralSettings['number_of_posts'] : get_option('posts_per_page')
    );

    if ( $atts['is_real_image'] == 'yes' )
    {
        $cssClass = 'wil-masonry-wrapper wil_masonry--2';
    }else{
        $cssClass = 'wil-masonry-wrapper wil_masonry-grid';
    }

    $cssClass .= ' ' . $atts['portfolio_animation'];

    $wrapperClass = 'wiloke-infinite-scroll-wrapper ' . $atts['extract_class'] . ' ' . vc_shortcode_custom_css_class($atts['css'], ' ');
    $oTerms = '';

    if ( $atts['source_of_projects'] == 'project_ids' )
    {
        if ( !empty($atts['project_ids']) )
        {
            $atts['project_ids'] = explode(',', $atts['include']);
            $atts['project_ids'] = array_map('trim', $atts['project_ids']);
        }
        $args['post__in'] = $atts['project_ids'];
    }else{
        if ( !empty($atts['terms']) )
        {
            $atts['terms'] = explode(',', $atts['terms']);
            $args['tax_query'] = array(
                array(
                    'taxonomy'  => 'portfolio_category',
                    'field'     => 'term_id',
                    'terms'     => $atts['terms']
                )
            );

            if ( $atts['is_navigation_filter'] == 'yes' ){
                $oTerms = WilokeRedisTermCaching::getTerms('portfolio_category', $atts['terms']);
            }
        }else{
            if ( $atts['is_navigation_filter'] == 'yes' ){
                $oTerms = WilokeRedisTermCaching::getTerms('portfolio_category');

                foreach ( $oTerms as $oTerm )
                {
                    $atts['terms'][] = $oTerm->term_id;
                    Wiloke::setTermsCaching('portfolio_category', array($oTerm->term_id=>$oTerm));
                }

                $args['tax_query']['terms'] = $atts['terms'];
            }
        }
    }
    $atts['is_crop_image'] = $isCropImage;

    $args['orderby'] = $atts['order_by'];

    $query = new WP_Query($args);

    if ( $query->have_posts() ) :
        global $post, $wiloke;
        ob_start();
    ?>

    <!-- He will help me how to detect Large and Medium Desktops device -->
    <script type="text/javascript">
        WILOKE_GLOBAL.portfolio_data['<?php echo esc_attr($atts['isotop_id']) ?>'] = '<?php echo json_encode($atts); ?>';
    </script>

    <div class="<?php echo esc_attr(trim($wrapperClass)); ?>">

        <?php wiloke_shortcode_render_nav_filter($atts, $oTerms); ?>

        <div
            <?php
            WilokePublic::render_attributes(
                array(
                    'class'                   => $cssClass,
                    'data-lg-vertical'        => $aDevicesSettings['large']['vertical'],
                    'data-lg-horizontal'      => $aDevicesSettings['large']['horizontal'],
                    'data-md-vertical'        => $aDevicesSettings['medium']['vertical'],
                    'data-md-horizontal'      => $aDevicesSettings['medium']['horizontal'],
                    'data-sm-vertical'        => $aDevicesSettings['small']['vertical'],
                    'data-sm-horizontal'      => $aDevicesSettings['small']['horizontal'],
                    'data-xs-vertical'        => $aDevicesSettings['extra_small']['vertical'],
                    'data-xs-horizontal'      => $aDevicesSettings['extra_small']['horizontal'],
                    'data-col-lg'             => $aDevicesSettings['large']['items_per_row'],
                    'data-col-md'             => $aDevicesSettings['medium']['items_per_row'],
                    'data-col-sm'             => $aDevicesSettings['small']['items_per_row'],
                    'data-col-xms'            => $aDevicesSettings['extra_small']['items_per_row'],
                    'data-animation-type'     => $atts['portfolio_animation_affect'],
                    'data-animation-children' => '.wil-work-item__anim',
                    'data-buttoncolor'        => $atts['style_button_color']
                )
            );
            ?>
        >

            <?php do_action('wiloke/wiloke_shortcode_design_portfolio/before_design_layout', $query, $aParseData, $atts); ?>
            
            <div class="wil_masonry wiloke-items-store">
                <div class="grid-sizer"></div>
                <?php
                /**
                 * Hooked: oz_query_portfolio 10
                 */
                $wilokeI = 0;
                while ($query->have_posts())
                {
                    $query->the_post();
                    $atts['post__not_in'][] = $post->ID;

                    include Wiloke::$public_path.'template/vc/portfolio/item.php';
                    $wilokeI++;
                }

                wp_reset_postdata();
                ?>
            </div>

            <?php
            /**
             * Hooked: render_loadmore_btn 10
             */
            do_action('wiloke/wiloke_shortcode_design_portfolio/after_design_layout', $query, $aParseData, $atts);
            ?>

        </div> <!-- END / WORK ISOTOPE -->
    </div>

    <?php
    else :
        WilokeAlert::message(esc_html__('There are no projects. Please go to Portfolio then create some ones or recheck  the settings of the shortcode.', 'oz'), false);
        wp_reset_postdata();
    endif;
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
}

function wiloke_shortcode_render_nav_filter($atts, $oTerms)
{
    if ( $atts['is_navigation_filter'] != 'yes' ){
        return;
    }

    ?>
    <div data-activatedcolor="<?php echo esc_attr($atts['style_navigator_activated_item_color']); ?>" data-currentcolor="<?php echo esc_attr($atts['style_navigator_item_color']); ?>" class="wil-work-filter <?php echo esc_attr($atts['navigation_animation'] . ' ' . $atts['nav_filter_style']); ?>">
        <ul class="wil-work-filter__list">
            <li class="current"><a href="#" data-filter="*"><?php echo esc_html($atts['all_text']); ?></a></li>
            <?php foreach ( $oTerms as $oTerm ) : ?>
            <li data-termid="<?php echo esc_attr($oTerm->term_id); ?>" data-total="<?php echo esc_attr($oTerm->count); ?>"><a href="#" data-filter=".<?php echo esc_attr($oTerm->slug); ?>"><?php echo esc_html($oTerm->name); ?></a></li>
            <?php endforeach; ?>
        </ul>
        <div title="<?php echo esc_attr($atts['navigation_tooltip']); ?>" class="wil-work-filter__icon"><span></span><span></span><span></span></div>
    </div>
    <?php
}

function wiloke_parse_item_size($aParseData){
    if ( !empty($aParseData['items_size']) && is_array($aParseData['items_size']) ) {
        return $aParseData;
    }

    if ( strpos($aParseData['items_size'], '*') !== false )
    {
        $sizes = '';

        $aParseData['items_size'] = explode(',', $aParseData['items_size']);

        foreach ( $aParseData['items_size'] as $val )
        {
            if ( strpos($val, '*') !== false )
            {
                $aParse = explode('*', $val);
                for ( $i = 1; $i <= $aParse[1]; $i++ )
                {
                    $sizes .= ',' . $aParse[0];
                }
            }else{
                $sizes .= ',' . $val;
            }
        }

        $aParseData['items_size'] = trim($sizes, ',');
    }
    $aParseData['items_size'] = explode(',', $aParseData['items_size']);
    return $aParseData;
}