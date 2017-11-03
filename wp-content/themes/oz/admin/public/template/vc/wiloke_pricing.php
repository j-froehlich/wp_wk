<?php

function wiloke_shortcode_pricing($atts){
    $atts = shortcode_atts(
        array(
            'icon_type'     => 'font-icon',
            'icon'          => 'icon-basic-lightbulb',
            'image'         => '',
            'heading'       => 'Basic',
            'currency'      => '$',
            'price'         => '09',
            'period'        => 'mo',
            'features'      => '',
            'highlight'     => '',
            'btn_name'      => 'Buy Now',
            'btn_link'      => '#',
            'css'           => '',
            'extract_class' => '',
            'icon_color'    => '',
            'heading_color' => '',
            'currency_color'=> '',
            'price_color'   => '',
            'period_color'  => '',
            'features_color'=> '',
            'button_color'  => '',
            'button_bg_color'=> ''
        ),
        $atts
    );

    if ( empty($atts['features']) ) {
        return false;
    }

    $atts['features'] = explode("\n", $atts['features']);

    $wrapperClass = 'wil-pricing ' . $atts['extract_class'] . ' ' . vc_shortcode_custom_css_class($atts['css'], ' ');

    if ( !empty($atts['highlight']) ) {
        $wrapperClass .= ' wil-pricing--highlight';
    }

    ob_start();
    ?>
    <div class="<?php echo esc_attr($wrapperClass); ?>">
        <div class="wil-pricing__header">
            <div class="wil-pricing__icon">
            <?php
            if ( $atts['icon_type'] == 'font-icon' ) :
                $atts['icon'] = strpos('icon-', $atts['icon']) == 0 ? 'icon ' . $atts['icon'] : $atts['icon'];
            ?>
                <div class="wil-icon--gradient"><i class="<?php echo esc_attr($atts['icon']); ?>" style="color: <?php echo esc_attr($atts['icon_color']) ?>"></i></div>
            <?php else : ?>
                <div class="wil-icon--image"><img src="<?php echo wp_get_attachment_image_url($atts['image'], 'large'); ?>" alt="<?php echo esc_attr($atts['heading']); ?>" /></div>
            <?php endif; ?>
            </div>
            <h3 class="wil-pricing__title" style="color: <?php echo esc_attr($atts['heading_color']) ?>"><?php echo esc_html($atts['heading']); ?></h3>
        </div>
        <div class="wil-pricing__body">
            <div class="wil-pricing__price">
                <?php if ( !empty($atts['currency']) ) : ?>
                <sup class="wil-pricing__sup" style="color: <?php echo esc_attr($atts['currency_color']) ?>"><?php echo esc_html($atts['currency']); ?></sup>
                <?php endif; ?>
                <?php if ( !empty($atts['price']) ) : ?>
                <span class="wil-pricing__amount" style="color: <?php echo esc_attr($atts['price_color']) ?>"><?php echo esc_html($atts['price']); ?></span>
                <?php endif; ?>
                <?php if ( !empty($atts['period']) ) : ?>
                <span class="wil-pricing__type" style="color: <?php echo esc_attr($atts['period_color']) ?>"><?php echo esc_html($atts['period']); ?></span>
                <?php endif; ?>
            </div>
            <?php if ( !empty($atts['features']) ) : ?>
            <ul class="wil-pricing__list">
                <?php foreach ( $atts['features'] as $feature ) :
                    $feature = str_replace(array('<p>', '</p>', '<br/>', '<br />', '<br>'), array('', '', '', '', ''), $feature);
                    if ( empty($feature) ) {
                        continue;
                    }
                ?>
                    <?php if ( strpos($feature, '*') !== false ) : ?>
                        <li class="disable" style="color: <?php echo esc_attr($atts['features_color']) ?>"><?php Wiloke::wiloke_kses_simple_html(str_replace('*', '', $feature)); ?></li>
                    <?php else : ?>
                        <li style="color: <?php echo esc_attr($atts['features_color']) ?>"><?php Wiloke::wiloke_kses_simple_html($feature); ?></li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>
        </div>
        <div class="wil-pricing__footer"><a href="<?php echo esc_url($atts['btn_link']); ?>" class="wil-btn wil-btn--gray wil-btn--large wil-pricing__btn" style="color: <?php echo esc_attr($atts['button_color']); ?>; background-color: <?php echo esc_attr($atts['button_bg_color']); ?>"><?php echo esc_html($atts['btn_name']); ?></a></div>
    </div>
    <?php
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}