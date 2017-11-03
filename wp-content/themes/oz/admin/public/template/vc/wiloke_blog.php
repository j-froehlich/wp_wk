<?php
if ( !function_exists('wiloke_shortcode_blog') ) {
    function wiloke_shortcode_blog($atts=array(), $isMainQuery=false){
        $aDefault = array(
            'layout'               => 'style2',
            'categories'           => '',
            'search'               => '',
            'tags'                 => '',
            'authors'              => '',
            'rest'                 => '',
            'paged'                => '',
            'pagelink'             => '',
            'pagination_type'      => 'page_numbers',
            'kind_of_pagination'   => 'ajax',
            'posts_per_page'       => get_option('posts_per_page'),
            'extract_class'        => '',
            'not_reload_page'      => 'not-reloadpage',
            'css'                  => ''
        );

        if ( empty($isMainQuery) ) {
            $atts = shortcode_atts(
                $aDefault,
                $atts
            );

            if ( empty($atts['paged']) ) {
                $atts['paged'] = is_front_page() && !is_home() ? get_query_var('page', 1) : get_query_var('paged', 1);
            }

            if ( empty($atts['paged']) ){
                $atts['paged'] = 1;
            }

            $args = array(
                'post_type'      => 'post',
                'post_status'    => 'publish',
                'posts_per_page' => absint($atts['posts_per_page']),
                'paged'          => absint($atts['paged'])
            );

            if ( !empty($atts['categories']) ) {
                $args['category__in'] = explode(',', $atts['categories']);
            }

            if ( !empty($atts['search']) ) {
                $args['s'] = $atts['search'];
            }

            if ( !empty($atts['tags']) ) {
                $args['tag__in'] = explode(',', $atts['tags']);
            }

            if ( !empty($atts['authors']) ) {
                $args['author__in'] = explode(',', $atts['authors']);
            }

            if ( !empty($atts['rest']) ){
                if ( !$aRest = json_decode($atts['rest'], true) ) {
                    $aRest = json_decode(stripslashes($atts['rest']), true);
                }

                $args = wp_parse_args($args, $aRest);
            }

            $wilokeQuery = new WP_Query($args);
        }else{
            global $wp_query; wp_reset_query();
            $atts = wp_parse_args($atts, $aDefault);
            $mainquery = $wp_query->query_vars;
            $mainquery['ignore_sticky_posts'] = true;
            $wilokeQuery = new WP_Query($mainquery);
            $atts['rest'] = json_encode($mainquery);
        }

        if ( empty($isMainQuery) ) {
            ob_start();
        }
        
        if ( $wilokeQuery->have_posts() ) :
            global $post;
            $order = 1;
            ?>
            <div id="<?php echo esc_attr(uniqid('wiloke_blog_layout')); ?>" class="wil-ef wil-section pt-0">
                <?php if ( $atts['layout'] == 'creative' ) : $atts['is_no_need_render_preloader'] = true; ?>
                    <div class="wil-blog-creative wil-animation wiloke-js-blog-wrapper <?php echo esc_attr($atts['kind_of_pagination']) ?>" data-nonce="<?php echo esc_attr(wp_create_nonce('wiloke-blog-nonce')); ?>" data-settings="<?php echo esc_attr(json_encode($atts)); ?>" data-rest="<?php echo esc_attr($atts['rest']); ?>" data-ismainquery="<?php echo esc_attr($isMainQuery); ?>">
                        <div class="container">
                            <div class="row">
                                <div class="col-xs-7 col-xs-offset-5">
                                    <div class="wil-blog-creative__effect">
                                        <div class="wil-blog-creative__effect-inner">
                                            <?php
                                                while ($wilokeQuery->have_posts()) :
                                                    $wilokeQuery->the_post();
                                                    include WILOKE_PUBLIC_DIR . 'template/vc/blog-layout/item.php';
                                                    $order++;
                                                endwhile;
                                            ?>
                                        </div>
                                    </div>
                                    <?php WilokePublic::render_pagination($wilokeQuery, $atts); ?>
                                </div>
                            </div>
                        </div>
                        <div class="wil-loader"></div>
                    </div>
                <?php else : ?>
                    <div data-animation-children=".post" class="wil-blog-grid wil-animation wil-animation--children wiloke-js-blog-wrapper <?php echo esc_attr($atts['kind_of_pagination']) ?>" data-nonce="<?php echo esc_attr(wp_create_nonce('wiloke-blog-nonce')); ?>" data-settings="<?php echo esc_attr(json_encode($atts)); ?>" data-ismainquery="<?php echo esc_attr($isMainQuery); ?>" data-rest="<?php echo esc_attr($atts['rest']); ?>">
                        <div class="row">
                            <?php
                                while ($wilokeQuery->have_posts()) :
                                    $wilokeQuery->the_post();
                                    include WILOKE_PUBLIC_DIR . 'template/vc/blog-layout/item.php';
                                    $order++;
                                endwhile;
                            ?>
                        </div>
                        <?php WilokePublic::render_pagination($wilokeQuery, $atts); ?>
                    </div>
                <?php endif; ?>
            </div>
            <?php
        else:
            get_template_part('content', 'none');
        endif;
        wp_reset_postdata();

        if ( empty($isMainQuery) ) {
            $content = ob_get_contents();
            ob_end_clean();
            return $content;
        }
    }
}
