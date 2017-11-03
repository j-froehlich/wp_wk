<?php

add_action('comment_post_redirect', 'wiloke_oz_comment_post_redirect', 10, 2);
function wiloke_oz_comment_post_redirect($location, $comment){
    global $wiloke;
    if ( !empty($wiloke->aThemeOptions) && ( $wiloke->aThemeOptions['advanced_ajax_feature'] == 'enable' ) ){
        WilokePublic::comment_template($comment, array(
            'callback' => array('WilokePublic', 'comment_template'),
            'max_depth'=>3
        ), 3);
        die();
    }

    return $location;
}

require_once get_template_directory() . '/admin/run.php';

/**
 * Add theme support
 * @since 1.0
 */
function wiloke_oz_add_theme_support()
{
    add_theme_support('html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ));
    add_theme_support('title-tag');
    add_theme_support('widgets');
    add_theme_support('woocommerce');
    add_theme_support('automatic-feed-links');
    add_theme_support('menus');
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');
    add_theme_support('post-formats', array( 'gallery', 'quote', 'video', 'audio' ));
    add_image_size('wiloke_460_460', 460, null, true);
    add_image_size('wiloke_20_auto', 20, null, true);
    add_image_size('wiloke_925_925', 925, null, true);
    add_image_size('wiloke_460_925', 460, 925, true);
    add_image_size('wiloke_925_460', 925, 460, true);
    add_image_size('wiloke_1600_auto', 1600, null, true);

    update_option( 'medium_size_w', 760 );
    update_option( 'medium_size_h', 430 );

    $GLOBALS['content_width'] = apply_filters('wiloke_filter_content_width', 1200);



    load_theme_textdomain( 'oz', get_template_directory() . '/languages' );
}

add_action('after_setup_theme', 'wiloke_oz_add_theme_support');

/**
 * Registers an editor stylesheet for the current theme.
 *
 * @global WP_Post $post Global post object.
 */
function wiloke_oz_theme_add_editor_styles() {
    global $post;
 
    $my_post_type = 'portfolio';
 
    // New post (init hook).
    if ( false !== stristr( $_SERVER['REQUEST_URI'], 'post-new.php' )
            && ( isset( $_GET['post_type'] ) === true && $my_post_type == $_GET['post_type'] )
    ) {
        add_editor_style( get_stylesheet_directory_uri() . '/admin/source/css/portfolio-editor-style.css' );
    }
 
    // Edit post (pre_get_posts hook).
    if ( stristr( $_SERVER['REQUEST_URI'], 'post.php' ) !== false
            && is_object( $post )
            && $my_post_type == get_post_type( $post->ID )
    ) {
        add_editor_style( get_stylesheet_directory_uri() . '/admin/source/css/portfolio-editor-style.css' );
    }
}
add_action( 'init', 'wiloke_oz_theme_add_editor_styles' );
add_action( 'pre_get_posts', 'wiloke_oz_theme_add_editor_styles' );

if ( !function_exists('wiloke_oz_query_vars_filter') ) {
    add_filter( 'query_vars', 'wiloke_oz_query_vars_filter' );
    function wiloke_oz_query_vars_filter($vars){
        $vars[] = 'wiloke_portfolio';
        return $vars;
    }
}

/**
 * You can't actually add meta boxes after the title by default in WP so
 * we're being cheeky. We've registered our own meta box position
 * `after_title` onto which we've regiestered our new meta boxes and
 * are now calling them in the `edit_form_after_title` hook which is run
 * after the post tile box is displayed.
 *
 * @return null
 */
function wiloke_oz_run_after_title_meta_boxes() {
    global $post, $wp_meta_boxes;
    # Output the `below_title` meta boxes:
    do_meta_boxes( get_current_screen(), 'after_title', $post );
}
add_action( 'edit_form_after_title', 'wiloke_oz_run_after_title_meta_boxes' );

add_filter( 'style_loader_src',  'wiloke_oz_remove_ver_css_js', 9999, 2 );
add_filter( 'script_loader_src', 'wiloke_oz_remove_ver_css_js', 9999, 2 );

function wiloke_oz_remove_ver_css_js( $src, $handle )
{
    if ( is_admin() ){
        return $src;
    }

    $handles_with_version = array('style'); // <-- Adjust to your needs!
    if ( strpos( $src, 'ver=' ) && ! in_array( $handle, $handles_with_version, true ) ){
        $src = remove_query_arg( 'ver', $src );
    }
    return $src;
}

add_filter('vc_param_animation_style_list', 'wiloke_oz_add_animation_to_vc_shortcode', 10, 1);
function wiloke_oz_add_animation_to_vc_shortcode($aAnimations){
    $wiloke = array(
        array(
            'values' => array(
                esc_html__( 'None', 'oz' ) => 'none',
            ),
        ),
        array(
            'label' => esc_html__( 'Wiloke', 'oz' ),
            'values' => array(
                esc_html__( 'Theme animation', 'oz' ) => 'wil-animation',
            )
        )
    );

    return array_merge($wiloke, array_shift($aAnimations));
}

function wiloke_oz_post_classes($classes) {
    global $wiloke, $post;
    if ( is_singular('portfolio') ) {
        $aPostMeta  = Wiloke::getPostMetaCaching($post->ID, 'project_general_settings');
        if ( isset($aPostMeta['project_skin']) && !empty($aPostMeta['project_skin']) && ($aPostMeta['project_skin'] != 'inherit') ) {
            $classes[] = $aPostMeta['project_skin'];
        }elseif ( !empty($wiloke->aThemeOptions['project_skin']) ){
            $classes[] = $wiloke->aThemeOptions['project_skin'];
        }
    }else{
        if ( !empty($post) ){
            $aPageSettings  = Wiloke::getPostMetaCaching($post->ID, 'single_page_settings');
            if ( isset($aPageSettings['theme_skin']) && !empty($aPageSettings['theme_skin']) ) {
                $classes[] = $aPageSettings['theme_skin'];
            }
        }
    }
 
    return $classes;
}
add_filter('body_class', 'wiloke_oz_post_classes', 10, 1);

add_action('init', 'wiloke_oz_add_lazy_load_option_to_single_image');
function wiloke_oz_add_lazy_load_option_to_single_image(){
    if ( function_exists('vc_add_param') ){
        vc_add_param('vc_single_image', array(
            'type'          => 'dropdown',
            'heading'       => esc_html__('Lazy Load', 'oz'),
            'param_name'    => 'is_lazy_load',
            'value'         => array(
                esc_html__('Enable', 'oz')  => 'enable',
                esc_html__('Disable', 'oz') => 'disable'
            ),
            'std'   => 'enable'
        )); 
    }
}

add_action('revslider_fe_javascript_output', 'oz_revslider_fe_javascript_output', 10, 2);
function oz_revslider_fe_javascript_output($slider, $sliderHtmlID){
    ?>
    WILOKE_GLOBAL.currentRevSliderID = 'revapi<?php echo esc_attr($slider->getID()); ?>';
    <?php
}