<?php
/**
 * @package Wiloke Framework
 * @category Core
 * @author WilokeTeam
 */

if ( !defined('ABSPATH') )
{
    exit; // Exit If accessed directly
}

if ( !class_exists('Wiloke') ) :
    /**
     * Main Wiloke Class
     * @class Wiloke
     * @version 1.0
     */

    class Wiloke{

        /**
         * First time Installation theme theme
         * @since 1.0
         */
        public static $firsTimeInstallation = 'wiloke_first_time_theme_installation';

        /**
         * @var string
         */
        public $version = '1.0.1';

        /**
         * @var string
         * @since 1.0.1
         */
        public static $wilokeDesignPortfolioDemos = 'wiloke_design_portfolio_demos';

        /**
         * @var $aConfigs - an Array contains all of configs
         */

        /**
         * @var Wiloke The single instance of the class
         * @since 1.0
         */
        protected static $_instance = null;

        /**
         * @var WO_Options $aThemeOptions
         */
        public $aThemeOptions = null;

        /**
         * Main Wiloke Instance
         *
         * Ensures only one instance of Wiloke is loaded or can be loaded.
         *
         * @since 1.0
         * @var static
         * @see Wiloke()
         * @return Wiloke - Main Instance
         */
        public static function instance()
        {
            if ( is_null(self::$_instance) )
            {
                self::$_instance = new self();
            }

            return self::$_instance;
        }

        /**
         * An instance of WilokeLoader class
         *
         * @since 1.0
         * @access protected
         * @return object
         */
        protected $_loader;

        /**
         * An instance of WO_Ajax class
         *
         * @since 1.0
         * @access protected
         * @return object
         */
        protected $_ajax;


        /**
         * He knows everything about WO_ThemeOptions
         *
         * @since 1.0
         * @access protected
         * @return object
         */
        protected $_themeOptions;

        /**
         * Register Sidebar
         *
         * @since 1.0
         * @access protected
         * @return object
         */
        protected $_registerSidebar;

        /**
         * An instance of WO_AdminGeneral
         *
         * @since 1.0
         * @access protected
         * @return object
         */
        protected $_adminGeneral;

        /**
         * An instance of WilokePublic
         *
         * @since 1.0
         * @access protected
         * @return object
         */
        protected $_public;
        public $frontEnd;

        /**
         * An instance of Mobile_Detect
         *
         * @since 1.0
         * @access protected
         * @return object
         */
        public static $mobile_detect;

        /**
         * An instance of Mobile_Detect
         *
         * @since 1.0
         * @access protected
         * @return object
         */
        public static $public_path;

        /**
         * An instance of WO_Taxonomy
         *
         * @since 1.0
         * @access protected
         * @return object
         */
        public $_taxonomy;

        /**
         * An instance of Mobile_Detect
         *
         * @since 1.0
         * @access protected
         * @return object
         */
        public static $public_url;

        /**
         * Predis Instance
         *
         * @since 1.0
         * @access public
         * @return object
         */
        public static $wilokePredis = null;
        public static $wilokeRedisTermCaching = null;
        public static $wilokeRedisTimeCaching = 86400;

        /**
         * List of errors
         *
         * @since 1.0
         * @static
         * @return array
         */
        public static $list_of_errors;

        /**
         * Caching All terms here
         * @since 1.0.1
         */
        public static $aWilokeTerms;

        /**
         * Caching post meta
         * @since 1.0.1
         */
        public static $aPostMeta;

        /**
         * Register autoload
         * @since 1.0.1
         */
        public static function autoload($name){
            if ( strpos($name, 'Wiloke') === false ){
                return;
            }

            $parseFileName = 'class.' . $name . '.php';

            if ( is_file( get_template_directory() . '/admin/inc/' . $parseFileName ) ) {
                include  get_template_directory() . '/admin/inc/' . $parseFileName;
            }elseif ( is_file( get_template_directory() . '/admin/public/' . $parseFileName ) ){
                include get_template_directory() . '/admin/public/' . $parseFileName;
            }
        }

        /**
         * Wiloke Constructor.
         */
        public function __construct()
        {
            self::$public_path = get_template_directory() . '/admin/public/';
            self::$public_url  = get_template_directory_uri() . '/admin/public/';

            do_action('wiloke_action_before_framework_init');

            $this->defineConstants();
            $this->configs();
            $this->predis_init();
            $this->include_modules();

            do_action('wiloke_action_after_framework_loaded');
            add_action('after_setup_theme', array($this, 'run_after_theme_loaded'));
            add_action('after_switch_theme', array($this, 'after_switch_theme'));
        }

        /**
         * After Switch to Oz Theme
         * @since 1.0
         */
        public function after_switch_theme(){
            if ( get_option(self::$firsTimeInstallation) ){
                update_option(self::$firsTimeInstallation, wp_get_theme()->get('Name'));
            }
        }

        /**
         * Set Temporary session
         * @since 1.0
         */
        public static function setTemporarySession($key, $value){
            setcookie($key, $value, time()+3600, '/');
        }

        /**
         * Set Temporary session
         * @since 1.0
         */
        public static function getTemporarySession($key, $isEcho=false){
            $message = isset($_COOKIE[$key]) ? $_COOKIE[$key] : '';
            setcookie($key, 'delete', time()-3600, '/');

            if ( !$isEcho ){
                return $message;
            }else{
                self::wiloke_kses_simple_html($message);
            }
        }

        /**
         * Init Predis
         * @since 1.0.3
         */
        public function predis_init(){
            if ( is_file( WP_PLUGIN_DIR . '/wiloke-service/admin/predis/autoload.php' ) ) {
                include WP_PLUGIN_DIR . '/wiloke-service/admin/predis/autoload.php';
                $aConfigs = array(
                    'scheme' => 'tcp',
                    'host'   => '127.0.0.1',
                    'port'   => 6379
                );

                try {
                    $redis = new Predis\Client($aConfigs, array('profile' => '2.8'));
                    
                    $redis->connect();
                    self::$wilokePredis = $redis;

                }catch(Predis\Connection\ConnectionException $e){

                }
            }
        }

        public static function setRedisCache($key, $value, $echoBeforeSet=false){
            if ( $echoBeforeSet ) {
                echo $value;
            }

            if ( self::$wilokePredis ){
                if ( is_object($value) ) {
                    $value = get_object_vars($value);
                }elseif ( is_string($value) ) {
                    $value = array($value);
                }

                if ( is_array($value) ) {
                    $value = serialize($value);
                }

                self::$wilokePredis->setEx($key, self::$wilokeRedisTimeCaching, $value);
            }

            return false;
        }

        public static function getRedisCache($key, $isString = false){
            if ( self::$wilokePredis ){
                $content = self::$wilokePredis->get($key);

                if ( empty($content) ) {
                    return false;
                }

                $content =  unserialize($content);

                if ( $isString || ( (count($content) === 1) && key($content) === 0 ) ) {
                    $content = $content[0];
                }

                return $content;
            }

            return false;
        }

        /**
         * Register hooks after theme loaded
         * @since 1.0
         */
        public function run_after_theme_loaded()
        {
            $this->run_modules();
            $this->general_hooks();
            $this->admin_hooks();
            $this->public_hooks();
            $this->run();
        }

        /**
         * Define Wiloke Constants
         */
        public function defineConstants()
        {
            $this->define('WILOKE_THEME_URI', trailingslashit(get_template_directory_uri()));
            $this->define('WILOKE_THEME_DIR', trailingslashit(get_template_directory()));

            $this->define('WILOKE_AD_REDUX_DIR', trailingslashit(WILOKE_THEME_DIR . 'admin/inc/redux-extensions'));
            $this->define('WILOKE_AD_REDUX_URI', trailingslashit(WILOKE_THEME_URI . 'admin/inc/redux-extensions'));

            $this->define('WILOKE_AD_SOURCE_URI', trailingslashit(get_template_directory_uri()) . 'admin/source/');
            $this->define('WILOKE_AD_ASSET_URI', trailingslashit(get_template_directory_uri()) . 'admin/asset/');

            $this->define('WILOKE_INC_DIR', trailingslashit(get_template_directory() . '/admin/inc/'));
            $this->define('WILOKE_PUBLIC_DIR', trailingslashit(get_template_directory() . '/admin/public/'));
            $this->define('WILOKE_TPL_BUILDER', trailingslashit(get_template_directory() . '/template-builder/'));
            $this->define('WILOKE_THEMESLUG', 'wiloke');
            $this->define('WILOKE_THEMENAME', 'Wiloke');
            $this->define('WILOKE_THEMEVERSION', $this->version);
            $this->define('TEXT_DOMAIN', 'oz');
        }

        /**
         * Includes
         */
        public function configs()
        {
            /**
             * Wiloke Configs
             */
            $aListOfConfigs = glob(get_template_directory().'/configs/*.php');

            foreach ( $aListOfConfigs as $file )
            {
                $parsekey = explode('/', $file);
                $parsekey = end($parsekey);
                $parsekey = str_replace(array('config.', '.php'), array('', ''), $parsekey);
                $this->aConfigs[$parsekey] = include $file;
            }

        }

        public function include_modules()
        {
            /**
             * Including Classes
             */
            do_action('wiloke_admin_hook_before_include_modules');
            include WILOKE_INC_DIR . 'func.visualComposer.php';

            // Front-end
            include WILOKE_INC_DIR . 'lib/Mobile-Detect/Mobile_Detect.php';

            do_action('wiloke_admin_hook_after_include_modules');
        }

        /**
         * Initialize Modules
         * @since 1.0
         */
        public function run_modules()
        {
            $this->_loader       = new WilokeLoader();
            $this->_public       = new WilokePublic();
            $this->frontEnd      = $this->_public;
            
            self::$wilokeRedisTermCaching = new WilokeRedisTermCaching();
            new WilokeAlert;
            new WilokeSocialNetworks;
            new WilokeInstallPlugins;
            new WilokeHtmlHelper;

            if ( !$this->kindofrequest('admin') )
            {
                self::$mobile_detect = new Mobile_Detect();
                new WilokeHead;
                new WilokeFrontPage;
            }

            $this->_themeOptions    = new WilokeThemeOptions();
            $this->_adminGeneral    = new WilokeAdminGeneral();

            new WilokeAdminBar;
            new WilokeReduxExtensions();
            new WilokeMetaboxes();
            $this->_registerSidebar = new WilokeWidget();
            $this->_taxonomy        = new WilokeTaxonomy();
            new WilokeContactForm();
            new WilokeUser();
            new WilokeInfiniteScroll();
            $this->_ajax            = new WilokeAjax();
            new WilokePostTypes;

            if ( class_exists('WilokeService') && class_exists('WilokeCaching') ){
                if ( !empty(WilokeCaching::$aOptions) ) {
                    self::$wilokeRedisTimeCaching = WilokeCaching::$aOptions['redis']['caching_interval'];
                }
            }
        }

        /**
         * Do you meet him ever?
         * @params $className
         */
        public function isClassExists($className, $autoMessage=true)
        {
            if ( !class_exists($className) )
            {
                if ( $this->kindofrequest('admin') )
                {
                    if ( $autoMessage )
                    {
                        $message = esc_html__('Sorry', 'oz') . $className . esc_html__('Class doesn\'t exist!', 'oz');
                    }else{
                        $message = true;
                    }
                }else{
                    $message = false;
                }


                throw new Exception($message);

            }else{
                return true;
            }
        }

        /**
         * Check this file whether it exists or not?
         */
        public function isFileExists($dir, $file)
        {
            if ( file_exists($dir.$file) )
            {
                return true;
            }else{
                $message = sprintf( __('The file with name %s doesn\'t exist. Please open a topic via support.wiloke.com to report this problem.', 'oz'), $file );
                self::$list_of_errors['error'][] = $message;
            }
        }

        /**
         * Define constant if not already set
         * @param string $name
         * @param string|bool $value
         */
        public function define($name, $value)
        {
            if ( !defined($name) )
            {
                define($name, $value);
            }
        }

        public static function display_number($count, $zero, $one, $more)
        {
            $count = absint($count);

            switch ($count)
            {
                case 0:
                    $count = $zero;
                    break;
                case 1:
                    $count = $count . ' ' . $one;
                    break;
                default:
                    $count = $count . ' ' . $more;
                    break;
            }

            return $count;
        }

        /**
         * What kind of request is that?
         * @param $needle
         * @return bool
         */
        public function kindofrequest($needle='admin')
        {
            switch ( $needle )
            {
                case 'admin':
                    return is_admin() ? true : false;
                break;

                default:
                    if ( !empty($needle) )
                    {
                        global $pagenow;

                        if ( $pagenow === $needle )
                            return true;
                    }

                    return false;
                  break;
            }
        }

        /**
         * Get terms by post id
         * @postId : integer
         * $taxonomy: string/array
         */
        public static function wiloke_get_terms_by_post_id($postID, $taxonomy='category')
        {
            return wp_get_post_terms($postID, $taxonomy);
        }

        /**
         * Get Term Slug For Portfolio
         * @since 1.0.1
         * @postID: integer
         * $taxonomy: string/array
         */
        public static function wiloke_terms_slug($postID, $taxonomy='category', $separated=' ', $oTerms=null)
        {
            $oTerms = is_null($oTerms) ? wp_get_post_terms($postID, $taxonomy) : $oTerms;

            $slug = '';

            if ( !empty($oTerms) && !is_wp_error($oTerms) )
            {
                foreach ( $oTerms as $oTerm )
                {
                    self::setTermsCaching($taxonomy, array($oTerm->term_id=>$oTerm));
                    $slug .= $oTerm->slug . $separated;
                }

                $slug = trim($slug, $separated);
            }

            return $slug;
        }

        /**
         * Caching Post Meta
         * @since 1.0
         */
        public static function setPostMetaCaching($postID, $key, $data){
            if ( isset(self::$aPostMeta[$postID]) && isset(self::$aPostMeta[$postID][$key]) ) {
                return;
            }

            self::$aPostMeta[$postID][$key] = json_encode($data);
        }

        /**
         * Get Post Meta
         * @since 1.0
         */
        public static function getPostMetaCaching($postID, $key){
            if ( isset(self::$aPostMeta[$postID]) && isset(self::$aPostMeta[$postID][$key]) ) {
                return json_decode(self::$aPostMeta[$postID][$key], true);
            }

            if ( !$data = get_post_meta($postID, $key, true) ) {
                return false;
            }

            self::setPostMetaCaching($postID, $key, $data);

            return $data;
        }

        /**
         * Get Term Name For Portfolio
         * @since 1.0.1
         * @postID: integer
         * $taxonomy: string/array
         */
        public static function wiloke_terms_name($postID, $taxonomy='category', $separated=', ', $oTerms=null)
        {
            $oTerms = is_null($oTerms) ? wp_get_post_terms($postID, $taxonomy) : $oTerms;

            $slug = '';

            if ( !empty($oTerms) && !is_wp_error($oTerms) )
            {
                foreach ( $oTerms as $oTerm )
                {
                    self::setTermsCaching($taxonomy, array($oTerm->term_id=>$oTerm));
                    $slug .= $oTerm->name . $separated;
                }

                $slug = trim($slug, $separated);
            }

            return $slug;
        }

        /**
         * Set Terms Caching
         *
         * @aData Array
         * @taxonomy String - Taxonomy key
         * @since 1.0.1
         */
        public static function setTermsCaching($taxonomy, $aData){
             if ( empty($taxonomy) || empty($aData) ){
                 return;
             }

             foreach ( $aData as $key => $val ) {
                 self::$aWilokeTerms[$taxonomy][$key] = json_encode($val);
             }
        }

        /**
         * Get Term Caching
         *
         * @since 1.0.1
         * @taxonomy Taxonomy Key
         * @termID Array
         */
        public static function getTermCaching($taxonomy, $termID){
            if ( is_array($termID) ) {
                foreach ( $termID as $id ){
                    if ( isset(self::$aWilokeTerms[$taxonomy][$id]) ) {
                        $oTermCaching[$id] = json_decode(self::$aWilokeTerms[$taxonomy][$id]);
                        $deleteHim = array_search($id, $termID);
                        unset($termID[$deleteHim]);
                    }
                }
            }else{
                if ( isset(self::$aWilokeTerms[$taxonomy][$termID]) ) {
                    $oTermCaching = json_decode(self::$aWilokeTerms[$taxonomy][$termID]);
                    $termID = null;
                }
            }

            if ( empty($termID) ) {
                return (object)$oTermCaching;
            }

            $oTerms = get_terms(array(
                'taxonomy'   => $taxonomy,
                'hide_empty' => true,
                'include'    => $termID
            ));

            if ( !empty($oTerms) && !is_wp_error($oTerms) ){
                foreach ( $oTerms as $oTerm ) {
                    $aTerms[$oTerm->term_id] = json_encode($oTerm);
                    self::setTermsCaching($taxonomy, array($oTerm->term_id=>$oTerm));
                }

                if ( is_array($termID) ) {
                    $oTerms = isset($oTermCaching) ? array_merge($oTerms, $oTermCaching) : $oTerms;
                    return (object)$oTerms;
                }else{
                    return (object)$oTerms[0];
                }

            }

            return false;
        }

        /**
         * Convert a pagebuilder array to a wordpress query args array
         * @author wiloke team
         * @since 1.0
         */
        static public function wiloke_query_args($atts='', $paged='')
        {
            $aWpQueryArgs['ignore_sticky_posts'] = 1;
            $aWpQueryArgs['post_status'] 		 = 'publish';

            extract(shortcode_atts(
                    array(
                        'paged'                 => 1,
                        'category_ids' 			=> '',
                        'category_id' 			=> '',
                        'tag_slug' 				=> '',
                        'sort' 					=> '',
                        'author_id' 			=> '',
                        'post_types' 			=> '',
                        'posts_per_page' 		=> '',
                        'offset' 				=> '',
                        'post__not_in'			=> '',
                        'order'		 	 		=> 'DESC',
                        'orderby'				=> '',
                        'cat__in'				=> '',
                        'tax_query'				=> array()
                    ),
                    $atts
                )
            );


            if ( !empty($category_ids) )
            {
                $aWpQueryArgs['cat'] = $category_ids;
            }

            if ( !empty($orderby) )
            {
                $aWpQueryArgs['orderby'] = $orderby;
            }

            if ( !empty($tag_slug) )
            {
                $aWpQueryArgs['tag'] = str_replace(' ', '-', $tag_slug);
            }

            if (!empty($author_id))
            {
                $aWpQueryArgs['author'] = $author_id;
            }

            if (!empty($post_types))
            {
                $aPostTypes 		= array();
                $aParsePostTypes 	= explode(',', $post_types);

                foreach ($aParsePostTypes as $postType)
                {
                    if (trim($postType) != '')
                    {
                        $aPostTypes[] = trim($postType);
                    }
                }

                $aWpQueryArgs['post_type'] = $aPostTypes;  // add post types to query args
            }


            //posts per page
            if ( empty($posts_per_page) )
            {
                $posts_per_page = get_option('posts_per_page');
            }
            $aWpQueryArgs['posts_per_page'] = $posts_per_page;

            //custom pagination
            if (!empty($paged)) {
                $aWpQueryArgs['paged'] = $paged;
            } else {
                $aWpQueryArgs['paged'] = 1;
            }

            // offset + custom pagination - if we have offset, wordpress overwrites the pagination and works with offset + limit
            if (!empty($offset) and $paged > 1) {
                $aWpQueryArgs['offset'] = $offset + ( ($paged - 1) * $limit) ;
            } else {
                $aWpQueryArgs['offset'] = $offset ;
            }

            if ( !empty($post__not_in) )
            {
                if ( is_string($post__not_in) )
                {
                    $post__not_in                 = trim($post__not_in, ',');
                    $aWpQueryArgs['post__not_in'] = explode(',', $post__not_in);
                }else{
                    $aWpQueryArgs['post__not_in'] = $post__not_in;
                }

                $aWpQueryArgs['post__not_in'] = array_map('intval', $aWpQueryArgs['post__not_in']);
            }

            if ( !empty($tax_query) )
            {
                $aWpQueryArgs['tax_query'] = $tax_query;
            }

            if ( !empty($order) )
            {
                $aWpQueryArgs['order'] = $order;
            }

            if ( !empty($cat__in) )
            {
                $aWpQueryArgs['cat__in'] = $cat__in;
            }

            return $aWpQueryArgs;
        }

        /**
         * Parse thumbnail size
         */
        static public function wiloke_parse_thumbnail_size($size)
        {
            if ( strpos($size, 'x') )
            {
                return explode('x', $size);
            }

            return $size;
        }

        /**
         * User Data
         *
         * @since 1.0
         * @return object
         */
        static public function get_userdata($field='')
        {
            if ( is_user_logged_in() )
            {
                if ( !empty($field) )
                {
                    return get_user_meta( get_current_user_id(), $field, true );
                }else{
                    return get_userdata( get_current_user_id() );
                }
            }
        }

        /**
         * Parse Template to key
         */
        static public function wiloke_parse_template_to_key($postID)
        {
            $target = get_page_template_slug($postID);
            $target = explode('/', $target);
            $target = end($target);

            $target = str_replace( array('', '.php'), array('', ''), $target );

            return $target;
        }

        /**
         * Truncate string
         */
        static public function wiloke_content_limit($limit=0, $post, $isFocusCutString=false, $content='', $isReturn = true, $dotted='')
        {
            if ( empty($limit) )
            {
                return;
            }

            if ( empty($content) )
            {
                if ( !$isFocusCutString && !empty($post->post_excerpt) )
                {
                    $content = $post->post_excerpt;
                }else{
                    if ( isset($post->ID) )
                    {
                        $content = get_the_content($post->ID);
                    }else{
                        $content = null;
                    }
                }
            }

            $content = strip_shortcodes($content);
            $content = strip_tags($content, '<script>,<style>');
            $content = trim( preg_replace_callback('#<(s(cript|tyle)).*?</\1>#si', function(){
                return '';
            }, $content));

            $content = str_replace('&nbsp;', '<br /><br />', $content);

            $content = self::wiloke_truncate_pharse($content, $limit);

            if ( $isReturn )
            {
                return $content . $dotted;
            }else{
                Wiloke::wiloke_kses_simple_html($content . $dotted, false);
            }
        }

        static public function wiloke_truncate_pharse($text, $max_characters)
        {
            $text = trim( $text );

            if(function_exists('mb_strlen') && function_exists('mb_strrpos'))
            {
                if ( mb_strlen( $text ) > $max_characters ) {
                    $text = mb_substr( $text, 0, $max_characters + 1 );
                    $text = trim( mb_substr( $text, 0, mb_strrpos( $text, ' ' ) ) );
                }
            }else{
                if ( strlen( $text ) > $max_characters ) {
                    $text = substr( $text, 0, $max_characters + 1 );
                    $text = trim( substr( $text, 0, strrpos( $text, ' ' ) ) );
                }
            }
            return $text;
        }

        static public function wiloke_kses_simple_html($content, $isReturn=false)
        {
            $allowed_html = array(
                'a' => array(
                    'href'  => array(),
                    'style' => array(
                        'color' => array()
                    ),
                    'title' => array(),
                    'target'=> array(),
                    'class' => array()
                ),
                'br'     => array('class' => array()),
                'p'      => array('class' => array(), 'style'=>array()),
                'em'     => array('class' => array()),
                'strong' => array('class' => array()),
                'span'   => array('data-typer-targets'=>array(), 'class' => array()),
                'i'      => array('class' => array()),
                'ul'     => array('class' => array()),
                'li'     => array('class' => array()),
                'code'   => array('class'=>array()),
                'pre'    => array('class' => array()),
                'iframe' => array('src'=>array(), 'width'=>array(), 'height'=>array(), 'class'=>array('embed-responsive-item')),
                'img'    => array('src'=>array(), 'width'=>array(), 'height'=>array(), 'class'=>array(), 'alt'=>array()),
                'embed'  => array('src'=>array(), 'width'=>array(), 'height'=>array(), 'class' => array()),
            );

            $content = str_replace('[wiloke_quotes]', '"', $content);

            if ( !$isReturn ) {
                echo wp_kses(wp_unslash($content), $allowed_html);
            }else{
                return wp_kses(wp_unslash($content), $allowed_html);
            }
        }

        public static function json_clean_decode($json, $assoc = false, $depth = 512, $options = 0) {

            // search and remove comments like /* */ and //
            $json = preg_replace_callback("/(/\*([^*]|[\r\n]|(\*+([^*/]|[\r\n])))*\*+/)|([\s\t](//).*)/", '', $json);

            $phpVersion = phpversion();
            if(version_compare($phpVersion, '5.4.0', '>=')) {
                $json = json_decode($json, $assoc, $depth, $options);
            }
            elseif(version_compare($phpVersion, '5.3.0', '>=')) {
                $json = json_decode($json, $assoc, $depth);
            }
            else {
                $json = json_decode($json, $assoc);
            }

            return $json;
        }

        /**
         * Get Menu Object
         */
        static public function wiloke_get_nav_menus()
        {
            $aNavMenus          = wp_get_nav_menus();
            $aParseNavMenus     = array();

            if ( !empty($aNavMenus) )
            {
                $aParseNavMenus[-1] = esc_html__('Use default menu', 'oz');
                foreach ($aNavMenus as $aMenu)
                {
                    $aParseNavMenus[$aMenu->term_id] = $aMenu->name;
                }
            }else{
                $aParseNavMenus[-1] = esc_html__('There are no menus', 'oz');
            }

            return $aParseNavMenus;
        }

        /**
         * Render Date
         */
        static public function wiloke_render_date($postID, $isEcho = true)
        {
            $format = get_option('date_format');
            $date   = get_the_date($format, $postID);
            
            if ( $isEcho )
            {
                echo $date;
            }else{
                return $date;
            }
        }

        static public function wiloke_parse_inline_style($aArgs)
        {
            $style = null;
            foreach ( $aArgs as $key => $val )
            {
                if ( !empty($val) )
                {
                    $style .= $key . ':' . $val . ';';
                }
            }

            return $style;
        }

        static public function wiloke_parse_atts($aArgs)
        {
            $style = null;
            foreach ( $aArgs as $key => $val )
            {
                if ( !empty($val) )
                {
                    $style .= 'data-'. $key . '=' . $val . ' ';
                }
            }

            return $style;
        }

        static public function wiloke_lazy_load($src='', $cssClass='', $aAtributes=array(), $status = null, $isFocusRender = false)
        {
            $renderAttr = '';
            if ( !empty($aAtributes) )
            {
                foreach ( $aAtributes as $atts => $val )
                {
                    $renderAttr .= $atts . '=' . esc_attr($val) . ' ';
                }
            }

            if ( !$isFocusRender )
            {
                if ( $status === null )
                {
                    global $wiloke;

                    $status = $wiloke->aThemeOptions['general_is_lazy_load'];
                }

                if ( $status ) :
                    $cssClass = $cssClass . ' lazy';
                    ?>
                    <img class="<?php echo esc_attr($cssClass); ?>" data-original="<?php echo esc_url($src); ?>" <?php echo esc_attr($renderAttr); ?> />
                    <noscript>
                        <img src="<?php echo esc_url($src); ?>" <?php echo esc_attr($renderAttr); ?>  />
                    </noscript>
                    <?php
                else :
                    ?>
                    <img src="<?php echo esc_url($src); ?>" <?php echo esc_attr($renderAttr); ?>  />
                    <?php
                endif;
            }else{
                ?>
                <img src="<?php echo esc_url($src); ?>" <?php echo esc_attr($renderAttr); ?>  />
                <?php
            }
        }

        static function wiloke_get_contact_form7()
        {
            $args       = array('post_type'=>'wpcf7_contact_form', 'posts_per_page'=>50, 'post_status'=>'publish');
            $query      = new WP_Query($args);
            $aValues    = array();

            if ( $query->have_posts() ) : while( $query->have_posts() ) : $query->the_post();
                $aValues[$query->post->ID] = $query->post->post_title;
            endwhile; endif;

            return $aValues;
        }

        static function wiloke_render_follow_us($cssClass='')
        {
            global $wiloke;

            if ( !empty(WilokeSocialNetworks::$aSocialNetworks) )
            {
                echo '<div class="'.esc_attr($cssClass).'">';
                    foreach (WilokeSocialNetworks::$aSocialNetworks as $key )
                    {
                        if ( !empty($wiloke->aThemeOptions['social_network_'.$key]) )
                        {
                            $icon  = str_replace('_', '-', $key);
                            $title = str_replace('_', ' ', $key);
                            $title = ucfirst($title);

                            echo '<a title="'.esc_attr($title).'" href="'.esc_url($wiloke->aThemeOptions['social_network_'.$key]).'" class="'.esc_attr($key).'"><i class="fa fa-'.esc_attr($icon).'"></i></a>';
                        }
                    }
                echo '</div>';
            }
        }

        static public function wiloke_render_post_format_icon($postID)
        {
            $postFormat = get_post_format($postID);

            switch ($postFormat)
            {
                case 'image':
                    $icon = 'fa fa-image';
                    break;

                case 'gallery':
                    $icon = 'fa fa-picture-o';
                    break;

                case 'video';
                    $icon = 'fa fa-youtube-play';
                    break;

                case 'audio':
                    $icon = 'fa fa-music';
                    break;

                case 'quote':
                    $icon = 'fa fa-quote-right';
                    break;

                case 'link':
                    $icon = 'fa fa-link';
                    break;

                default:
                    $icon = 'fa fa-thumb-tack';
                    break;
            }

            return $icon;
        }

        /**
         * Get other templates (e.g. product attributes) passing attributes and including the file.
         *
         * @access public
         * @param string $template_name
         * @param array $args (default: array())
         * @param string $template_path (default: '')
         */
        public static function get_template($template_name, $args = array(), $template_path = '')
        {
            if ( ! empty( $args ) && is_array( $args ) )
            {
                extract( $args );
            }

            $located = self::locate_template($template_name, $template_path);

            if ( !file_exists( $located ) )
            {
                _doing_it_wrong( __FUNCTION__, sprintf( '<code>%s</code> does not exist.', $located ), '1.0' );
                return;
            }

            // Allow 3rd party plugin filter template file from their plugin.
            $located = apply_filters('wiloke_get_template', $located, $template_name, $args, $template_path);

            do_action( 'wiloke_before_template_part', $template_name, $template_path, $located, $args );
            
            include($located);

            do_action( 'wiloke_after_template_part', $template_name, $template_path, $located, $args );
        }

        /**
         * Locate a template and return the path for inclusion.
         *
         * This is the load order:
         *
         *		yourtheme		/	$template_path	/	$template_name
         *		yourtheme		/	$template_name
         *		$default_path	/	$template_name
         *
         * @access public
         * @param string $template_name
         * @param string $template_path (default: '')
         * @param string $default_path (default: '')
         * @return string
         */
        public static function locate_template($template_name, $template_path = '')
        {
            if ( !$template_path )
            {
                $template_path = 'admin/public/template/' ;
            }

            // Look within passed path within the theme - this is priority.
            $template = locate_template(
                array(
                    trailingslashit( $template_path ) . $template_name,
                    $template_name
                )
            );

            // Return what we found.
            return apply_filters( 'wiloke_locate_template', $template, $template_name, $template_path );
        }

        /**
         * Collection of hooks related to admin
         * @since 1.0
         */
        public function admin_hooks()
        {
            if ( is_file( WILOKE_THEME_DIR . 'hooks/admin.php' ) ) {
                require WILOKE_THEME_DIR . 'hooks/admin.php';
            }
        }

        /**
         * We care everything related to front-end
         * @since 1.0
         */
        public function public_hooks()
        {
            if ( is_file( WILOKE_THEME_DIR . 'hooks/public.php' ) ) {
                require WILOKE_THEME_DIR . 'hooks/public.php';
            }
        }

        /**
         * General Hooks, in other words, he works the both admin and front-end
         * @since 1.0
         */
        public function general_hooks()
        {
            if ( !empty($this->_themeOptions) )
            {
                $this->_loader->add_action('init', $this->_themeOptions, 'get_option');
            }

            if ( !empty($this->_registerSidebar) )
            {
                $this->_loader->add_action('widgets_init', $this->_registerSidebar, 'register_widgets');
            }
        }


        /**
         * Generate srcset and sizes
         *
         * @return: ['main'=>array('width', 'height', 'src'), 'srcset'=>array(), 'sizes'=>array()]
         * @since 1.0.1
         */
        public static function generateSrcsetImg($attachmentID, $size){
            $img = wp_get_attachment_image_src($attachmentID, $size);

            if ($img) {
                $aExtractSmallImg = wp_get_attachment_image_src($attachmentID, 'wiloke_xs_thumb');

                if ($aExtractSmallImg){
                    $attr['thumb_xs']['src']    = $aExtractSmallImg[0];
                    $attr['thumb_xs']['width']  = $aExtractSmallImg[1];
                    $attr['thumb_xs']['height'] = $aExtractSmallImg[2];
                }

                list($src, $width, $height) = $img;
                $aImgData = wp_get_attachment_metadata($attachmentID);
                $attr['main']['src']    = $src;
                $attr['main']['width']  = $width;
                $attr['main']['height'] = $height;

                if (is_array($aImgData)) {
                    $aSize  = array(absint($width), absint($height));
                    $srcset = wp_calculate_image_srcset($aSize, $src, $aImgData, $attachmentID);
                    $sizes  = wp_calculate_image_sizes($aSize, $src, $aImgData, $attachmentID);

                    if ($srcset && ($sizes || !empty( $attr['sizes']))) {
                        $attr['srcset'] = $srcset;

                        if ( empty($attr['sizes']) ) {
                            $attr['sizes'] = $sizes;
                        }
                    }
                }

                return $attr;
            }

            return false;
        }

        public static function clientIP(){
	        function get_client_ip() {
		        if (isset($_SERVER['HTTP_CLIENT_IP'])){
			        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
                }elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
		        }elseif(isset($_SERVER['HTTP_X_FORWARDED'])) {
			        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
		        }elseif(isset($_SERVER['HTTP_FORWARDED_FOR'])){
			        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
                }elseif(isset($_SERVER['HTTP_FORWARDED'])){
			        $ipaddress = $_SERVER['HTTP_FORWARDED'];
                }elseif(isset($_SERVER['REMOTE_ADDR'])){
			        $ipaddress = $_SERVER['REMOTE_ADDR'];
                }else{
			        $ipaddress = 'UNKNOWN';
		        }
		        return $ipaddress;
	        }
        }
        
        /**
         * List of actions and filters. We will run it soon
         * @since 1.0
         */
        public function run()
        {
            $this->_loader->run();
        }
    }

endif;
