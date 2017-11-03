<?php
/**
 * Create by Minh Minh
 * Team Wiloke
 * URI: wiloke.net
 */

class piAbout extends piWilokeWidgets
{
    public $aDef = array('url' => '', 'description'=>'', 'name'=>'Wiloke', 'is_social_networks'=>'no' ,'title' => 'About');

    public function __construct()
    {
        parent::__construct('wiloke_about', parent::PI_PREFIX . 'About', array('classname'=>'widget_about widget_text') );
    }

    public function form($aInstance)
    {
        $aInstance = wp_parse_args($aInstance, $this->aDef);

        $this->pi_text_field('Title', $this->get_field_id('title'), $this->get_field_name('title'), $aInstance['title']);
        $this->pi_upload_field('Logo', $this->get_field_id('image'), $this->get_field_id('upload_button'),$this->get_field_name('url'), false, $aInstance['url']);
        $this->pi_textarea_field('Description', $this->get_field_id('description'), $this->get_field_name('description'), $aInstance['description'], esc_html__('Allows the simple html tags', 'wiloke'));
        $this->pi_select_field('Social Networks', $this->get_field_id('is_social_networks'), $this->get_field_name('is_social_networks'), array('no'=>'Disable', 'yes'=>'Enable'), $aInstance['is_social_networks'], esc_html__('Go to Appearance->Asgard Options to set your social networks', 'wiloke'));

    }

    public function update($aNewinstance, $aOldinstance)
    {
        $aInstance = $aOldinstance;
        foreach ( $aNewinstance as $key => $val )
        {
            if ( $key == 'description' )
            {
                $aInstance[$key] = $val;
            }else{
                $aInstance[$key] = strip_tags($val);
            }
        }

        return $aInstance;
    }

    public function widget($atts, $aInstance)
    {
        $aInstance = wp_parse_args($aInstance, $this->aDef);

        echo $atts['before_widget'];
        if(!empty($aInstance['title'])) {
            echo $atts['before_title'] . esc_html($aInstance['title']) . $atts['after_title'];
        }
        ?>
        <div class="textwidget">
            <?php if ( !empty($aInstance['url']) ) : ?>
                <img src="<?php echo esc_url($aInstance['url']); ?>" alt="<?php echo esc_attr($aInstance['name']); ?>">
            <?php endif; ?>

            <?php if ( !empty($aInstance['description']) ) : ?>
                <p><?php echo wp_kses_post($aInstance['description']); ?></p>
            <?php endif; ?>

            <?php
            global $wiloke;
            if ( $aInstance['is_social_networks'] == 'yes' && class_exists('WilokeSocialNetworks') ) :
            ?>
                <ul class="footer-social">
                    <?php
                    foreach ( WilokeSocialNetworks::$aSocialNetworks as $key ) :
                        $socialIcon = 'fa fa-'.str_replace('_', '-', $key);

                        $key = 'social_network_'.$key;
                        if ( isset($wiloke->aThemeOptions[$key]) && !empty($wiloke->aThemeOptions[$key]) ) :
                            $separated = isset($last) && $last == $key ? '' : $separated;
                        ?>
                        <li class="social-item">
                            <a href="<?php echo esc_url($wiloke->aThemeOptions[$key]); ?>" target="_blank" title="<?php echo esc_attr($separated); ?>">
                                <i class="<?php echo esc_attr($socialIcon); ?>"></i>
                            </a>
                        </li>
                        <?php
                        endif;
                    endforeach;
                    ?>
                </ul>
            <?php endif; ?>
        </div>
        <?php
        echo $atts['after_widget'];
    }
}

?>