<?php
function wiloke_shortcode_testimonials($atts){
    $atts = shortcode_atts(
        array(
            'testimonials'      => '',
            'css'               => '',
            'extract_class'     => ''
        ),
        $atts
    );

    $wrapperClass = 'wil-swiper wil-animation ' . $atts['extract_class'] . ' ' . vc_shortcode_custom_css_class($atts['css'], ' ');

    if ( empty($atts['testimonials']) ) {
        return;
    }

    $aTestimonialDefault = array('client_name_color'=>'', 'client_professional_color'=>'', 'testimonial_color'=>'');

    $atts['testimonials'] = vc_param_group_parse_atts($atts['testimonials']);

    ob_start();
    ?>
    <div class="<?php echo esc_attr($wrapperClass); ?>" data-swiper-pagination="false" data-swiper-navigation="true">
            <div class="swiper-wrapper">
                <?php foreach ( $atts['testimonials'] as $aTestimonial ) :
                    ?>
                    <div class="swiper-slide">
                        <div class="wil-blockquote">
                            <div class="wil-blockquote__header">
                                <?php if ( !empty($aTestimonial['client_picture']) ) :
                                    $aTestimonial = wp_parse_args($aTestimonial, $aTestimonialDefault);
                                ?>
                                <div style="background-image: url(<?php echo esc_url(wp_get_attachment_image_url($aTestimonial['client_picture'], 'thumbnail')); ?>)" class="wil-blockquote__avatar"></div>
                                <?php endif; ?>
                                <cite class="wil-blockquote__name" style="color: <?php echo esc_attr($aTestimonial['client_name_color']); ?>"><?php echo esc_html($aTestimonial['client_name']); ?></cite>
                                <span class="wil-blockquote__work" style="color: <?php echo esc_attr($aTestimonial['client_professional_color']); ?>"><?php echo esc_html($aTestimonial['client_professional']); ?></span>
                            </div>
                            <div class="wil-blockquote__divider"><span class="wil-blockquote__divider-item"></span><span class="wil-blockquote__divider-item"></span><span class="wil-blockquote__divider-item"></span></div>
                            <div class="wil-blockquote__body">
                                <p style="color: <?php echo esc_attr($aTestimonial['testimonial_color']); ?>"><?php Wiloke::wiloke_kses_simple_html($aTestimonial['testimonial']); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="wil-swiper__pagination"></div>
            <div class="wil-swiper__button">
                <div class="wil-swiper__button-item wil-swiper__button-prev"><i class="fa fa-angle-left"></i></div>
                <div class="wil-swiper__button-item wil-swiper__button-next"><i class="fa fa-angle-right"></i></div>
            </div>
        </div>
    <?php
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}