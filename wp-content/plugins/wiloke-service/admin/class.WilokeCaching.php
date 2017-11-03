<?php
/**
 * Wiloke Caching
 * Speed up website
 *
 * @link        https://wiloke.com
 * @since       0.5
 * @package     WilokeService
 * @subpackage  WilokeService/admin
 * @author      Wiloke
 */

if ( !defined('ABSPATH') )
{
    wp_die( esc_html__('You do not permission to access to this page', 'wiloke-service') );
}

if ( !class_exists('WilokeCaching') ) {
    class WilokeCaching
    {
        /**
         * Caching Settings
         * @since 0.5
         */
        public static $aOptions;

        /**
         * Registering Caching Sub-menu
         * @since 0.5
         */
        public function register_submenu()
        {
            add_submenu_page( WilokeService::$aSlug['main'], esc_html__('Caching', 'wiloke-service'), esc_html__('Caching', 'wiloke-service'), 'edit_theme_options', WilokeService::$aSlug['caching'], array($this, 'caching_area'));
        }

        /**
         * Caching Area
         * @since 0.5
         */
        public function caching_area(){
            include plugin_dir_path(__FILE__) . 'html/caching/settings.php';
        }

        /**
         * Saving Data
         * @since 0.5
         */
        public function save_caching_settings(){
            $aDefault = array(
                'redis'=>array(
                    'caching_interval' => 86400
                )
            );

            if ( isset($_POST['wiloke_caching']) && wp_verify_nonce($_POST['wiloke_redis_caching_nonce'], 'wiloke_redis_caching_action') ){
                update_option('_wiloke_service_caching', serialize($_POST['wiloke_caching']));
                self::$aOptions = $_POST['wiloke_caching'];
            }else{
                self::$aOptions = get_option('_wiloke_service_caching');
                if ( self::$aOptions ) {
                    self::$aOptions = unserialize(self::$aOptions);
                }
            }

            unset($_POST['wiloke_caching']);

            self::$aOptions = wp_parse_args(self::$aOptions, $aDefault);
        }

        /**
         * Purge Cache
         * @since 1.0
         */
        public function purge_cache(){
            if ( isset($_POST['wiloke_redis_caching_nonce']) && wp_verify_nonce($_POST['wiloke_redis_caching_nonce'], 'wiloke_redis_caching_action') ){

                switch ($_GET['target']){
                    case 'redis':
                        if ( !class_exists('Wiloke') ){
                            wp_die( esc_html__('It seems this theme haven\'t made by Wiloke', 'wiloke-service') );
                        }else{
                            Wiloke::$wilokePredis->flushAll();
                            Wiloke::setTemporarySession('wiloke_service_purged_redis', esc_html__('All redis caches have been purged!', 'wiloke-service'));
                        }
                        break;
                }

                unset($_POST['wiloke_redis_caching_nonce']);
            }
        }
    }
}