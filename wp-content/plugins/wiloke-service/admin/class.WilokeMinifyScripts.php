<?php
/**
 * WilokeMinifyScripts
 * Speed up website
 *
 * @link        https://wiloke.com
 * @since       0.6
 * @package     WilokeService
 * @subpackage  WilokeService/admin
 * @author      Wiloke
 */

use MatthiasMullie\Minify;

if ( !defined('ABSPATH') )
{
    wp_die( esc_html__('You do not permission to access to this page', 'wiloke-service') );
}

if ( !class_exists('WilokeMinifyScripts') ) {

    class WilokeMinifyScripts
    {
		public $aListOfScripts;
		public $aListOfCss;
		public $aStatus;
		public $scriptPrefix = 'wiloke_minify_';
		protected $toggleAdditionalScripts = false;
		protected $aCustomCSS;
		protected $aCustomJS;
		protected $aWooCommerceCSS = array('woocommerce-layout'=>'woocommerce/assets/css/woocommerce-layout.css','woocommerce-general'=>'woocommerce/assets/css/woocommerce.css');

	    public function init(){
			$this->aStatus = get_option('_wiloke_minify');
		}

	    public function addAttributeToScripts($tag, $handle, $src)
	    {
	        global $wiloke;
	        
	        if ( ((strpos($handle, 'woocommerce') !== false) && !is_single()) || (strpos($src, '.css') !== false) ){
        		return str_replace( array(' src', ' href'), array(' data-cfasync="true" src', ' data-cfasync="true" href'), $tag );
	        }
	       	
	        return $tag;
	    }

		public function wp_enqueue_scripts(){
			if (!empty($this->aStatus)){
				$version = get_option('wiloke_minify_at_version');
				if ( version_compare($version, WilokeService::$aThemeInfo['version'], '<')  ){
					$this->_proceedCompressJavaScript();
					$this->_proceedCompressCss();
				}

				$aUploadInfo = wp_upload_dir();
				$uploadUrl   = $aUploadInfo['baseurl'] . '/';

				if (  isset($this->aStatus['javascript']) && !empty($this->aStatus['javascript']) ) {

					$jsFileName  = get_option('wiloke_minify_theme_js');
					$this->aListOfScripts = get_option('wiloke_minify_list_of_original_js');

					if (!empty($this->aListOfScripts)) {
						foreach ($this->aListOfScripts as $scriptTag) {
							wp_deregister_script($scriptTag);
							wp_dequeue_script($scriptTag);
						}
					}

					wp_enqueue_script('wiloke_minify_theme_js', $uploadUrl . $jsFileName, array('jquery'), WilokeService::getThemeVersion()->get('Version'), true);
				}

				if (  isset($this->aStatus['css']) && !empty($this->aStatus['css']) ) {
					$cssFileName = get_option('wiloke_minify_theme_css');
					$this->aListOfCss = get_option('wiloke_minify_list_of_original_css');

					if ( function_exists('is_woocommerce') ){
						$wooCommerceFileName = get_option('wiloke_minify_woocommerce_css');
						$aWooCommerceCssTags = array_keys($this->aWooCommerceCSS);
						if ( !empty($aWooCommerceCssTags) && !empty($wooCommerceFileName) ){
							foreach ($aWooCommerceCssTags as $cssTag){
								wp_deregister_style($cssTag);
								wp_dequeue_style($cssTag);
							}
							wp_enqueue_style('wiloke_minify_woocommerce_css', $uploadUrl . $wooCommerceFileName, array(), WilokeService::getThemeVersion()->get('Version'));
						}
					}

					if ( !empty($this->aListOfCss) ){
						foreach ($this->aListOfCss as $cssTag){
							wp_deregister_style($cssTag);
							wp_dequeue_style($cssTag);
						}
					}
					wp_enqueue_style('wiloke_minify_theme_css', $uploadUrl . $cssFileName, array(), WilokeService::getThemeVersion()->get('Version'));
				}	
			}
		}

		public function register_submenu()
	    {
	        add_submenu_page( WilokeService::$aSlug['main'], esc_html__('Minify Scripts', 'wiloke-service'), esc_html__('Minify Scripts', 'wiloke-service'), 'edit_theme_options', WilokeService::$aSlug['minify'], array($this, 'settings'));
	    }

	    public function jsDir($jsDir, $minifierJs){
			$minifierJs->add($jsDir);
			return $jsDir;
	    }

	    public function cssDir($cssDir, $minifierCss){
			$minifierCss->add($cssDir);
			return $cssDir;
	    }

	    public function settings(){
			$aData = get_option('_wiloke_minify');
			$aData = wp_parse_args($aData, array('css'=>0, 'javascript'=>0, 'toggle_custom_scripts'=>0, 'additional_js'=>'', 'additional_css'=>''));
		    $aData = apply_filters('wiloke-service/minify-scripts/settings', $aData);
	    	include plugin_dir_path(__FILE__) . 'html/minify/settings.php';
	    }

	    protected function _getScriptFromWPFolder($folder, $scriptName){
		    if ( $scriptName === 'underscore' || $scriptName === 'backbone' ){
			    return false;
		    }

			$dir = ABSPATH . 'wp-includes/' . $folder . '/' . $scriptName . '.min.'.$folder;
			if ( is_file($dir) ){
				return $dir;
			}
			return false;
	    }

	    protected function _getScriptFromLibFolder($folder, $scriptName){
			$dir = get_template_directory() . '/' . $folder . '/lib/';
			if ( is_file($dir.$scriptName.$folder) ){
				return $dir.$scriptName.$folder;
			}
			return false;
	    }

	    protected function _parseFontUrl($fonts)
	    {
		    $font_url = '';

		    /*
			Translators: If there are characters in your language that are not supported
			by chosen font(s), translate this to 'off'. Do not translate into your own language.
			 */
		    if ( 'off' !== _x( 'on', 'Google font: on or off', 'wiloke-service' ) ) {
			    $font_url = add_query_arg( 'family', urlencode( $fonts ), "//fonts.googleapis.com/css" );
		    }
		    return $font_url;
	    }

	    protected function _getScriptFromDefault($folder, $scriptName){
		    $dir = get_template_directory() . '/' . $folder . '/';
		    if ( is_file($dir.$scriptName.'min.'.$folder) ){
			    return $dir.$scriptName.'js';
		    }elseif( is_file($dir.$scriptName.$folder) ){
				return $dir.$scriptName.$folder;
		    }
		    return false;
	    }

	    protected function parseCustomScripts($aScripts){
			if ( empty($aScripts) ){
				return false;
			}

			$aScripts = explode("\n", $aScripts);
			$aParsedScripts = array();
			foreach ($aScripts as $aScript){
				$aScript = explode("=>", $aScript);
				$aParsedScripts[$aScript[0]] = $aScript[1];
			}

			return $aParsedScripts;
	    }

	    protected function _aGetThemesJavaScript(){
		    global $wiloke;

		    if ( !isset($wiloke->aConfigs['frontend']['scripts']) || empty($wiloke->aConfigs['frontend']['scripts']) ){
			    return false;
		    }

		    $aFiles = array();

		    foreach ( $wiloke->aConfigs['frontend']['scripts'] as $scriptTag => $aInfo ){
                if ( isset($aInfo['is_ignore_minify']) ){
                    continue;
                }

				if ($aInfo[0] === 'js' || $aInfo[0]  ===  'both'){
					if ( isset($aInfo['is_wp_store']) && $aInfo['is_wp_store'] ){
						if ( $file = $this->_getScriptFromWPFolder('js', $aInfo[1]) ){
							$aFiles[$scriptTag] = $file;
						}
					}else if ( ($aInfo[0] === 'both' || $aInfo[0] === 'js') && (!isset($aInfo['default']) || !$aInfo['default']) ){
						if ( $file = $this->_getScriptFromLibFolder('js', $aInfo[1]) ){
							$aFiles[$scriptTag] = $file;
						}
					}else{
						if ( $file = $this->_getScriptFromDefault('js', $aInfo[1]) ){
							$aFiles[$scriptTag] = $file;
						}
					}
				}
		    }

		    return $aFiles;
	    }

	    protected function _aGetCSS(){
		    global $wiloke;
		    if ( !isset($wiloke->aConfigs['frontend']['scripts']) || empty($wiloke->aConfigs['frontend']['scripts']) ){
			    return false;
		    }

		    $aFiles = array();

		    foreach ( $wiloke->aConfigs['frontend']['scripts'] as $scriptTag => $aInfo ){
		        if ( isset($aInfo['is_ignore_minify']) ){
		            continue;
                }

			    if ($aInfo[0] === 'css' || $aInfo[0]  ===  'both'){
				    if ( isset($aInfo['is_wp_store']) && $aInfo['is_wp_store'] ){
					    if ( $file = $this->_getScriptFromWPFolder('css', $aInfo[1]) ){
						    $aFiles[$scriptTag] = $file;
					    }
				    }else if ( ($aInfo[0] === 'both' || $aInfo[0] === 'css') && (!isset($aInfo['default']) || !$aInfo['default']) ){
					    if ( $file = $this->_getScriptFromLibFolder('css', $aInfo[1]) ){
						    $aFiles[$scriptTag] = $file;
					    }
				    }else{
					    if ( $file = $this->_getScriptFromDefault('css', $aInfo[1]) ){
						    $aFiles[$scriptTag] = $file;
					    }
				    }
			    }elseif( isset($aInfo['is_googlefont']) && $aInfo['is_googlefont'] ){
				    $aFiles[$scriptTag] = $this->_parseFontUrl($aInfo[0]);
			    }
		    }
		    return $aFiles;
	    }

	    protected function _proceedCompressJavaScript(){
		    $aFiles = $this->_aGetThemesJavaScript();
		    include plugin_dir_path(__FILE__) . 'minify-master/vendor/autoload.php';

		    $uploadDir = wp_upload_dir();
		    $uploadDir = $uploadDir['basedir'].'/';
		    $minifierJs = new Minify\JS();
		    if ( !empty($aFiles) ) {
		    	$aScriptTags = array();
			    if ( !empty($this->aCustomJS) ){
					$this->aCustomJS = $this->parseCustomScripts($this->aCustomJS);

					if ( !empty($this->aCustomJS) ){
						foreach ( $this->aCustomJS as $scripTag => $fileDir ){
							$file = trim(WP_PLUGIN_DIR . '/' . $fileDir);
							if ( is_file($file) ){
								array_push($aScriptTags, trim($scripTag));
								$this->jsDir($file, $minifierJs);
							}
						}
					}
			    }
			    foreach ( $aFiles as $scriptTag => $file ){
				    if ( is_file($file) ){
					    array_push($aScriptTags, $scriptTag);
					    $this->jsDir($file, $minifierJs);
				    }
			    }

			    $fileName = 'wiloke_minify_js_'.trim(WilokeService::$aThemeInfo['slug']).'_'.trim(WilokeService::$aThemeInfo['version']);

			    if ( get_option('wiloke_minify_theme_js') && is_file(get_option('wiloke_minify_theme_js')) ){
				    unlink(get_option('wiloke_minify_theme_js'));
			    }

			    $minifierJs->minify($uploadDir.$fileName.'.js');
			    update_option('wiloke_minify_theme_js', $fileName.'.js');
			    update_option('wiloke_minify_list_of_original_js', $aScriptTags);
			    update_option('wiloke_minify_at_version', WilokeService::$aThemeInfo['version']);
		    }
	    }

	    protected function _proceedCompressCss(){
		    $aFiles = $this->_aGetCSS();
		    include plugin_dir_path(__FILE__) . 'minify-master/vendor/autoload.php';

		    $uploadDir = wp_upload_dir();
		    $uploadDir = $uploadDir['basedir'].'/';
		    $minifierJs = new Minify\CSS();

		    if ( !empty($aFiles) ) {
			    $aScriptTags = array();

			    if ( !empty($this->aCustomCSS) ){
				    $this->aCustomCSS = $this->parseCustomScripts($this->aCustomCSS);
				    if ( !empty($this->aCustomCSS) ){
					    foreach ( $this->aCustomCSS as $scripTag => $fileDir ){
						    $file = trim(WP_PLUGIN_DIR . '/' . $fileDir);
						    if ( is_file($file) ){
							    array_push($aScriptTags, trim($scripTag));
							    $this->cssDir($file, $minifierJs);
						    }
					    }
				    }
			    }

			    foreach ( $aFiles as $scriptTag => $file ){
				    if ( is_file($file) ){
					    array_push($aScriptTags, $scriptTag);
					    $this->cssDir($file, $minifierJs);
				    }
			    }

			    $fileName = 'wiloke_minify_js_'.trim(WilokeService::$aThemeInfo['slug']).'_'.trim(WilokeService::$aThemeInfo['version']);

			    if ( get_option('wiloke_minify_theme_js') && is_file(get_option('wiloke_minify_theme_js')) ){
				    unlink(get_option('wiloke_minify_theme_js'));
			    }
			    $minifierJs->minify($uploadDir.$fileName.'.css');
			    update_option('wiloke_minify_theme_css', $fileName.'.css');
			    update_option('wiloke_minify_list_of_original_css', $aScriptTags);
			    update_option('wiloke_minify_at_version', WilokeService::$aThemeInfo['version']);
		    }
	    }

	    protected function _compressWooCommerceCss(){

		    if ( is_dir(WP_PLUGIN_DIR . '/woocommerce') ){
			    include plugin_dir_path(__FILE__) . 'minify-master/vendor/autoload.php';

			    $uploadDir = wp_upload_dir();
			    $uploadDir = $uploadDir['basedir'].'/';
			    $minifierJs = new Minify\CSS();
			    if ( !empty($this->aWooCommerceCSS) ) {
				    foreach ( $this->aWooCommerceCSS as $scriptTag => $file ){
					    $file = trim(WP_PLUGIN_DIR . '/' . $file);
					    if ( is_file($file) ){
						    $this->cssDir($file, $minifierJs);
					    }
				    }

				    if ( get_option('wiloke_minify_woocommerce_css') && is_file(get_option('wiloke_minify_woocommerce_css')) ){
					    unlink(get_option('wiloke_minify_woocommerce_css'));
				    }
				    $minifierJs->minify($uploadDir.'wiloke_minify_woocommerce.css');
				    update_option('wiloke_minify_woocommerce_css', 'wiloke_minify_woocommerce.css');
			    }
		    }
	    }

		public function save_settings(){
			if ( check_ajax_referer('wiloke_minify_action', 'security', true) ){
				parse_str($_POST['data'], $aData);

				$this->toggleAdditionalScripts = isset($aData['wiloke_minify']['toggle_custom_scripts']) && !empty($aData['wiloke_minify']['toggle_custom_scripts']) ? true : false;

				if ( isset($aData['wiloke_minify']['javascript']) && (absint($aData['wiloke_minify']['javascript']) === 1) ){
					if ( $this->toggleAdditionalScripts ){
						$this->aCustomJS = $aData['wiloke_minify']['additional_js'];
					}
					$this->_proceedCompressJavaScript();
				}

				if ( isset($aData['wiloke_minify']['css']) && (absint($aData['wiloke_minify']['css']) === 1) ){
					if ( $this->toggleAdditionalScripts ){
						$this->aCustomCSS = $aData['wiloke_minify']['additional_css'];
					}
					$this->_compressWooCommerceCss();
					$this->_proceedCompressCss();
				}

				update_option('_wiloke_minify', $aData['wiloke_minify']);
				wp_send_json_success();
			}

			wp_send_json_error();
		}

		public function reCompressAfterUpgraded(){
			$aData = get_option('_wiloke_minify');
			if ( empty($aData) ){
				return false;
			}

			$this->toggleAdditionalScripts = isset($aData['toggle_custom_scripts']) && !empty($aData['toggle_custom_scripts']) ? true : false;

			if ( isset($aData['javascript']) && (absint($aData['javascript']) === 1) ){
				if ( $this->toggleAdditionalScripts ){
					$this->aCustomJS = isset($aData['additional_js']) ? $aData['additional_js'] : '';
				}
				$this->_proceedCompressJavaScript();
			}

			if ( isset($aData['css']) && (absint($aData['css']) === 1) ){
				if ( $this->toggleAdditionalScripts ){
					$this->aCustomCSS = isset($aData['additional_css']) ? $aData['additional_css'] : '';
				}
				$this->_proceedCompressCss();
			}

			$this->_compressWooCommerceCss();
		}

		public function getRealPath($path){
			if ( strpos($path, 'plugins') !== false ){
				$path = substr($path, strpos($path, '/plugins/'));
				$path = WP_PLUGIN_DIR . str_replace('plugins/', '', $path);
			}else{
				$path = substr($path, strpos($path, '/themes/'));
				$path = WP_CONTENT_DIR . '/themes' . str_replace('themes/', '', $path);
			}

			return $path;
		}
    }
}