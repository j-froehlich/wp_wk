<?php

class WilokeComingsoon
{
    public $aData;
    public $url;

    public function __construct()
    {
        $this->url = plugin_dir_url( dirname(__FILE__) ) . 'public/comingsoon/';
        $this->aData = get_option('pi_comingsoon');
    }

    public function register_menu(){
        add_submenu_page( WilokeService::$aSlug['main'], esc_html__('Maintain mode', 'wiloke-service'), esc_html__('Maintain mode', 'wiloke-service'), 'edit_theme_options', WilokeService::$aSlug['comingsoon'], array($this, 'pi_comingsoon_settings'));
    }

    public function comingsoon_actived()
    {
        if( isset($this->aData['toggle']) && !empty($this->aData['toggle']) ) {
            if (!current_user_can('edit_theme_options')) {
                add_action('template_redirect', array($this, 'load_it_front_end'));
            }
        }
    }

    public function register_foot_scripts()
    {
        echo '<script type="text/javascript" src="'.esc_url($this->url.'asset/js/jquery-1.11.2.min.js').'"></script>';
        echo '<script type="text/javascript" src="'.esc_url($this->url.'asset/js/jquery.countdown.min.js').'"></script>';
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function($)
            {
                $('#countdown').countdown('<?php echo esc_js($this->aData["countdown"]);  ?>', function(event) {
                    $(this).html(event.strftime(''
                        + '<div class="item"><span class="count">%D</span><span>Days</span></div>'
                        + '<div class="item"><span class="count">%H</span><span>Hours</span></div>'
                        + '<div class="item"><span class="count">%M</span><span>Minutes</span></div>'
                        + '<div class="item"><span class="count">%S</span><span>Seconds</span></div>'));
                });
            })
        </script>
        <?php
        echo '<script type="text/javascript" src="'.esc_url($this->url.'source/js/script.js').'"></script>';
    }

    public function register_head_scripts(){
        echo '<link rel="stylesheet" type="text/css" href="'.esc_url($this->url.'source/css/style.css').'">';
    }

    public function load_it_front_end()
    {
        if( !is_feed() )
        {
            include( plugin_dir_path( dirname(__FILE__) ) . 'public/comingsoon/comingsoon.php' );
            die();
        }
    }

    public function pi_comingsoon_settings()
    {
        $args = array(
            'toggle'    => 0,
            'background'=> '',
            'heading'   => 'OUR WEBSITE ARE COMING SOON',
            'countdown' => '2017/06/22',
        );

        if ( isset($_POST['wiloke_comingsoon_nonce']) && wp_verify_nonce($_POST['wiloke_comingsoon_nonce'], 'wiloke_comingsoon_action') )
        {
            update_option('pi_comingsoon', $_POST['comingsoon']);
            $aData = $_POST['comingsoon'];
        }else{
            $aData = get_option('pi_comingsoon');
        }

        $aData = wp_parse_args($aData, $args);

        include plugin_dir_path( dirname(__FILE__) ) . 'admin/html/comingsoon/settings.php';
    }
}
