<?php
function wiloke_shortcode_swiper_slider($atts){
    $atts = shortcode_atts(
        array(
            'post_type'                  => '',
            'include'                    => '',
            'taxonomies'                 => '',
            'attachments'                => '',
            'toggle_pagination'          => 'true',
            'toggle_navigation'          => 'true',
            'navigation_pagination_style'=> 2,
            'slides_per_view'            => 1,
            'extract_class'              => '',
            'image_size'                 => 'large',
            'width'                      => '',
            'height'                     => '',
            'css'                        => ''
        ),
        $atts
    );

    $wrapperClass = 'wil-swiper ' . $atts['extract_class'] . ' ' . vc_shortcode_custom_css_class($atts['css'], ' ');
    
    ob_start();
    ?>
    <div data-swiper-pagination="<?php echo esc_attr($atts['toggle_navigation']); ?>" data-swiper-navigation="<?php echo esc_attr($atts['toggle_pagination']); ?>" data-swiper-pagination-style="<?php echo esc_attr($atts['navigation_pagination_style']); ?>" data-swiper-navigation-style="<?php echo esc_attr($atts['navigation_pagination_style']); ?>" class="<?php echo esc_attr($wrapperClass); ?>" data-swiper-slidesperview="<?php echo esc_attr($atts['slides_per_view']); ?>" data-swiper-width="<?php echo esc_attr($atts['width']); ?>" data-swiper-height="<?php echo esc_attr($atts['height']); ?>">

        <?php 
            if ( $atts['post_type'] == 'attachment' ) :
                if ( empty($atts['attachments']) ) {
                    return;
                }

                $atts['attachments'] = explode(',', $atts['attachments']);
        ?>
        <div class="swiper-wrapper">
            <?php foreach ( $atts['attachments'] as $attachID ) : ?>
            <div class="swiper-slide">
                <?php echo wp_get_attachment_image($attachID, $atts['image_size']); ?>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else : ?>
            <?php
                if ( $atts['post_type'] == 'ids' ) {
                    if ( $atts['include']  ) {
                        return;
                    }
                    $args['post__in'] =  array_map(explode(',', $atts['include']), 'trim');
                }else{
                    $args['post_type'] = $atts['post_type'];
                    if ( !empty($args['taxonomies']) ) {
                        $aTaxes =  array_map(explode(',', $atts['taxonomies']), 'trim');

                        switch ($atts['post_type']) {
                            case 'post':
                                $args['category__in'] = $aTaxes;
                                break;
                            case 'product':
                                $args['tax_query'] = array(
                                    array(
                                        'taxonomy' => 'product_cat',
                                        'field'    => 'term_id',
                                        'terms'    => $aTaxes
                                    ),
                                );
                                break;
                            case 'portfolio':
                                $args['tax_query'] = array(
                                    array(
                                        'taxonomy' => 'portfolio_category',
                                        'field'    => 'term_id',
                                        'terms'    => $aTaxes
                                    ),
                                );
                                break;
                        }

                        $args['post_status'] = 'publish';
                        $args['ignore_sticky_posts'] = 1;
                        $args['posts_per_page'] = absint($atts['number_of_posts']);
                    }
                }

                $wilokeQuery = new WP_Query($args);

                if ( $wilokeQuery->have_posts() ) :
                ?>
                    <div class="swiper-wrapper">
                        <?php
                            while ($wilokeQuery->have_posts()) :
                                $wilokeQuery->the_post();
                                if ( !has_post_thumbnail($wilokeQuery->post->ID) ) {
                                    continue;
                                }
                        ?>
                        <div class="swiper-slide">
                            <a href="<?php echo esc_url(get_permalink($wilokeQuery->post->ID)); ?>">
                                <?php echo get_the_post_thumbnail($wilokeQuery->post->ID, $atts['image_size']); ?>
                            </a>
                        </div>
                        <?php
                            endwhile; wp_reset_postdata();
                        ?>
                    </div>
                <?php
                endif;
            ?>
        <?php endif; ?>

        <?php if ( $atts['toggle_pagination'] == 'true' ) : ?>
        <div class="wil-swiper__pagination"></div>
        <?php endif; ?>

        <?php if ( $atts['toggle_pagination'] == 'true' ) : ?>
        <div class="wil-swiper__button">
            <div class="wil-swiper__button-item wil-swiper__button-prev"><i class="fa fa-angle-left"></i></div>
            <div class="wil-swiper__button-item wil-swiper__button-next"><i class="fa fa-angle-right"></i></div>
        </div>
        <?php endif; ?>

        <div class="wil-loader wil-loader--color"></div>
    </div>
    <?php
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}