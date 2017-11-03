<?php
function wiloke_shortcode_countto($atts){
    $atts = shortcode_atts(
        array(
            'countto'       => 3,
            'countto_color' => '',
            'heading'       => 'Cup Of Coffee',
            'heading_color' => '',
            'divider_color' => '',
            'css'           => '',
            'extract_class' => ''
        ),
        $atts
    );
    $wrapperClass = 'wil-counter wil-animation ' . $atts['extract_class'] . ' ' . vc_shortcode_custom_css_class($atts['css'], ' ');
    ob_start();
    ?>
    <div class="<?php echo esc_attr($wrapperClass); ?>">
        <div data-from="0" data-to="<?php echo esc_attr($atts['countto']); ?>" class="wil-odometer wil-counter__number" style="color:<?php echo esc_attr($atts['countto_color']); ?>"><?php echo esc_html($atts['countto']); ?></div>
        <div class="wil-divider wil-counter__divider" style="background-color: <?php echo esc_attr($atts['divider_color']); ?>"></div>
        <h3 class="wil-counter__title" style="color: <?php echo esc_attr($atts['heading_color']); ?>;"><?php echo esc_attr($atts['heading']); ?></h3>
    </div>
    <?php
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}