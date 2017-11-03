<?php
function wiloke_shortcode_team_member($atts){
    $atts = shortcode_atts(
        array(
            'profile_picture'   => '',
            'name_color'        => '',
            'name'              => 'Richard Hendricks',
            'position'          => 'Senior Systems Architect',
            'social_networks'   => '',
            'position_color'    => '',
            'style'             => 'wil-team--2',
            'extract_class'     => '',
            'css'               => ''
        ),
        $atts
    );
    $wrapperClass = 'wil-team wil-animation ' . $atts['style'] . ' ' . $atts['extract_class'] . ' ' . vc_shortcode_custom_css_class($atts['css'], ' ');
    $atts['profile_picture'] = !empty($atts['profile_picture']) ? wp_get_attachment_image_url($atts['profile_picture'], 'large') : '';
    $aSocialDefault = array('social_icon_color'=>'');
    ob_start();
    ?>
    <div class="<?php echo esc_attr($wrapperClass); ?>">
        <?php if ( $atts['style'] !== 'wil-team--2' ) : ?>
        <div style="background-image: url(<?php echo esc_url($atts['profile_picture']); ?>)" class="wil-team__img">
            <div class="wil-team__over">
                <div class="wil-tb wil-team__tb">
                    <div class="wil-tb__cell">
                        <?php
                        if ( !empty($atts['social_networks']) ) :
                            $atts['social_networks'] = vc_param_group_parse_atts($atts['social_networks']);
                        ?>
                        <div class="wil-social wil-team__social">
                            <?php foreach ( $atts['social_networks'] as $aSocial ) : ?>
                            <a href="<?php echo esc_url($aSocial['link']); ?>" class="wil-social__item">
                                <i class="<?php echo esc_attr($aSocial['icon']); ?>" style="color: <?php echo esc_attr($aSocial['social_icon_color']); ?>"></i>
                            </a>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="wil-team__content">
            <h2 class="wil-team__name" style="color: <?php echo esc_attr($atts['name_color']); ?>"><?php echo esc_html($atts['name']); ?></h2>
            <span class="wil-team__work" style="color: <?php echo esc_attr($atts['position_color']); ?>"><?php echo esc_html($atts['position']); ?></span>
        </div>
        <?php else : ?>
            <div style="background-image: url(<?php echo esc_url($atts['profile_picture']); ?>)" class="wil-team__img"></div>
            <div class="wil-team__content">
                <h2 class="wil-team__name" style="color: <?php echo esc_attr($atts['name_color']); ?>"><?php echo esc_html($atts['name']); ?></h2>
                <span class="wil-team__work" style="color: <?php echo esc_attr($atts['position_color']); ?>"><?php echo esc_html($atts['position']); ?></span>
                <?php
                    if ( !empty($atts['social_networks']) ) :
                    $atts['social_networks'] = vc_param_group_parse_atts($atts['social_networks']);
                ?>
                    <div class="wil-social wil-team__social">
                        <?php foreach ( $atts['social_networks'] as $aSocial ) :
                            $aSocial = wp_parse_args($aSocial, $aSocialDefault);
                        ?>
                        <a href="<?php echo esc_url($aSocial['link']); ?>" class="wil-social__item">
                            <i class="<?php echo esc_attr($aSocial['icon']); ?>" style="color: <?php echo esc_attr($aSocial['social_icon_color']); ?>"></i>
                        </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
    <?php
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}