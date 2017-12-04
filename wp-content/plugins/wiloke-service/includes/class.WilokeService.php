<?php
/**
 * The class define the core of plugin
 *
 * @link        https://wiloke.com
 * @since       0.2
 * @author      Wiloke
 * @package     WilokeService
 * @subpackage  WilokeService/includes
 */

if ( !defined('ABSPATH') )
{
    wp_die( esc_html__('You do not permission to access to this page', 'wiloke') );
}

class WilokeService
{
    /**
     *  Plugin Instance
     *
     * @since 1.0
     * @access protected
     */
    protected static $_instance = null;

    /**
     * Loader's instance
     *
     * @var Object
     * @since 1.0
     * @access protected
     */
    protected $_oLoader;

    /**
     * An instance of WilokeTwitterLoginPublic class
     *
     * @var Object
     * @since 1.0
     * @access protected
     */
    protected $_oPublicInstance;

    /**
     * An instance of WilokeTwitterLoginAdmin class
     *
     * @var Object
     * @since 1.0
     * @access protected
     */
    protected $_oAdminInstance;

    /**
     * An instance of WilokeUpdate class
     *
     * @var Object
     * @since 1.0
     * @access protected
     */
    protected $_WilokeUpdate;
    public static $WilokeUpdate;

    /**
     * An instance of WilokeUpdate class
     *
     * @var Object
     * @since 1.0
     * @access protected
     */
    protected $_WilokeAmazonService;


    /**
     * An instance of WilokeComingsoon class
     *
     * @var Object
     * @since 1.0
     * @access protected
     */
    protected $_WilokeCS;

    /**
     * An instance of WilokeImport class
     *
     * @var Object
     * @since 1.0
     * @access protected
     */
    protected $_WilokeImport;

    /**
     * An instance of WilokeCaching class
     *
     * @var Object
     * @since 1.0
     * @access protected
     */
    protected $_WilokeCaching;

    /**
     * An instance of WilokeMinifyScripts class
     *
     * @var Object
     * @since 1.0
     * @access protected
     */
    protected $_WilokeMinifyScripts;
    public $WilokeMinifyScripts;

    /**
     * An instance of WilokeNotification
     * @since 0.6
     */
    public $WilokeNotification;
    public static $hasUpdateKey='_wiloke_has_update';

    /**
     * An instance of WilokeImport class
     *
     * @var Object
     * @since 1.0
     * @access protected
     */
    public static $wilokeServiceError = 'wiloke_serivce_error';

    /**
     * An instance of WilokeTwitterLoginShortcodes class
     *
     * @var Object
     * @since 1.0
     * @access protected
     */
    protected $_oShortcode;

    /** 
     * Saving the current theme info in this store
     * @since 1.0
     */
    public static $currentThemeInfo = '_wiloke_current_theme_info';

    /**
     * Caching Theme Info in this variable
     * @since 1.0
     */
    public static  $aThemeInfo   = array();

    /**
     * Wiloke API KEY
     * @since 1.0
     */
    public static $wilokeApiKey = '';

    /**
     * Wiloke Service's slug
     *
     * @since 0.2
     * @static
     */
    public static $aSlug = array('main'=>'wiloke-service', 'notification' => 'wiloke-notifications', 'update'=>'wiloke-update', 'comingsoon'=>'wiloke-comingsoon', 'import'=>'wiloke-import', 'amazon-service'=>'wiloke-amazon-service', 'minify'=>'minify-scripts');
    // 'caching'=>'wiloke-caching'

    /**
     * S3
     * @static
     */
    public static $awsUrl = 'https://s3.amazonaws.com/wiloke.net/';

    /**
     * S3
     * @static
     */
    public static $nonceKey = 'wiloke_service_nonce';

    /**
     * Temporary Disable Request Key
     * @since 1.0
     * @static
     */
    public static $temporaryDisableRequest = 'wiloke_temporary_disable_request';

    /**
     * Plugin URL
     * @since 1.0
     */
    public static $wilokeServiceURI;


    /**
     * Get Access Token
     * @since 1.0
     */
    public $accessToken;

    /**
     * Cloning is forbidden
     *
     * @since 1.0
     */
    public function __clone()
    {
        _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'wiloke' ), '2.1'  );
    }

    /**
     * Wake up is forbidden
     *
     * @since 1.0
     */
    public function __wakeup()
    {
        _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'wiloke' ), '2.1'  );
    }

    /**
     * Plugin Version
     * @since 1.0
     * @static
     */
    public static $version = '0.9.9';


    public static $emailContact = 'sale@wiloke.com';

    public static $oThemeInfo = null;

    /**
     * Run as soon as plugin init. The function load all front end and backend files, functions.
     */
    public function __construct()
    {
        self::$wilokeServiceURI = plugin_dir_url(dirname(__FILE__));

        add_action('after_switch_theme', array('WilokeService', 'wiloke_update_theme_info'), 10);
        add_action('init', array('WilokeService', 'get_theme_info'), 10);

        $this->admin_init();
        $this->load_modules();
        $this->load_admin();
        $this->load_public();

        add_action('admin_menu', array($this, 'register_menu'));
        add_action('admin_enqueue_scripts', array($this, 'register_scripts'));
    }

    public static function getThemeVersion(){
		if ( !empty(self::$oThemeInfo) ){
			return self::$oThemeInfo;
		}

	    self::$oThemeInfo = wp_get_theme(get_template());

		return self::$oThemeInfo;
    }

    public function admin_init(){
        $this->accessToken = get_option('_wiloke_service_client_token');
    }

    /**
     * Register Menu For The Plugin
     * @since 1.0
     */
    public function register_menu(){
        add_menu_page( esc_html__('Wiloke Service', 'wiloke-service'), esc_html__('Wiloke Service', 'wiloke-service'), 'edit_theme_options', self::$aSlug['main'], array($this, 'say_something_about_me'), 'dashicons-thumbs-up' );
    }

    /**
     * Wiloke Service Area
     * @since 1.0
     */
    public function say_something_about_me(){
        if ( isset($_POST['wiloke-update-action']) && !empty($_POST['wiloke-update-action']) )
        {
            if ( wp_verify_nonce($_POST['wiloke-update-action'], 'wiloke-update-nonce') )
            {
                if ( isset($_POST['wiloke_update']['secret_token']) )
                {
                    $secretToken = filter_var($_POST['wiloke_update']['secret_token'], FILTER_SANITIZE_STRING);
                    update_option('_wiloke_service_client_token', $secretToken);
                    update_site_option('_wiloke_service_client_token', $secretToken);
                    delete_transient(self::$temporaryDisableRequest);
                    delete_option(self::$wilokeServiceError);
                    $this->_WilokeUpdate->refreshUpdate();
                    self::$wilokeApiKey = $secretToken;
                    self::get_theme_info(true);
                    self::request($this->_WilokeUpdate->wilokeAPIURL);
                    unset($_POST['wiloke-update-action']);
                }
            }
        }else{
            $secretToken = $this->accessToken;
        }

        include plugin_dir_path( dirname(__FILE__) ) . 'admin/html/introduction.php';
    }

    public function register_scripts($hook){
        if ( strpos($hook, WilokeService::$aSlug['main']) !== false )
        {
            wp_enqueue_style('semantic-ui', plugin_dir_url( dirname(__FILE__) ) . 'assets/semantic-ui/form.min.css');
            wp_enqueue_style('semantic-ui', plugin_dir_url( dirname(__FILE__) ) . 'assets/semantic-ui/semantic.min.css');
        }
        wp_enqueue_media();
        wp_enqueue_script('wiloke-service', plugin_dir_url( dirname(__FILE__) ) . 'source/js/script.js', array('jquery'));
        wp_add_inline_script('jquery-migrate', 'var WILOKE_SERIVCE_NONCE="'.wp_create_nonce(self::$nonceKey).'";');
        wp_add_inline_script('jquery-migrate', 'var WILOKE_SERVICE_S3="'.esc_url(self::$awsUrl).'";');
        wp_enqueue_style('wiloke-service-global', plugin_dir_url( dirname(__FILE__) ) . 'source/css/global.css');
    }

    /**
     * Autoloader
     * @since 1.0
     */
    public static function wiloke_service_autoloader($className){
        if ( is_file(plugin_dir_path( dirname(__FILE__) ) . 'includes/class.'.$className.'.php') ) {
            include plugin_dir_path( dirname(__FILE__) ) . 'includes/class.'.$className.'.php';
        }elseif (is_file(plugin_dir_path( dirname(__FILE__) ) . 'admin/class.'.$className.'.php')) {
            include plugin_dir_path( dirname(__FILE__) ) . 'admin/class.'.$className.'.php';
        }
    }

    /**
     * The function loads all modules of the plugin
     *
     * + Wiloke_Loader              : Register all hooks, filters of the plugin
     * + WilokeTwitterLoginAdmin    : What related to admin should ask him
     * + WilokeTwitterLoginPublic   :  What related to front-end should ask him
     *
     * @since 1.0
     */
    public function load_modules()
    {
        $this->_oLoader      = new WilokeLoader();
    }

    /**
     * Loads admin modules
     * @since 1.0
     */
    public function load_admin()
    {
        $this->_WilokeUpdate = new WilokeUpdate();
        self::$WilokeUpdate = $this->_WilokeUpdate;
	    if ( !$this->accessToken ){
		    return;
	    }

        if ( is_admin() ){
            if ( !defined('WILOKE_DISABLE_REQUEST') || !WILOKE_DISABLE_REQUEST ) {
            	$this->_oLoader->add_action('admin_init', $this->_WilokeUpdate, 'insideUpdateCorePage');
                $this->_oLoader->add_action('http_request_args', $this->_WilokeUpdate, 'update_check', 5, 2);
                // $this->_oLoader->add_action('admin_menu', $this->_WilokeUpdate, 'register_submenu');
                $this->_oLoader->add_filter('pre_set_site_transient_update_plugins', $this->_WilokeUpdate, 'update_plugins');
                $this->_oLoader->add_filter('pre_set_transient_update_plugins', $this->_WilokeUpdate, 'update_plugins');
                $this->_oLoader->add_filter('pre_set_site_transient_update_themes', $this->_WilokeUpdate, 'update_themes');
                $this->_oLoader->add_filter('pre_set_transient_update_themes', $this->_WilokeUpdate, 'update_themes');
            }
        }

        self::$aSlug = apply_filters('wiloke_service_filter_slug', self::$aSlug);

        // Notification
        $this->WilokeNotification = new WilokeNotification;
        $this->_oLoader->add_action('admin_menu', $this->WilokeNotification, 'registerMenu');

        // S3
        $this->_WilokeAmazonService = new WilokeAmazonService();

        // Importer
        if ( array_key_exists('import', self::$aSlug) ){
            $this->_WilokeImport = new WilokeImport();
            $this->_oLoader->add_action('admin_menu', $this->_WilokeImport, 'register_menu');
            $this->_oLoader->add_action('admin_footer', $this->_WilokeImport, 'admin_footer');
            $this->_oLoader->add_action('init', $this->_WilokeImport, 'generate_key');
            $this->_oLoader->add_action('admin_enqueue_scripts', $this->_WilokeImport, 'register_scripts');
            $this->_oLoader->add_action('wp_ajax_wiloke_import', $this->_WilokeImport, 'import_demo');
            $this->_oLoader->add_action('wp_ajax_wiloke_import_template', $this->_WilokeImport, 'import_template');
            $this->_oLoader->add_action('wp_ajax_wiloke_service_request_demo_data', $this->_WilokeImport, 'request_demo_data');
            $this->_oLoader->add_action('wp_ajax_wiloke_service_request_vc_portfolio', $this->_WilokeImport, 'wiloke_service_request_vc_portfolio');
            $this->_oLoader->add_action('wp_ajax_wiloke_service_import_vc_portfolio', $this->_WilokeImport, 'wiloke_service_import_vc_portfolio');
        }

        // Caching
        if ( array_key_exists('caching', self::$aSlug) ){
            $this->_WilokeCaching = new WilokeCaching();
            $this->_oLoader->add_action('admin_menu', $this->_WilokeCaching, 'purge_cache');
            $this->_oLoader->add_action('init', $this->_WilokeCaching, 'save_caching_settings');
        }

        if ( array_key_exists('minify', self::$aSlug) ){
            $this->_WilokeMinifyScripts = new WilokeMinifyScripts();
            $this->WilokeMinifyScripts = $this->_WilokeMinifyScripts;
            
            $this->_oLoader->add_action('init', $this->_WilokeMinifyScripts, 'init');
            $this->_oLoader->add_action('wp_ajax_save_settings', $this->_WilokeMinifyScripts, 'save_settings');
            $this->_oLoader->add_action('wp_enqueue_scripts', $this->_WilokeMinifyScripts, 'wp_enqueue_scripts', 9999);
            $this->_oLoader->add_filter('script_loader_tag', $this->_WilokeMinifyScripts, 'addAttributeToScripts', 10, 3);
            $this->_oLoader->add_filter('style_loader_tag', $this->_WilokeMinifyScripts, 'addAttributeToScripts', 10, 3);
            $this->_oLoader->add_action('admin_menu', $this->_WilokeMinifyScripts, 'register_submenu');
            $this->_oLoader->add_action('upgrader_process_complete', $this->_WilokeMinifyScripts, 'reCompressAfterUpgraded');
        }

        //Comingsoon
        $this->_WilokeCS = new WilokeComingsoon();
        $this->_oLoader->add_action('admin_menu', $this->_WilokeCS, 'register_menu');
        $this->_oLoader->add_action('comingsoon_footer', $this->_WilokeCS, 'pi_add_footer_code');
        $this->_oLoader->add_action('init', $this->_WilokeCS, 'comingsoon_actived');
    }

    public static function wiloke_update_theme_info() {
        self::get_theme_info(true);
    }

    /**
     * Get Theme Info
     *
     * @since 0.9.2
     */
    public static function findWilokeThemeInMultisite(){
        $oThemes  = wp_get_themes();
        foreach ($oThemes as $oTheme){
            if ( strtolower($oTheme->get('Author')) == 'wiloke' ){
                return $oTheme;
            }
        }

        return false;
    }

    /**
     * Get Theme Info
     *
     * @since 0.1
     */
    public static function get_theme_info($isFocus = false)
    {
        self::$aThemeInfo   = get_option(self::$currentThemeInfo);
        self::$wilokeApiKey = get_option('_wiloke_service_client_token');

        if ( !empty(self::$aThemeInfo['email']) && !$isFocus )
        {
            return false;
        }

        if ( is_multisite() ){
            $oTheme = self::findWilokeThemeInMultisite();
            if ( !$oTheme ){
                update_option(self::$currentThemeInfo, 'notwiloke');
                return false;
            }
	        $oParent = $oTheme->parent;
        }else{
            $oTheme  = wp_get_theme();
	        $oParent = $oTheme->parent();
        }

        if ( isset($oParent) && !empty($oParent) )
        {
            $oTheme = $oParent;
        }

        $userInfo = wp_get_current_user();
        
        if ( is_multisite() ){
            $userName = $userInfo->data->display_name;
	        $slug = $oTheme->__get('template');
        }else{
            $userName = $userInfo->user_firstname . ' ' . $userInfo->user_lastname;
	        $slug = get_template();
        }
        
        if ( strtolower($oTheme->get('Author')) == 'wiloke' )
        {
            self::$aThemeInfo = array('name'=>$oTheme->get('Name'),'author'=>$oTheme->get('Author'), 'version'=>$oTheme->get('Version'), 'email'=>get_option('admin_email'), 'siteurl'=>get_option('home'), 'username'=>$userName, 'slug'=>$slug);
            if ( empty(self::$aThemeInfo['email']) ){
                $oUser = wp_get_current_user();
                if ( in_array( 'administrator', (array) $oUser->roles ) ) {
                    self::$aThemeInfo['email'] = $oUser->user_email;
                }
            }
        }else{
            self::$aThemeInfo = 'notwiloke';
        }
        update_option(self::$currentThemeInfo, self::$aThemeInfo);
    }

    /**
     * Creating a nice theme key
     * @since 1.0
     */
    public static function handle_text($text)
    {
        $text = strtolower($text);
        $text = stripslashes($text);
        $text = str_replace( array(' ', '-'), array('_', '_'), $text);
        return $text;
    }

    /**
     * Front page
     * @since 1.0
     */
    public function load_public(){
        if ( is_admin() ){
            return;
        }

        $this->_oLoader->add_action('wiloke_comingsoon_footer', $this->_WilokeCS, 'register_foot_scripts');
        $this->_oLoader->add_action('wiloke_comingsoon_head', $this->_WilokeCS, 'register_head_scripts');
    }


    /**
     * Ensures that only the instance is loaded and can be loaded
     * @since 1.0
     * @static
     * @return object of WilokeService
     */
    public static function instance()
    {
        if ( self::$_instance === null )
        {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Callback to functions, which have been registered above
     * @since 1.0
     */
    public function run()
    {
        $this->_oLoader->run();
    }

    private static function _request( $url, $args )
    {
        if ( !is_admin() ){
            return false;
        }

        if ( defined('WILOKE_DISABLE_REQUEST') && WILOKE_DISABLE_REQUEST ) {
            return false;
        }

        if ( get_transient(self::$temporaryDisableRequest) ) {
            return new WP_Error( 'call_again_in_a_few_minutes', esc_html__( 'Something went wrong', 'wiloke-service' ) );
        }

        if ( empty( WilokeService::$wilokeApiKey ) ) {
            return new WP_Error( 'api_token_error', esc_html__( 'An API token is required.', 'wiloke-service' ) );
        }

        $defaults = array(
            'headers' => array(
                'Authorization' => 'Bearer '.self::$wilokeApiKey,
                'Accept' => 'application/json'
            ),
            'timeout' => 20,
            'sslverify' => true, // for localhost,
            'body' => self::$aThemeInfo
        );
        $args = wp_parse_args( $args, $defaults );
        // Make an API request.
        $response = wp_remote_get( esc_url_raw( $url ), $args );

        // Check the response code.
        $response_code    = wp_remote_retrieve_response_code( $response );
        $response_message = wp_remote_retrieve_response_message( $response );

        if ( 200 !== $response_code && !empty( $response_message ) ) {
            update_option(self::$wilokeServiceError, $response_message);
            return new WP_Error( $response_code, $response_message );
        } elseif ( 200 !== $response_code ) {
            $message = esc_html__('An unknown API error occurred.', 'wiloke-service');
            update_option(self::$wilokeServiceError, $message);
            return new WP_Error( $response_code, $message );
        } else {
            delete_option(self::$wilokeServiceError);
            
            $parseResponse = json_decode(wp_remote_retrieve_body( $response ));

            if ( !is_object($parseResponse) && !is_array($parseResponse) )
            {
                set_transient(WilokeService::$temporaryDisableRequest, true, 60);
                return new WP_Error('call_again_in_a_few_minutes', esc_html__('Please try call again in a few minutes', 'wiloke-service'));
            }
          
            return $parseResponse;
        }       
    }

    public static function request($url, $args=array()){
       return self::_request($url, $args);
    }
}