<?php
/**
 * Plugin Name: Wiloke Sharing Post
 * Plugin URI: http://wiloke.net
 * Description: Wiloke Sharing Post
 * Version: 1.0
 * Author: Wiloke
 * Text Domain: wiloke
 * Author URI: http://wiloke.net
 */


if (!defined('ABSPATH')) {
    die("You don't have sufficient permission to access this page");
}

if (!class_exists('WilokeSharingPost')) {
    class WilokeSharingPost {

        public $wiloke_sharing_defaults = array(
            'attributes' => array(
                'facebook' => array(
                    'active' => true,
                    'name_class' => 'fa fa-facebook',
                    'title' => 'Facebook'
                ),
                'twitter' => array(
                    'active' => true,
                    'name_class' => 'fa fa-twitter',
                    'title' => 'Twitter'
                ),
                'googleplus' => array(
                    'active' => true,
                    'name_class' => 'fa fa-google-plus',
                    'title' => 'Google Plus'
                ),
                'digg' => array(
                    'active' => true,
                    'name_class' => 'fa fa-digg',
                    'title' => 'Digg'
                ),
                'reddit' => array(
                    'active' => true,
                    'name_class' => 'fa fa-reddit',
                    'title' => 'Reddit'
                ),
                'linkedin' => array(
                    'active' => true,
                    'name_class' => 'fa fa-linkedin',
                    'title' => 'LinkedIn'
                ),
                'stumbleupon' => array(
                    'active' => true,
                    'name_class' => 'fa fa-stumbleupon',
                    'title' => 'StumbleUpon'
                ),
                'tumblr' => array(
                    'active' => true,
                    'name_class' => 'fa fa-tumblr',
                    'title' => 'Tumblr'
                ),
                'pinterest' => array(
                    'active' => true,
                    'name_class' => 'fa fa-pinterest',
                    'title' => 'Pinterest '
                ),
                'email' => array(
                    'active' => true,
                    'name_class' => 'fa fa-envelope',
                    'title' => 'Email '
                )
            ),
            'show_on' => array(
                'home' => true,
                'pages' => true,
                'posts' => true,
                'archive' => true,
            ),
            'title' => ''
        );

        public $aShowOn = array(
            'home'     => 'Home',
            'page'     => 'Pages',
            'post'     => 'Posts',
            'archive'  => 'Archives'
        );

        public $wiloke_script = array(
            'facebook' => '<div id="fb-root"></div>
                            <script>(function(d, s, id) {
                          var js, fjs = d.getElementsByTagName(s)[0];
                          if (d.getElementById(id)) return;
                          js = d.createElement(s); js.id = id;
                          js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.0";
                          fjs.parentNode.insertBefore(js, fjs);
                        }(document, "script", "facebook-jssdk"));</script>',
            'twitter' => '<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?"http":"https";if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document, "script", "twitter-wjs");</script>',
            'googleplus' => '<script type="text/javascript">
									  				(function() {
									    				var po = document.createElement("script"); po.type = "text/javascript"; po.async = true;
									    				po.src = "https://apis.google.com/js/platform.js";
									    				var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(po, s);
									  				})();
													</script>',
            'digg' => '',
            'reddit' => '',
            'linkedin' => '<script src="//platform.linkedin.com/in.js" type="text/javascript">
										  				lang: en_US
													</script>',
            'stumbleupon' => '<script type="text/javascript">
													  (function() {
													    var li = document.createElement("script"); li.type = "text/javascript"; li.async = true;
													    li.src = ("https:" == document.location.protocol ? "https:" : "http:") + "//platform.stumbleupon.com/1/widgets.js";
													    var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(li, s);
													  })();
													</script>',
            'tumblr' => '',
            'pinterest' => ''
        );

        function wiloke_markup () {

            global $post;
            $media = "";
            if ( has_post_thumbnail($post->ID) )
            {
                $media = '&amp;media='.urlencode(wp_get_attachment_url( get_post_thumbnail_id($post->ID) ));
            }

            return array(
                'facebook' => array(
                    'before' => '<a class="facebook" href="http://www.facebook.com/sharer.php?u='.urlencode(get_permalink($post->ID)).'&amp;t='.urlencode(get_the_title($post->ID)).'" target="_blank" title="'.esc_html__('Share to Facebook', 'wiloke').'">',
                    'after' => '</a>'
                ),
                'twitter' => array(
                    'before' => '<a class="twitter" href="https://twitter.com/intent/tweet?text='.urlencode(get_the_title($post->ID)) . '-'.urlencode(get_permalink($post->ID)).'&amp;source=webclient" target="_blank"  title="'.esc_html__('Share to Twitter', 'wiloke').'">',
                    'after' => '</a>'
                ),
                'googleplus' => array(
                    'before' => '<a class="googleplus" href="http://google.com/bookmarks/mark?op=edit&amp;bkmk='.urlencode(get_permalink($post->ID)).'&amp;title='.urlencode(get_the_title($post->ID)).'" target="_blank"  title="'.esc_html__('Share to Google Plus', 'wiloke').'">',
                    'after' => '</a>'
                ),
                'digg' => array(
                    'before' => '<a class="digg" href="http://www.digg.com/submit?url=' .urlencode(get_permalink($post->ID)). '&amp;title='.urlencode(get_the_title($post->ID)).'" target="_blank"  title="Share to Digg">',
                    'after' => '</a>'
                ),
                'reddit' => array(
                    'before' => '<a class="reddit" href="http://reddit.com/submit?url='.urlencode(get_permalink($post->ID)). '&amp;title='.urlencode(get_the_title($post->ID)).'" target="_blank" title="'.esc_html__('Share to Reddit', 'wiloke').'">',
                    'after' => '</a>'
                ),
                'linkedin' => array(
                    'before' => '<a class="linkedin" href="http://www.linkedin.com/shareArticle?mini=true&amp;url='.urlencode(get_permalink($post->ID)).'&amp;title='.urlencode(get_the_title($post->ID)).'" target="_blank" title="'.esc_html__('Share to LinkedIn', 'wiloke').'">',
                    'after' => '</a>'
                ),
                'stumbleupon' => array(
                    'before' => '<a class="stumbleupon" href="http://www.stumbleupon.com/submit?url='.urlencode(get_permalink($post->ID)).'&amp;title='.urlencode(get_the_title($post->ID)).'" target="_blank" title="'.esc_html__('Share to StumbleUpon', 'wiloke').'">',
                    'after' => '</a>'
                ),
                'tumblr' => array(
                    'before' => '<a class="tumblr" href="http://www.tumblr.com/share/link?url=' . urlencode(get_permalink()) . '&amp;name=' . urlencode(get_the_title()) . '" target="_blank" title="'.__('Share to Tumblr', 'wiloke').'">',
                    'after' => '</a>'
                ),
                'pinterest' => array(
                    'before' => '<a class="pinterest" href="https://pinterest.com/pin/create/button/?url='.urlencode(get_permalink($post->ID)).$media.'&amp;description='.urlencode(get_the_title($post->ID)).'" target="_blank" data-pin-do="buttonBookmark" title="'.esc_html__('Share to Pinterest', 'wiloke').'">',
                    'after' => '</a>'
                ),
                'email' => array(
                    'before' => '<a class="mail" href="mailto:?Subject=' . str_replace(' ', '%20', get_the_title($post->ID)) . '&Body=' . str_replace(' ', '%20', 'Here is the link to the article: ' .get_permalink($post->ID)) . '" title="'.esc_html__('Email this article', 'wiloke').'">',
                    'after' => '</a>'
                )
            );
        }

        public function __construct ()
        {
            // Add actions
            add_action('init', array($this, 'init'));
            add_action('admin_menu', array($this, 'wiloke_register_submenu'));
            add_action('admin_init', array($this, 'wiloke_register_settings'));
            add_action('wp_enqueue_scripts', array($this, 'wiloke_load_styles_scripts'));
            add_action('admin_enqueue_scripts', array($this, 'wiloke_admin_styles_scripts'));

            // After active plugin
            register_activation_hook( __FILE__, array($this, 'wiloke_after_active_plugin' ));

            // Add ShortCode
            add_shortcode('wiloke_sharing_post', array($this, 'wiloke_sharing_markup'));
        }

        public function init(){
            $this->aShowOn = apply_filters('wiloke_sharing_post_show_on', $this->aShowOn);
        }

        public function wiloke_load_styles_scripts() {
            wp_enqueue_style( 'wiloke-sharing-fontawesome', plugins_url('css/font-awesome.min.css',__FILE__) );
        }

        public function wiloke_admin_styles_scripts () {

            wp_enqueue_style( 'wiloke-sharing-admin-fontawesome', plugins_url('css/font-awesome.min.css',__FILE__) );
            wp_enqueue_style( 'wiloke-sharing-admin-main', plugins_url('css/wiloke-sharing-admin.css',__FILE__) );
            wp_enqueue_script( 'wiloke-sharing-admin-main', plugins_url('js/wiloke-sharing-admin.js',__FILE__) );
        }

        public function wiloke_register_settings() {
            register_setting( 'wiloke_share_options', 'wiloke_share_options' );
        }

        public function wiloke_register_submenu () {
            add_submenu_page( 'options-general.php', 'Wiloke Sharing Settings', 'Wiloke Sharing Settings', 'activate_plugins', 'wiloke-sharing-settings', array( $this, 'wiloke_sharing_settings_submenu_page' ) );
        }

        public function wiloke_after_active_plugin() {
            $wos_options = get_option('wiloke_share_options');

            if (!$wos_options || empty($wos_options)) {
                $wos_options = $this->wiloke_sharing_defaults;
            }
            else {
                $wos_options = array_merge($this->wiloke_sharing_defaults, $wos_options);
            }
            update_option('wiloke_share_options', $wos_options);
        }

        public function wiloke_sharing_markup() {

            $wos_options = get_option('wiloke_share_options');

            if (is_home() && !in_array('home', (array)$wos_options['show_on']) && in_array('home', $this->aShowOn))
            {
                return '';
            }

            if (is_single() && !in_array('post', (array)$wos_options['show_on']) && in_array('post', $this->aShowOn))
            {
                return '';
            }

            if (is_page() && !in_array('page', (array)$wos_options['show_on']) && in_array('page', $this->aShowOn) )
            {
                return '';
            }

            if (is_archive() && !in_array('archive', (array)$wos_options['show_on']) && in_array('archive', $this->aShowOn) )
            {
                return '';
            }

            $conditional = apply_filters('wiloke_sharing_posts', true, $wos_options);

            if (!$conditional){
                return;
            }

            do_action('wiloke_hook_wsp_before_render_social', $wos_options['show_on']);

            $markup_socials = $this->wiloke_markup();
            $cssWrapper = apply_filters('wiloke_sharing_post_css_class', 'wiloke-sharing-post-social');
            ?>
            <div class="<?php echo esc_attr($cssWrapper); ?>">
                <?php echo apply_filters('wiloke_sharing_post_title', $wos_options['title']); ?>
                <?php
                    foreach($wos_options['attributes'] as $key => $attribute) :
                        if (isset($attribute['active'])) {
                            $social = $markup_socials[$key];

                            if ( has_action('wiloke_sharing_post_filter_social_item') )
                            {
                                do_action('wiloke_sharing_post_filter_social_item', $social['before'], $attribute['name_class'], $attribute['title'], $social['after']);
                            }else{
                                echo $social['before'];
                                echo sprintf('<i class="%1s" title="%2s">%3s</i>', esc_attr($attribute['name_class']), esc_attr($attribute['title']), $attribute['title']);
                                echo $social['after'];
                            }
                        }
                    endforeach;
                ?>
            </div>
            <?php

        }

        public function wiloke_sharing_settings_submenu_page () {
            ?>
            <div class="wrap">
                <h2>Settings</h2>

                <form action="options.php" method="POST">
                <?php settings_fields('wiloke_share_options'); ?>
                <?php $wos_options = get_option('wiloke_share_options'); ?>
                    <table class="form-table wiloke-settings-table">
                        <tr>
                            <th><label class="wiloke-show-on-title"><?php esc_html_e('Title', 'wiloke'); ?></label></th>
                            <td>
                                <input type="text" name="wiloke_share_options[title]" value="<?php echo esc_attr($wos_options['title']); ?>" />
                            </td>
                        </tr>
                        <tr>
                            <th><label for="wiloke-show-on"><?php esc_html_e('Show On the below pages: ', 'wiloke'); ?></label></th>
                            <td>
                                <?php do_action('wiloke_hook_wsp_before_render_show_on', $wos_options); ?>
                                <input type="hidden" name="wiloke_share_options[show_on]" value="" >
                                <?php
                                foreach ( $this->aShowOn as $key => $val ) :
                                ?>
                                    <label class="wiloke-share-label">
                                        <input type="checkbox" name="wiloke_share_options[show_on][<?php echo esc_attr($key); ?>]" value="<?php echo esc_attr($key); ?>" <?php checked(in_array($key,(array)$wos_options['show_on']), 1); ?> />
                                        <?php echo esc_html($val); ?>
                                    </label>
                                <?php
                                endforeach;
                                do_action('wiloke_hook_wsp_after_render_show_on', $wos_options);
                                ?>
                            </td>
                        </tr>

                        <?php foreach($wos_options['attributes'] as $key => $attribute) : ?>
                            <tr>
                                <th><label for="wiloke-show-on"><?php echo esc_html($attribute['title']); ?></label></th>
                                <td>
                                    <input name="wiloke_share_options[attributes][<?php echo esc_attr($key); ?>][title]" type="hidden" value="<?php echo esc_attr($attribute['title']); ?>"/>
                                    <input type="checkbox" name="wiloke_share_options[attributes][<?php echo esc_attr($key); ?>][active]" value="active" <?php checked(in_array('active',(array)$attribute), 1); ?>>
                                    <input class="" type="text" name="wiloke_share_options[attributes][<?php echo esc_attr($key); ?>][name_class]" value="<?php echo esc_attr($attribute['name_class']); ?>"/>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                <?php submit_button(); ?>
                </form>
            </div>
        <?php
        }
    }
}
new WilokeSharingPost;

add_action( 'plugins_loaded', 'pi_sp_load_textdomain' );
/**
 * Load plugin textdomain.
 *
 * @since 1.0.0
 */
function pi_sp_load_textdomain() {
  load_plugin_textdomain( 'wiloke', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' ); 
}