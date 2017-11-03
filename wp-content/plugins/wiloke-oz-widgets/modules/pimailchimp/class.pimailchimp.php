<?php
/**
 * Created by ninhle - wiloke team
 */


class piMailchimp extends piWilokeWidgets
{
    public $aDef  = array('title' => 'Mailchimp', 'description'=>'', 'list_id'=>'', 'caption' => '');
    public function __construct()
    {
        $args = array('classname'=>'widget_wiloke_mailchimp widget_newsletter widget_mailchimp', 'caption' => '', 'description'=>'');
        parent::__construct('wiloke_mailchimp', parent::PI_PREFIX . 'Mailchimp', $args);

        add_action('wp_enqueue_scripts', array($this, 'pi_mailchimp_frondend_js'));
    }

    public function pi_mailchimp_frondend_js()
    {
        wp_localize_script('jquery', 'piwilokewidgetsajaxurl', admin_url('admin-ajax.php'));
    }

    public function form($aInstance)
    {
        $aInstance = wp_parse_args($aInstance, $this->aDef);

        piWilokeWidgets::pi_text_field('Title', $this->get_field_id('title'), $this->get_field_name('title'), $aInstance['title']);
        piWilokeWidgets::pi_textarea_field(esc_html__('Description', 'wiloke'), $this->get_field_id('caption'), $this->get_field_name('caption'), $aInstance['caption']);
        piWilokeWidgets::pi_textarea_field(esc_html__('Warning Message', 'wiloke'), $this->get_field_id('description'), $this->get_field_name('description'), $aInstance['description']);

        $api = get_option('pi_mailchimp_api_key');
        if (  empty($api)  )
        {
            echo '<p><code>'.sprintf( (__('You haven\'t configured your mailchimp yet. Please go <a href="%s" target="_blank">here</a> to do it.', 'wiloke')), admin_url('options-general.php?page=wiloke-mailchimp') ).'</code></p>';
        }
    }

    public function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        foreach ($new_instance as $key => $value)
        {
            $instance[$key] = strip_tags($value);
        }

        return $instance;
    }

    public function widget($atts, $aInstance) {

        $aInstance = wp_parse_args($aInstance, $this->aDef);

        print $atts['before_widget'];

        if ( !empty($aInstance['title']) ) {

            print $atts['before_title'] . esc_html($aInstance['title']) . $atts['after_title'];

        }

        echo '<div class="widget__mailchimp">';

        if ( !empty($aInstance['caption']) ) {

            print '<p>'.wp_unslash($aInstance['caption']).'</p>';

        }

        if ( !get_option('pi_mailchimp_api_key') ) {

            if ( is_user_logged_in() && current_user_can('edit_theme_options') ) {

                printf( (__('<p>Please <a href="%s" target="_blank" style="color:#ff0771">config</a> your mailchimp</p>', 'wiloke')), admin_url('themes.php?page=pi-config-mailchimp') );

            }

            } else { ?>

                <div class="widget__mailchimp-form">

                    <form class="pi_subscribe">
                        <input type="email" class="pi-subscribe-email widget__mailchimp-form__input" placeholder="<?php _e('Your mail...', 'wiloke'); ?>" value="" required/>

                        <button type="submit" class="pi-btn pi-subscribe widget__mailchimp-form__submit" data-handle="<?php _e('Processing', 'wiloke'); ?>"><i class="fa fa-send"></i></button>
                    </form>

                </div>

                <p class="subscribe-status alert-done" style="display: none"><?php _e('Submit success - Bigs thank for you','wiloke');?></p>
                        <?php $ajax_nonce = wp_create_nonce( "pi_subscribe_nonce" ); ?>
                <?php
                if (!empty($aInstance['description'])) {
                  ?>
                  <p>
                    <small><?php echo wp_kses_post($aInstance['description']); ?></small>
                  </p>
                <?php
                }
                ?>
                <script type="text/javascript">

                    jQuery(".pi-btn.pi-subscribe").on('click', function()
                    {
                        var $self = jQuery(this),
                            _oringin = $self.html();
                        
                        if ( jQuery("input.pi-subscribe-email").val() != '' )
                        {
                            $self.val( $self.attr('data-handle') );
                            jQuery.post(
                                piwilokewidgetsajaxurl,
                                {
                                    action : 'pi_subscribe',
                                    // send the nonce along with the request
                                    subscribeNonce : '<?php echo esc_js($ajax_nonce); ?>',
                                    email: jQuery("input.pi-subscribe-email").val()
                                },
                                function( response ) {
                                    var data = JSON.parse(response);
                                    if(data.type=='error')
                                    {
                                        jQuery(".subscribe-status").html(data.msg).addClass("alert-error").removeClass("alert-done").fadeIn();
                                        $self.html(_oringin);
                                    }
                                    else{
                                        jQuery(".subscribe-status").html(data.msg).addClass("alert-done").removeClass("alert-error").fadeIn();
                                        jQuery("form.pi_subscribe").find(".form-remove").remove();
                                    }
                                }
                            );
                        }else{
                            jQuery(".subscribe-status").html("Please enter your e-mail").addClass("alert-error").removeClass("alert-done").fadeIn();
                        }
                        return false;
                    })
                </script>

        <?php }

        echo '</div>';

        print $atts['after_widget'];

    }
}

include plugin_dir_path(__FILE__) . 'func.mailchimp.php';
