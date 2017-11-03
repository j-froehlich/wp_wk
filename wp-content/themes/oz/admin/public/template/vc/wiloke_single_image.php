<?php
function wiloke_shortcode_single_image($atts){
    $atts = shortcode_atts(
        array(
            'image'             => '',
            'size'              => 'large',
            'caption'           => '',
            'click_action'      => 'none',
            'btn_name'          => 'Launch',
            'style'             => 'default',
            'custom_link'       => '',
            'caption_color'     => '',
            'alignment'         => 'text-center',
            'toggle_lazyload'   => 'enable',
            'target'            => '_self',
            'css'               => '',
            'extract_class'     => ''
        ),
        $atts
    );

    $wrapperClass = 'wil-img  ' . $atts['style'] . ' ' . $atts['extract_class'] . ' ' . $atts['alignment'] . ' ' . vc_shortcode_custom_css_class($atts['css'], ' ');
    ob_start();
    ?>
    <div class="<?php echo esc_attr($wrapperClass); ?>">
        <?php
        if ( !empty($atts['image']) ){
            if ( strpos($atts['size'], 'x') !== false ){
                $atts['size'] = explode('x', $atts['size']);
            }
        }
        
        if ( $atts['toggle_lazyload'] === 'disable' ){
            echo wp_get_attachment_image($atts['image'], $atts['size']);
        }else{
            $cssClass = 'lazy attachment-'.$atts['size'] . ' size-'.$atts['size'];
            ?>
            <img class="<?php echo esc_attr($cssClass); ?>" data-original="<?php echo wp_get_attachment_image_url($atts['image'], $atts['size']); ?>" alt="<?php echo esc_attr($atts['caption']); ?>" />
            <?php
        }
        

        if ( $atts['click_action'] !== 'none' ) :
            if ( $atts['click_action'] == 'to_image' ) {
                $atts['custom_link'] = wp_get_attachment_image_url($atts['image']);
            }
        ?>
            <div class="wil-overlay wil-img__overlay"></div>
            <a href="<?php echo esc_url($atts['custom_link']); ?>" class="wil-btn wil-img__btn" target="<?php echo esc_attr($atts['target']); ?>"><?php echo esc_html($atts['btn_name']); ?><div class="wil-btn--shadow"></div>
            </a>
        <?php endif; ?>

        <?php if ( !empty($atts['caption']) ) : ?>
            <h6 style="color:<?php echo esc_attr($atts['caption_color']); ?>"><?php echo esc_html($atts['caption']); ?></h6>
        <?php endif; ?>
    </div>
    <?php
    return ob_get_clean();
}
