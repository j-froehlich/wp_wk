<?php
function wiloke_shortcode_skills_bar($atts){
    $atts = shortcode_atts(
        array(
            'skills'                   => '',
            'css'                      => '',
            'toggle_animation'         => 'wil-animation',
            'uncompleted_skill_color'  => '',
            'skill_color'              => '',
            'extract_class'            => ''
        ),
        $atts
    );

    if ( empty($atts['skills']) ) {
        return;
    }

    $atts['skills'] = vc_param_group_parse_atts($atts['skills']);

    ob_start();
    $wrapperClass = 'wil-progress  ' . $atts['toggle_animation'] . ' ' . $atts['extract_class'] . ' ' . vc_shortcode_custom_css_class($atts['css'], ' ');
    $aDefault = array('skill_color'=>'', 'percentage'=>'', 'completed_skill_color'=>'');

    foreach ( $atts['skills'] as $aSkill ) :
        $aSkill['percentage'] = absint($aSkill['percentage']);
        $aSkill = wp_parse_args($aSkill, $aDefault);
    ?>
    <div aria-value="<?php echo esc_attr($aSkill['percentage']); ?>" aria-value-min="0" aria-value-max="100" class="<?php echo esc_attr($wrapperClass); ?>">
        <div class="wil-progress__text">
            <h3 class="wil-progress__name" style="color: <?php echo esc_attr($aSkill['skill_color']); ?>;"><?php echo esc_html($aSkill['skill']); ?></h3><span class="wil-progress__number" style="color: <?php echo esc_attr($aSkill['skill_color']); ?>;"><?php echo esc_html($aSkill['percentage']) . '%'; ?></span>
        </div>
        <div class="wil-progress__bar" style="background-color: <?php echo esc_attr($atts['uncompleted_skill_color']); ?>;">
            <div class="wil-progress__bar-count" style="background-color: <?php echo esc_attr($aSkill['completed_skill_color']); ?>;"></div>
        </div>
    </div>
    <?php
    endforeach;
    return ob_get_clean();
}
