<?php
/**
 * WilokeAlert Class
 *
 * @category Alert
 * @package Wiloke Framework
 * @author Wiloke Team
 * @version 1.0
 */

if ( !defined('ABSPATH') )
{
    exit;
}

class WilokeAlert
{
    /**
     * Alert an error
     * @param $message The text message
     * @param $type warning - error - success - info
     * @return string
     */
    public static function message($message, $isReturn=true, $isFrontEnd=true, $classAdditional=null)
    {
        if ($isFrontEnd)
        {
            if ( !current_user_can('edit_posts') )
            {
                return;
            }
        }

        if ( $isReturn )
        {
            ob_start();
        }

        echo '<div class="wiloke-message-wrapper">';
            echo '<div class="wiloke-message-icon text-center">';
                echo '<img src="https://maps.gstatic.com/mapfiles/api-3/images/icon_error.png" />';
            echo '</div>';
            echo '<span class="wiloke-message text-center '.esc_attr($classAdditional).'">';
                echo wp_kses( $message, array(
                    'span'  => array(),
                    'a'     => array('href'=>array(), 'title'=>array()),
                    'strong'=> array()
                ) );
            echo '</span>';

        echo '</div>';

        if ( $isReturn )
        {
            $content = ob_get_contents();
            ob_clean();

            return $content;
        }
    }

    /**
     * Render Alert
     */
    public static function render_alert($content='', $status='info')
    {
        if ( is_admin() ) {
            $class = 'notice notice-'.str_replace('alert', 'error', $status);
        }else{
            $class = 'alert alert-'.$status;
        }
        ?>
        <div class="<?php echo esc_attr($class); ?>">
            <?php Wiloke::wiloke_kses_simple_html($content); ?>
        </div>
        <?php
    }
}
