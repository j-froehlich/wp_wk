<?php
/**
 * WilokeAdminGeneral Class
 *
 * @category General
 * @package Wiloke Framework
 * @author Wiloke Team
 * @version 1.0.1
 */

if ( !defined('ABSPATH') )
{
    exit;
}

class WilokeAdminGeneral
{
    public $aOptions;
    /**
     * Unset Audio Post Format
     * @since 1.0
     */
    public function add_toggle_live_builder_settings_button(){
        global $post;
        if ( !isset($post->post_type) || $post->post_type != 'portfolio' ) {
            return false;
        }

        ?>
        <div id="wiloke-toggle-live-builder-and-settings-button-wrapper">
            <div class="wiloke-toggle-live-builder-and-settings-button-wrapper__inner">
                <div id="wiloke-portfolio-toggle-live-builder-button" class="wiloke-portfolio-toggle-live-builder-button">
                    <div class="icon">
                        <img src="<?php echo esc_url(WILOKE_AD_ASSET_URI . 'img/edit-live.png'); ?>" alt="<?php esc_html_e('Live Editor', 'oz'); ?>" />
                    </div>
                    <h2><?php esc_html_e('Live editor', 'oz'); ?></h2>
                    <p><?php esc_html_e('Choosing this mode to build your project as visual way (recommended)', 'oz'); ?></p>
                </div>
                <div class="wiloke-portfolio-toggle-settings-button">
                    <div class="icon">
                        <img src="<?php echo esc_url(WILOKE_AD_ASSET_URI . 'img/edit.png'); ?>" alt="<?php esc_html_e('Classic Editor', 'oz'); ?>" />
                    </div>
                    <h2><?php esc_html_e('Classic editor', 'oz'); ?></h2>
                    <p><?php esc_html_e('Choosing this mode to build your project as normal way', 'oz'); ?></p>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Add Theme Color
     * @since 1.0
     */
    public function add_set_theme_color($aMetaBox, $metaboxID){

        if ( !in_array('portfolio', $aMetaBox[$metaboxID]['pages']) || $metaboxID != 'project_general_settings' ) {
            return $aMetaBox;
        }

        foreach ( $aMetaBox[$metaboxID]['fields'] as $key => $aField ) {
            if ( $aField['id'] != 'project_style' ) {
                continue;
            }else{
                array_splice($aMetaBox[$metaboxID]['fields'], $key, 0, array(array(
                    'type'          => 'colorpicker',
                    'name'          => esc_html__('Override Theme Color', 'oz'),
                    'description'   => esc_html__('Set a main color of the page. This color will be filled in Menu, Footer, Button (In the case you are selecting  Theme Color Mode), etc.', 'oz'),
                    'id'            => 'theme_color'
                )) );

                return $aMetaBox;
            }
        }
        return $aMetaBox;
    }

    /**
     * WordPress Editor Placeholder
     * @since 1.0
     */
    public function add_wp_editor_placeholder(){
        ?>
<!--        <div class="wiloke-wp-editor-placeholder">--><?php //esc_html__('Enter content here', 'oz'); ?><!--</div>-->
        <?php
    }

    /**
     * Unset Audio Post Format
     * @since 1.0
     */
    public function unset_audio_post_format($aPostFormat){
        if ( is_array($aPostFormat[0]) ) {
            unset($aPostFormat[0][3]);
            return $aPostFormat;
        }

        return $aPostFormat;
    }

    /**
     * These buttons only display on portfolio page
     * @since 1.0
     */
    public function add_save_and_settings_btn(){
        global $post;
        if ( !isset($post->post_type) || $post->post_type != 'portfolio' ) {
            return false;
        }

        ?>
        <div id="wiloke-portfolio-button-wrapper">
            <button class="button button-primary wiloke-portfolio-settings-button"><?php esc_html_e('Settings', 'oz'); ?></button>
            <button class="button button-primary wiloke-portfolio-rest-settings-button"><?php esc_html_e('More', 'oz'); ?></button>
            <button id="wiloke-portfolio-save-button" class="button button-primary wiloke-portfolio-save-button"><?php esc_html_e('Save', 'oz'); ?></button>
            <button class="button button-primary wiloke-portfolio-cancel-button"><?php esc_html_e('Close', 'oz'); ?></button>
        </div>
        <?php
    }

    /**
     * Render Entries Meta after the post title
     * @since 1.0
     */
    public function render_entries_meta(){
        global $post, $wiloke;
        if ( !isset($post->post_type) || $post->post_type != 'portfolio' ) {
            return false;
        }

        $aPostMeta = Wiloke::getPostMetaCaching($post->ID, 'project_general_settings');

        echo '<div class="wiloke-portfolio-entries-meta">';
        $wiloke->frontEnd->render_project_info($post, $aPostMeta);
        echo '</div>';
    }

    public function get_options(){
        if ( is_admin() ) {
            return;
        }

        $this->aOptions = get_option('wiloke_permalink');
    }

    public function rewrite_post_link(){
        global $wiloke;

        if ( isset($_POST['wiloke-permalinks-action']) && isset($_POST['wiloke_permalink']) ){
            $aData = array_map('sanitize_text_field', $_POST['wiloke_permalink']);
            update_option('wiloke_permalink', $aData);
            unset($_POST['wiloke_permalink']);
            flush_rewrite_rules();
        }

        $oValues = get_option('wiloke_permalink');

        echo '<div class="wrap">';
            echo '<form action="'.esc_url(admin_url('options-general.php?page=wiloke-permalinks')).'" method="POST">';
                if ( isset($wiloke->aConfigs['general']['rewrite_link']) ) :
            foreach ( $wiloke->aConfigs['general']['rewrite_link'] as $postType => $aData ) :
                $value = isset($oValues[$postType]) ? $oValues[$postType] : 'without-posttype';
                ?>
                <h2 class="title"><?php echo esc_html($aData['title']); ?></h2>
                <table class="form-table permalink-structure">
                    <tbody>
                        <tr>
                            <th><label><input id='wiloke_<?php echo esc_attr($postType) ?>_permalink_permalink_1' name='wiloke_permalink[<?php echo esc_attr($postType) ?>]' type='radio' value='<?php echo esc_attr($postType) ?>' <?php checked($value, $postType); ?> /> <?php esc_html_e('Plain', 'oz'); ?></label></th>
                            <td><code><?php echo esc_url(get_option('siteurl')); ?>/portfolio/%postname%</code></td>
                        </tr>
                        <?php if ( isset($aData['taxonomy']) ) : ?>
                        <tr>
                            <th><label><input id='wiloke_<?php echo esc_attr($postType) ?>_permalink_2' name='wiloke_permalink[<?php echo esc_attr($postType) ?>]' type='radio' value='<?php echo esc_attr($aData['taxonomy']); ?>' <?php checked($value, $aData['taxonomy']); ?>/> <?php esc_html_e('Based on Category', 'oz'); ?></label></th>
                            <td><label for="wiloke_<?php echo esc_attr($postType) ?>_permalink_2"><code><?php echo esc_url(get_option('siteurl')); ?>/category-slug/%postname%</code></label></td>
                        </tr>
                    <?php endif; ?>
                        <tr>
                            <th><label><input id='wiloke_<?php echo esc_attr($postType) ?>_permalink_3' name='wiloke_permalink[<?php echo esc_attr($postType) ?>]' type='radio' value='without-posttype' <?php checked($value, 'without-posttype'); ?>/> <?php esc_html_e('Post name', 'oz'); ?></label></th>
                            <td><label for="wiloke_<?php echo esc_attr($postType) ?>_permalink_3"><code><?php echo esc_url(get_option('siteurl')); ?>/%postname%</code></label></td>
                        </>
                    </tbody>
                </table>
                <?php
            endforeach;
        endif;
                wp_nonce_field('wiloke-permalinks-nonce', 'wiloke-permalinks-action');
                submit_button();
            echo '</form>';
        echo '</div>';
    }

    /**
     * Render admin notices
     * @since 1.0
     */
    public function notices()
    {
        if ( !empty(Wiloke::$list_of_errors) )
        {
            foreach ( Wiloke::$list_of_errors as $error => $aMessages )
            {
                foreach ( $aMessages as $message )
                {
                    ?>
                    <div class="notice notice-<?php echo esc_attr($error); ?> is-dismissible">
                        <p><?php Wiloke::wiloke_kses_simple_html($message); ?></p>
                    </div>
                    <?php
                }
            }
        }
    }

    /**
     * Enqueue scripts to admin
     * @since 1.0
     */
    public function enqueue_scripts($hook)
    {
        global $post, $wiloke;

        $min = defined('WILOKE_SCRIPT_DEBUG') && WILOKE_SCRIPT_DEBUG ? '.' : '.min.';

        if ( !wp_script_is('wiloke_post_format', 'enqueued') )
        {
            wp_enqueue_media();

            wp_enqueue_script('wiloke_post_format_ui', WILOKE_AD_SOURCE_URI . 'js/wiloke_post_format'.$min.'js', array('jquery'), null, true);
            wp_enqueue_style('wiloke_post_format_ui', WILOKE_AD_SOURCE_URI . 'css/wiloke_post_format.css', array(), null);

            wp_enqueue_script('wiloke_taxonomy', WILOKE_AD_SOURCE_URI . 'js/taxonomy'.$min.'js', array('jquery', 'wiloke_post_format_ui'), null, true);
        }

        wp_enqueue_script('wiloke_ad_waypoints', WILOKE_AD_ASSET_URI . 'js/jquery.waypoints.min.js', array('jquery'), null, true);
        wp_enqueue_script('wiloke_ad_shortcodes', WILOKE_AD_SOURCE_URI . 'js/shortcode'.$min.'js', array('jquery'), null, true);

        wp_register_script('wiloke_spectrum', WILOKE_AD_ASSET_URI . 'js/spectrum.js', array('jquery'), null, true);
        wp_register_style('wiloke_spectrum', WILOKE_AD_ASSET_URI . 'css/spectrum.css', array(), null);

        wp_enqueue_style('wiloke_ad_shortcodes', WILOKE_AD_SOURCE_URI . 'css/shortcode.css', array(), null);
        wp_enqueue_style('wiloke_design_layout', WILOKE_AD_SOURCE_URI . 'design-layout/css/style.css');
        wp_enqueue_style('wiloke_admin_general', WILOKE_AD_SOURCE_URI . 'css/style.css');

        wp_enqueue_script('wiloke_design_layout', WILOKE_AD_SOURCE_URI . 'design-layout/js/portfolio-layout'.$min.'js', array('jquery'), null, true);

        wp_enqueue_style('linea-font', WILOKE_THEME_URI . 'css/lib/linea-font.min.css');
        wp_enqueue_style('introjs', WILOKE_AD_ASSET_URI . 'css/introjs.css');
        wp_enqueue_style('introjs-flattener', WILOKE_AD_ASSET_URI . 'css/introjs-flattener.css');
        wp_enqueue_script('introjs', WILOKE_AD_ASSET_URI . 'js/intro.js', array('jquery'), null, true);
        wp_enqueue_script('wilokeintro', WILOKE_AD_SOURCE_URI . 'js/wilokeintro'.$min.'js', array('jquery'), null, true);
        wp_enqueue_script('wiloke_globaljs', WILOKE_AD_SOURCE_URI . 'js/scripts'.$min.'js', array('jquery'), null, true);

        if ( isset($wiloke->aConfigs['tours']) && isset($wiloke->aConfigs['tours']['admin']) )
        {
            wp_localize_script('introjs', 'WILOKE_TOURS', $wiloke->aConfigs['tours']['admin']);
        }

        $aIntro = get_option('wiloke_ingore_intro');
        if ( !empty($aIntro) )
        {
            $aIntro = json_decode($aIntro, true);
            wp_localize_script('introjs', 'WILOKE_IGNORE_INTRO', $aIntro);
        }

        if ( isset($post->post_type) )
        {
            if ( is_file(get_template_directory() . '/admin/source/css/'.$post->post_type.'.css') )
            {
                wp_enqueue_style('wiloke_'.$post->post_type, WILOKE_AD_SOURCE_URI . 'css/'.$post->post_type.'.css');
            }

            if ( is_file(get_template_directory() . '/admin/source/js/'.$post->post_type.'.js') )
            {
                wp_enqueue_script('wiloke_'.$post->post_type, WILOKE_AD_SOURCE_URI . 'js/'.$post->post_type.'.js', array('jquery'), null, true);
            }
        }

        if ( isset($post->post_type) && $post->post_type == 'portfolio' ) {
            wp_enqueue_style('oz-fonts', WilokeFrontPage::fonts_url('Poppins:400,700|Questrial'));
            wp_enqueue_script( 'oz-portfolio-js', WILOKE_AD_SOURCE_URI . 'js/oz-portfolio'.$min.'js', array('jquery', 'cmb-scripts'), null, true );
            wp_localize_script('jquery', 'WILOKE_OZ_THEMEOPTIONS', json_encode(get_option('wiloke_themeoptions')));
        }
    }

    /**
     * Font formats
     * @since 1.0
     */
    public function add_font_setting_menus($buttons){
        array_unshift( $buttons, 'fontselect' ); // Add Font Select
        array_unshift( $buttons, 'fontsizeselect' ); // Add Font Size Select
        return $buttons;
    }
    public function fontsize_formats($initArray){
        $initArray['fontsize_formats'] = "9px 10px 12px 13px 14px 16px 18px 21px 24px 28px 32px 36px 40px 50px 60px 70px 80px 90px 100px 200px";
        return $initArray;
    }

    /**
     * Print scripts into head in the admin area
     * @since 1.0
     */
    public function add_script_to_admin_head()
    {
        global $wiloke;

        ?>
<script type="text/javascript">
/* <![CDATA[ */
            window.WilokeAdminGlobal = {};
            WilokeAdminGlobal.ajaxinfo = {};
            <?php
                if ( isset($wiloke->aConfigs['general']['color_picker']['palette']) && !empty($wiloke->aConfigs['general']['color_picker']['palette']) )
                {
//            JSON_UNESCAPED_UNICODE
                    ?>
WilokeAdminGlobal.ColorPalatte = '<?php echo json_encode($wiloke->aConfigs['general']['color_picker']['palette'], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP ); ?>';
                    <?php
                }else{
                    ?>
WilokeAdminGlobal.ColorPalatte = null;
                    <?php
                }
            ?>
/* ]]> */
</script>
        <?php
    }

    /**
     * Register Menu
     * @since 1.0
     */
    public function register_menu()
    {
        global $wiloke;

        if ( isset($wiloke->aConfigs['frontend']['register_nav_menu']['menu']) )
        {
            foreach ( $wiloke->aConfigs['frontend']['register_nav_menu']['menu'] as $aMenu )
            {
                register_nav_menu($aMenu['key'], $aMenu['name']);
            }

        }

        if ( isset($wiloke->aConfigs['frontend']['register_nav_menus']) )
        {
            register_nav_menu($wiloke->aConfigs['frontend']['register_nav_menus']);
        }
        
    }

    /**
     * Only print photo info keys in photo post type
     * @since 1.0
     */
    public function add_photo_info_keys($null, $post)
    {
        global $wiloke;
        if ( $post->post_type == 'portfolio' )
        {
            return $wiloke->aConfigs['general']['single_portfolio_info'];
        }

        return null;
    }

    /**
     * What sharing posts to be displayed
     * @since 1.0
     */
    public function what_pages_sharing_posts_display($aSharing)
    {
        $aSharing['photo'] = array(
            'name' => esc_html__('Portfolio', 'oz'),
            'std'  => 1
        );

        return $aSharing;
    }

    /**
     * Set Gallery Spacing
     * @since 1.0
     */
    public function set_gallery_spacing() {

        if ( !class_exists( 'Jetpack' ) || !(Jetpack::is_module_active( 'tiled-gallery' ) || Jetpack::is_module_active( 'carousel' )) ) {
            $min = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '.' : '.min.';
            wp_enqueue_script( 'wiloke-gallery-settings', WILOKE_AD_SOURCE_URI . 'js/gallery-settings'.$min.'js', array( 'shortcode', 'media-views' ), null, true );
            wp_enqueue_script( 'wiloke-gallery-settings-editor', WILOKE_AD_SOURCE_URI . '/js/gallery-setting-editor'.$min.'js', array( 'shortcode', 'jquery', 'media-views', 'media-audiovideo' ), false, true  );
        }
    }

    /**
     * Gallery Spacing Settings
     * @since 1.0
     */
    public function gallery_spacing_settings() {

        if ( !class_exists( 'Jetpack' ) || !(Jetpack::is_module_active( 'tiled-gallery' ) || Jetpack::is_module_active( 'carousel' )) ) : ?>

            <script type="text/html" id="tmpl-wil-gallery-settings">

                <label class="setting">
                    <span><?php esc_html_e('Spacing', 'oz'); ?></span>
                    <select data-setting="spacing">
                        <option value="None"><?php esc_html_e('None', 'oz'); ?></option>
                        <option value="0"><?php esc_html_e('0px', 'oz'); ?></option>
                        <option value="5"><?php esc_html_e('5px', 'oz'); ?></option>
                        <option value="10"><?php esc_html_e('10px', 'oz'); ?></option>
                        <option value="15"><?php esc_html_e('15px', 'oz'); ?></option>
                        <option value="20"><?php esc_html_e('20px', 'oz'); ?></option>
                        <option value="25"><?php esc_html_e('25px', 'oz'); ?></option>
                        <option value="30"><?php esc_html_e('30px', 'oz'); ?></option>
                        <option value="35"><?php esc_html_e('35px', 'oz'); ?></option>
                        <option value="40"><?php esc_html_e('40px', 'oz'); ?></option>
                    </select>
                </label>

                <label class="setting">
                    <span>Style</span>
                    <select data-setting="style">
                        <option value="none"><?php esc_html_e('None', 'oz'); ?></option>
                        <option value="square"><?php esc_html_e('Square', 'oz'); ?></option>
                        <option value="circle"><?php esc_html_e('Circle', 'oz'); ?></option>
                        <option value="high"><?php esc_html_e('High', 'oz'); ?></option>
                    </select>
                </label>
                
            </script>

        <?php endif;
    }
}