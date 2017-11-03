<?php

function wiloke_shortcode_content_box($atts){
    $atts = shortcode_atts(
        array(
            'icon'         => 'icon-basic-lightbulb',
            'title'        => 'Wiloke',
            'description'  => 'Writing something talk about you here',
            'linkto'       => '#',
            'target'       => '_self',
            'alignment'    => 'text-center',
            'bg'           => '',
            'extract_class'=> '',
            'css'          => '',
            'description_color' => '',
            'title_color'       => '',
            'icon_color'        => '',
        ),
        $atts
    );

    $wrapperClass = 'wil-icon-box wil-animation ' . $atts['alignment'] . ' ' . $atts['extract_class'] . ' ' . vc_shortcode_custom_css_class($atts['css'], ' ');

    if ( !empty($atts['bg']) ) {
        $atts['bg'] = wp_get_attachment_image_url($atts['bg'], 'large');
    }

    $atts['icon'] = strpos('icon-', $atts['icon']) == 0 ? 'icon ' . $atts['icon'] : $atts['icon'];

    ob_start();
    ?>
    <div class="<?php echo esc_attr($wrapperClass); ?>">
        <?php if ( !empty($atts['linkto']) ) : ?>
        <a href="<?php echo esc_url($atts['linkto']); ?>" target="<?php echo esc_attr($atts['target']); ?>">
        <?php endif; ?>
            <?php if ( !empty($atts['bg']) ) : ?>
            <div style="background-image: url(<?php echo esc_url($atts['bg']); ?>)" class="wil-icon-box__media"></div>
            <?php endif; ?>
            <div class="wil-icon-box__content">
                <div class="wil-icon--gradient"><i class="<?php echo esc_attr($atts['icon']); ?>" style="color: <?php echo esc_attr($atts['icon_color']); ?>"></i></div>
                <div class="wil-icon-box__divider"></div>
                <h2 class="wil-icon-box__title" style="color: <?php echo esc_attr($atts['title_color']); ?>"><?php echo esc_html($atts['title']); ?></h2>
                <div class="wil-icon-box__text">
                    <p style="color: <?php echo esc_attr($atts['description_color']); ?>"><?php echo esc_html($atts['description']); ?></p>
                </div>
            </div>
            <div class="wil-overlay wil-icon-box__overlay"></div>
        <?php if ( !empty($atts['linkto']) ) : ?>
        </a>
        <?php endif; ?>
    </div>
    <?php
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}