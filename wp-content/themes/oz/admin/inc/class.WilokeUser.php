<?php
/**
 * WilokeUser Class
 *
 * @category General
 * @package Wiloke Framework
 * @author Wiloke Team
 * @version 1.0
 */

if ( !defined('ABSPATH') )
{
    exit;
}

class WilokeUser
{
    public function __construct()
    {
        add_action('admin_enqueue_scripts', array($this, 'wiloke_admin_enqueue_scripts'));
        add_action('personal_options', array($this, 'wiloke_profile_fields'));
        add_action('personal_options_update', array($this, 'wiloke_profile_update'));
        add_action('edit_user_profile_update', array($this, 'wiloke_profile_update'));
    }

    public function wiloke_profile_update($user_id)
    {
        if ( current_user_can('edit_user',$user_id) )
        {
            if ( isset($_POST['thumbnail_id']) && !empty($_POST['thumbnail_id']) )
            {
                update_user_meta($user_id, "wiloke_avatar", $_POST['thumbnail_id']);
            }

            if ( isset($_POST['wiloke_user_socials']) && !empty($_POST['wiloke_user_socials']) )
            {
                update_user_meta($user_id, "wiloke_user_socials", $_POST['wiloke_user_socials']);
            }
        }
    }

    public function wiloke_admin_enqueue_scripts($page)
    {
        if ( isset($page) && ( $page == 'user-edit.php' || $page == 'profile.php' ) )
        {
            // Check Wiloke Post Format plugin is activating or not
            if (!wp_script_is('wiloke_post_format', 'enqueued')) {
                wp_enqueue_media();

                wp_enqueue_script('wiloke_post_format_ui', WILOKE_AD_SOURCE_URI . 'js/wiloke_post_format.js', array('jquery'), null, true);
                wp_enqueue_style('wiloke_post_format_ui', WILOKE_AD_SOURCE_URI . 'css/wiloke_post_format.css', array(), null);

                wp_enqueue_script('wiloke_taxonomy', WILOKE_AD_SOURCE_URI . 'js/taxonomy.js', array('jquery', 'wiloke_post_format_ui'), null, true);
            }
        }
    }

    public function wiloke_profile_fields( $user )
    {
        $imgID      = get_user_meta($user->ID, 'wiloke_avatar', true );
        $aSocials   = get_user_meta($user->ID, 'wiloke_user_socials', true);

        $aDefault   = array(
            'facebook'      => '',
            'twitter'       => '',
            'google_plus'   => '',
            'linkedin'      => '',
            'dribbble'      => '',
            'pinterest'     => '',
        );

        $aSocials = wp_parse_args($aSocials, $aDefault);

        ?>
        <table class="form-table">
            <tbody>
                <tr class="user-rich-editing-wrap">
                    <?php WilokeHtmlHelper::wiloke_render_media_field( array('id'=>'thumbnail_id', 'value'=>$imgID, 'name'=>'Avatar', 'description'=>esc_html__('An picture of 170x170px is recommended by your theme.', 'oz'))); ?>
                </tr>
            </tbody>
        </table>

        <table class="form-table">
            <thead><strong><?php esc_html_e('Social Networks', 'oz'); ?></strong></thead>
            <tbody>
                <tr>
                    <th>
                        <label for="facebook"><?php esc_html_e('Facebook', 'oz'); ?></label>
                    </th>
                    <td>
                        <input type="text" name="wiloke_user_socials[facebook]" value="<?php echo esc_url($aSocials['facebook']); ?>">
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="facebook"><?php esc_html_e('Twitter', 'oz'); ?></label>
                    </th>
                    <td>
                        <input type="text" name="wiloke_user_socials[twitter]"  value="<?php echo esc_url($aSocials['twitter']); ?>">
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="facebook"><?php esc_html_e('Google+', 'oz'); ?></label>
                    </th>
                    <td>
                        <input type="text" name="wiloke_user_socials[google_plus]" value="<?php echo esc_url($aSocials['google_plus']); ?>">
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="facebook"><?php esc_html_e('Linkedin', 'oz'); ?></label>
                    </th>
                    <td>
                        <input type="text" name="wiloke_user_socials[linkedin]" value="<?php echo esc_url($aSocials['linkedin']); ?>">
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="facebook"><?php esc_html_e('Dribbble', 'oz'); ?></label>
                    </th>
                    <td>
                        <input type="text" name="wiloke_user_socials[dribbble]" value="<?php echo esc_url($aSocials['dribbble']); ?>">
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="facebook"><?php esc_html_e('Pinterest', 'oz'); ?></label>
                    </th>
                    <td>
                        <input type="text" name="wiloke_user_socials[pinterest]"  value="<?php echo esc_url($aSocials['pinterest']); ?>">
                    </td>
                </tr>
            </tbody>
        </table>

        <?php
    }
}