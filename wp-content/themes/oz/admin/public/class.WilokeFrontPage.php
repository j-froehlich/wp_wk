<?php
/**
 * WO_FrontEnd Class
 *
 * @category Front end
 * @package Wiloke Framework
 * @author Wiloke Team
 * @version 1.0
 */

if ( !defined('ABSPATH') )
{
    exit;
}

class WilokeFrontPage
{
    public function __construct()
    {
        add_action('wp_print_scripts', array($this, 'dequeue_scripts'), 20 );
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'), 99);
        add_action('wp_enqueue_scripts', array($this, 'inline_scripts'), 9999);
    }

    public function inline_scripts(){
        global $wiloke;

        if ( isset($wiloke->aThemeOptions['advanced_js_code']) && !empty($wiloke->aThemeOptions['advanced_js_code']) )
        {
            $wiloke->aThemeOptions['advanced_js_code'] .= '/*WilokeCustomJS*/';
            wp_add_inline_script('jquery', $wiloke->aThemeOptions['advanced_js_code']);
        }
    }

    public function dequeue_scripts()
    {
        wp_dequeue_script('isotope');
        wp_dequeue_script('isotope-css');
        wp_dequeue_script('vc_grid-js-imagesloaded');
    }

    public static function fonts_url($fonts)
    {
        $font_url = '';

        /*
        Translators: If there are characters in your language that are not supported
        by chosen font(s), translate this to 'off'. Do not translate into your own language.
         */
        if ( 'off' !== _x( 'on', 'Google font: on or off', 'oz' ) ) {
            $font_url = add_query_arg( 'family', urlencode( $fonts ), "//fonts.googleapis.com/css" );
        }
        return $font_url;
    }

    /**
     * Enqueue scripts into front end
     */
    public function enqueue_scripts()
    {
        global $wiloke;

        $themeSlug = 'wiloke_'.$wiloke->aConfigs['general']['theme_slug'];

        do_action('wiloke_action_before_enqueue_scripts');

        if ( isset($wiloke->aConfigs['frontend']) && isset($wiloke->aConfigs['frontend']['scripts']) )
        {
            if ( is_singular() )
            {
                wp_enqueue_script('comment-reply');
            }

            $aScripts = $wiloke->aConfigs['frontend']['scripts'];

            foreach ( $aScripts as $key => $aVal )
            {
                $isGoodConditional = true;
                if ( !wp_script_is($key, 'enqueued') )
                {
                    if ( isset($aVal['conditional']) && function_exists($aVal['conditional']) )
                    {
                        if ( call_user_func($aVal['conditional'], $key) )
                        {
                            $isGoodConditional = true;
                        }else{
                            $isGoodConditional = false;
                        }
                    }

                    if ( $isGoodConditional ) {
                        if ( isset($aVal['is_wp_lib']) && $aVal['is_wp_lib'] ){
                            if ( $aVal[0] === 'css' ){
                                wp_enqueue_style($aVal[1]);
                            }else{
                                wp_enqueue_script($aVal[1]);
                            }
                        }elseif (isset($aVal['is_url']) && $aVal['is_url'] === true) {
                            if ($aVal[0] == 'css') {
                                $aVal['required'] = !isset($aVal['required']) ? array() : $aVal['required'];
                                wp_enqueue_style($key, $aVal[1], $aVal['required'], null);
                            } else {
                                $aVal['required'] = !isset($aVal['required']) ? array('jquery') : $aVal['required'];
                                if ( !empty($wiloke->aThemeOptions) && $aVal['is_google_map'] ){
                                    wp_enqueue_script('googlemap', esc_url('//maps.googleapis.com/maps/api/js?key='.$wiloke->aThemeOptions['general_map_api']), array('jquery'), true);
                                }else{
                                    wp_enqueue_script($key, $aVal[1], $aVal['required'], null, true);
                                }
                            }
                        } elseif (isset($aVal['is_googlefont']) && $aVal['is_googlefont'] === true) {
                            wp_enqueue_style($key, self::fonts_url($aVal[0]), array(), null);
                        }elseif ($aVal[0] === 'is_custom_css'){
                            if ( isset($wiloke->aThemeOptions[$aVal[2]]) && !empty($wiloke->aThemeOptions[$aVal[2]]) )
                            {
                                wp_add_inline_style($aVal[1], $wiloke->aThemeOptions[$aVal[2]]);
                            }
                        }elseif ($aVal[0] === 'is_custom_js'){
                            if ( isset($wiloke->aThemeOptions[$aVal[2]]) && !empty($wiloke->aThemeOptions[$aVal[2]]) )
                            {
                                if ( function_exists('wp_add_inline_script') )
                                {
                                    $wiloke->aThemeOptions[$aVal[2]] = '/*WilokeCustomJS*/'.$wiloke->aThemeOptions[$aVal[2]];
                                    wp_add_inline_script($aVal[1], $wiloke->aThemeOptions[$aVal[2]]);
                                }
                            }
                        }else{
                            $concat = '/lib/';

                            if ( isset($aVal['default']) &&  $aVal['default'] === true )
                            {
                                $concat = '/';
                                $aVal[1] = ( !defined('WILOKE_SCRIPT_DEBUG') || !WILOKE_SCRIPT_DEBUG ) && (isset($aVal['is_compress']) && $aVal['is_compress']) ? $aVal[1] . 'min.' : $aVal[1];
                            }

                            if ( $aVal[0] == 'both' || $aVal[0] == 'css' )
                            {
                                $aVal['required'] = !isset($aVal['required']) ? array() : $aVal['required'];
                                wp_enqueue_style($key, WILOKE_THEME_URI . 'css' . $concat . $aVal[1].'css', $aVal['required'], null);
                            }

                            if ( $aVal[0] == 'both' || $aVal[0] == 'js'  )
                            {
                                $aVal['required'] = !isset($aVal['required']) ? array('jquery') : $aVal['required'];
                                wp_enqueue_script($key, WILOKE_THEME_URI . 'js' . $concat . $aVal[1].'js', $aVal['required'], null, true);
                            }
                        }
                    }
                }
            }

            $aDepth = array();
            if ( class_exists('WilokeService') ){
                $aMinifyStatus = get_option('_wiloke_minify');

                if ( isset($aMinifyStatus['css']) && !empty($aMinifyStatus['css']) ){
                    $aDepth[] = 'wiloke_minify_theme_css';
                }
            }

            wp_enqueue_style($themeSlug, get_stylesheet_uri(), $aDepth);
            $generalCustomColor = true;

            ob_start();
            include get_template_directory() . '/css/color/default.css';
            $color = ob_get_clean();

            if ( is_singular('portfolio') ){
                global $post;
                $aPortfolioSettings = Wiloke::getPostMetaCaching($post->ID, 'project_general_settings');

                if ( isset($aPortfolioSettings['theme_color']) && !empty($aPortfolioSettings['theme_color']) ){
                    $color = str_replace('#ba93ef', $aPortfolioSettings['theme_color'], $color);
                    wp_add_inline_style($themeSlug, $color);
                    $generalCustomColor = false;
                }
            }elseif ( is_front_page() && !is_home() ) {
                $postID = get_option('page_on_front');
            }elseif ( function_exists('is_shop') && is_shop() ) {
                $postID = get_option('woocommerce_shop_page_id');
            }elseif (is_page()){
                global $post;
                $postID = $post->ID;
            }

            if ( isset($postID) ){
                $aPageSettings = Wiloke::getPostMetaCaching($postID, 'single_page_settings');
                if ( isset($aPageSettings['theme_color']) && !empty($aPageSettings['theme_color']) ){
                    $color = str_replace('#ba93ef', $aPageSettings['theme_color'], $color);
                    wp_add_inline_style($themeSlug, $color);
                    $generalCustomColor = false;
                }
            }

            if ( $generalCustomColor && isset($wiloke->aThemeOptions['advanced_main_color']) && $wiloke->aThemeOptions['advanced_main_color'] != 'default' )
            {
                if ( is_file(get_template_directory() . '/css/color/'.$wiloke->aThemeOptions['advanced_main_color'].'.css') )
                {
                    wp_enqueue_style('customizemaincolor', get_template_directory_uri() . '/css/color/'.$wiloke->aThemeOptions['advanced_main_color'].'.css' );
                }else{
                    if ( !empty($wiloke->aThemeOptions) && !isset($wiloke->aThemeOptions['advanced_main_color']['rgba']) ) {
                        $customColor = !empty($wiloke->aThemeOptions['advanced_main_color']['rgba']) ? $wiloke->aThemeOptions['advanced_custom_main_color']['rgba'] : $wiloke->aThemeOptions['advanced_custom_main_color']['color'];

                        $color = str_replace('#ba93ef', $customColor, $color);

                        wp_add_inline_style($themeSlug, $color);
                    }
                }
            }
        }

        if ( !empty($wiloke->aThemeOptions) && $wiloke->aThemeOptions['advanced_google_fonts'] == 'general' )
        {
            if ( !empty($wiloke->aThemeOptions['advanced_general_google_fonts']) )
            {
                $aParseFont = explode('css?family=', $wiloke->aThemeOptions['advanced_general_google_fonts']);

                wp_enqueue_style('wiloke_general_google_fonts', self::fonts_url($aParseFont[1]), array(), null);

                ob_start();
                include WILOKE_THEME_DIR . 'css/ggfont-general-custom.css';
                $font = ob_get_clean();
                $font = str_replace('#googlefont_general', $wiloke->aThemeOptions['advanced_general_google_fonts_css_rules'], $font);
                wp_add_inline_style('wiloke_general_google_fonts', $font);
            }
        }

        if ( isset($wiloke->aThemeOptions['advanced_css_code']) && !empty($wiloke->aThemeOptions['advanced_css_code']) ) {
	        wp_add_inline_style($themeSlug, $wiloke->aThemeOptions['advanced_css_code']);
        }
        do_action('wiloke_action_after_enqueue_scripts');
    }
}