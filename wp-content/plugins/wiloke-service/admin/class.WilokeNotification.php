<?php
/**
 * Wiloke Notification
 * Notification for user know what changed and what updated
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
// dadaxxasazzdad11xxdadadad))##1 vedauser veda_demo
if ( !class_exists('WilokeNotification') )
{
    class WilokeNotification{
        public $aTabs = array();

        public function __construct()
        {
            $this->init();
            add_action('admin_enqueue_scripts', array($this, 'enqueueScripts'));
            add_action('admin_notices', array($this, 'announcingHasAnUpdate'));
            add_action('admin_init', array($this, 'reCheckUpdate'));
        }

        public function reCheckUpdate(){
            global $pagenow;
            if ( $pagenow !== 'update-core.php' ){
                return false;
            }

            if ( isset( $_GET['action'] ) && ('do-plugin-upgrade' === $_GET['action']) ){
                delete_transient(WilokeService::$hasUpdateKey);
            }
        }

        public function announcingHasAnUpdate(){
            if ( get_transient(WilokeService::$hasUpdateKey) ){
                $class = 'wiloke-announcement';
                $message = sprintf( __('You have the latest version of %s. Please click on <a href="%s">View Changelog</a> to check what new in this version or click on <a href="%s">Go to Updates area</a> to update it.', 'wiloke-service'), WilokeService::$aThemeInfo['name'], esc_url(admin_url('admin.php?page='.WilokeService::$aSlug['notification'])), esc_url(admin_url('update-core.php')) );

                printf( '<div class="%1$s"><i class="icon fa fa-pied-piper-alt"></i><div class="content"><div class="header">Wiloke Announcement</div><p>%2$s</p></div></div>', esc_attr( $class ), wp_kses($message, array(
                    'a' => array('href'=>array(), 'class'=>array())
                )) );
            }
        }

        public function init(){
            $this->aTabs = array('changelog'=>esc_html__('ChangeLog', 'wiloke-service'));
        }

        public function registerMenu(){
            add_submenu_page( WilokeService::$aSlug['main'], esc_html__('Notifications', 'wiloke-service'), esc_html__('Notifications', 'wiloke-service'), 'edit_theme_options', WilokeService::$aSlug['notification'], array($this, 'readNotifications'));
        }

        public function readNotifications(){
            include plugin_dir_path( dirname(__FILE__) ) . 'admin/html/notification/index.php';
        }

        /**
         * Enqueue Script
         * @since 0.6
         */
        public function enqueueScripts($hooks){
            if ( strpos($hooks, WilokeService::$aSlug['notification']) ){
                wp_enqueue_style('wiloke-notification', plugin_dir_url( dirname(__FILE__) ) . 'source/css/notification.css');
                wp_enqueue_script('wiloke-notification', plugin_dir_url( dirname(__FILE__) ) . 'source/js/notification.js', array('jquery'), null, true);
            }
        }

        /**
         * Notification Title
         * @since 0.6
         */
        public function title($title){
            ?>
            <h4 class="wil-noti-name"><?php esc_html_e(ucfirst($title)); ?></h4>
            <?php
        }

        public function item($item){
            ?>
            <p class="wil-noti-mess"><?php echo $item; ?></p>
            <?php
        }


    }
}