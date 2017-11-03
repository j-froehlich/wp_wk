<?php
/**
 * WilokeHead Class
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

class WilokeHead
{
    public function __construct()
    {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
    }

    public function enqueue_scripts()
    {
        global $wiloke;

        if ( !isset($wiloke->aThemeOptions['advanced_ajax_feature']) ){
            $wiloke->aThemeOptions['advanced_ajax_feature'] = 'disable';
        }

        wp_enqueue_style('wiloke-alert', Wiloke::$public_url . 'source/css/alert.css');
        global $post;
        ob_start();
        ?>
        var wilokeVisited = 0;
        if ( typeof WILOKE_GLOBAL === 'undefined' )
        {
            window.WILOKE_GLOBAL                = {};
            WILOKE_GLOBAL.aListOfEvents         = {},
            WILOKE_GLOBAL.currentRevSliderID    = null,
            WILOKE_GLOBAL.homeurl               = '<?php echo esc_url(home_url('/')); ?>';
            WILOKE_GLOBAL.wiloke_nonce          = '<?php echo esc_js(wp_create_nonce('wiloke-nonce')); ?>';
            WILOKE_GLOBAL.work_in_progress      = '<div class="loadder-more text-center wiloke-work-in-progress"><span class="round"><span></span><span></span><span></span></span></div>';
            WILOKE_GLOBAL.ajaxurl               = '<?php echo esc_url(admin_url("admin-ajax.php")); ?>';
            WILOKE_GLOBAL.postID                = <?php echo !empty($post->ID) ? esc_js($post->ID) : -1; ?>;
            WILOKE_GLOBAL.post_type             = '<?php echo !empty($post->post_type) ? esc_js($post->post_type) : -1; ?>';
            WILOKE_GLOBAL.portfolio_data        = {};
            WILOKE_GLOBAL.portfolio_loaded_cats = {};
            WILOKE_GLOBAL.posts__not_in         = {};
            WILOKE_GLOBAL.woocommerce           = {
                cartEmpty: '<?php printf(wp_kses(__('Your cart is currently empty. <a href="%s">Return To Shop</a>', 'oz'), array('a'=>array('href'=>array()))), esc_url(get_permalink(get_option('woocommerce_shop_page_id')))); ?>'
            };
            WILOKE_GLOBAL.popup_template = '<div class="wil-work-popup__image">{{content}}</div><div class="wil-work-popup__caption"><div class="wil-work-popup__caption-text"><h2 class="wil-work-item__title"><a href="{{link}}" class="wiloke-link-inside-magnific">{{title}}</a></h2><div class="wil-work-item__cat">{{category}}</div></div><a href="{{link}}" class="wil-btn wiloke-link-inside-magnific"><?php esc_html_e('Work detail', 'oz'); ?></a></div>';
            WILOKE_GLOBAL.is_debug = '<?php echo defined('WP_DEBUG') && WP_DEBUG ? 'true' : 'false'; ?>',
            WILOKE_GLOBAL.siteCache = {};
            WILOKE_GLOBAL.siteHandling = new Array();
            WILOKE_GLOBAL.hasHashChange = true;
            WILOKE_GLOBAL.comment = {
                status: {
                    submit: '<?php esc_html_e('Submitting...', 'oz'); ?>'
                },
                error: {
                    empty: '<?php esc_html_e("Please fill all required fields", "oz") ?>',
                    conflict: '<?php esc_html_e("Duplicate comment detected; it looks as though you’ve already said that!", "oz"); ?>'
                }
            },
            WILOKE_GLOBAL.toggleAjaxFeature = '<?php echo esc_attr($wiloke->aThemeOptions['advanced_ajax_feature']); ?>'
        }
        <?php
        $content = ob_get_contents();
        ob_end_clean();
        wp_add_inline_script('jquery-migrate', $content);
    }
}