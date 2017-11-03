<?php
function wiloke_shortcode_button($atts){
    $atts = shortcode_atts(array(
        'name'            => 'Button',
        'link'            => '#',
        'target'          => '_self',
        'color'           => '',
        'style'           => '',
        'size'            => '',
        'extract_class'   => '',
        'css'             => ''
    ), $atts);

    $class = 'wil-btn ' . $atts['extract_class'] . ' ' . vc_shortcode_custom_css_class($atts['css'], ' ') . ' ' . $atts['size'] . ' ' . $atts['color'] . ' ' . $atts['style'];
    $class = trim($class);

    ob_start();
    ?>
    <a href="<?php echo esc_url($atts['link']); ?>" target="<?php echo esc_attr($atts['target']); ?>" class="<?php echo esc_attr($class); ?>"><?php echo esc_html($atts['name']); ?></a>
    <?php
    return ob_get_clean();
}