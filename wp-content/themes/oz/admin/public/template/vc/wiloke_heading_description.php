<?php
function wiloke_shortcode_heading_description($atts){
    $atts = shortcode_atts(
        array(
            'heading_tag'   => 'h2',
            'heading'       => '',
            'toggle_divider'=> 'enable',
            'divider_color' => '',
            'heading_color' => '',
            'description_color'=> '',
            'description'   => '',
            'alignment'     => 'wil-text-center',
            'css'           => '',
            'extract_class' => ''
        ),
        $atts
    );

    ob_start();
    $wrapperClass = 'wil-heading ' . $atts['extract_class'] . ' ' . $atts['alignment'] . ' ' . vc_shortcode_custom_css_class($atts['css'], ' ');
    ?>
    <div class="<?php echo esc_attr($wrapperClass); ?>">
        <?php if ( $atts['toggle_divider'] === 'enable' ) : ?>
        <div class="wil-divider wil-heading__divider" style="background-color: <?php echo esc_attr($atts['divider_color']); ?>"></div>
        <?php endif; ?>
        <<?php echo esc_attr($atts['heading_tag']); ?> class="wil-heading__title" style="color:<?php echo esc_attr($atts['heading_color']); ?>"><?php echo esc_html($atts['heading']); ?></<?php echo esc_attr($atts['heading_tag']); ?>>
        <p class="wil-heading__subtitle" style="color: <?php echo esc_attr($atts['description_color']); ?>"><?php Wiloke::wiloke_kses_simple_html($atts['description']); ?></p>
    </div>
    <?php
    return ob_get_clean();
}
