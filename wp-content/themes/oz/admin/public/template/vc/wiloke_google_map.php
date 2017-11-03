<?php
function wiloke_shortcode_google_map($atts){
    $atts = shortcode_atts(
        array(
            'lat_long'      => '',
            'style'         => 'grayscale',
            'info'          => 'Wiloke - Professional WordPress',
            'height'        => 500,
            'zoom'          => 13,
            'marker'        => '',
            'css'           => '',
            'extract_class' => ''
        ),
        $atts
    );

    if ( empty($atts['lat_long']) ) {
        return;
    }

    ob_start();
    $wrapperClass = 'wil-map ' . $atts['extract_class'] . ' ' . vc_shortcode_custom_css_class($atts['css'], ' ');
    $wrapperClass = trim($wrapperClass);

    if ( !empty($atts['marker']) ) {
        $atts['marker'] = wp_get_attachment_image_url($atts['marker'], 'thumbnail');
    }

    $atts['lat_long'] = explode('x', $atts['lat_long']);
    ?>
    <div id="wiloke-googlemap" class="<?php echo esc_attr($wrapperClass); ?>" style="height: <?php echo esc_attr($atts['height']) ?>px;" data-style="<?php echo esc_attr($atts['style']); ?>" data-marker="<?php echo esc_url($atts['marker']); ?>" data-lat="<?php echo esc_attr($atts['lat_long'][0]); ?>" data-long="<?php echo esc_attr($atts['lat_long'][1]); ?>" data-info="<?php echo esc_attr($atts['info']); ?>" data-zoom="<?php echo esc_attr($atts['zoom']); ?>"></div>
    <?php
    return ob_get_clean();
}
