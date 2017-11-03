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

		public function init(){
			$this->aStatus = get_option('_wiloke_minify');
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
							wp_dequeue_script($scriptTag);
						}
					}

					wp_enqueue_script('wiloke_minify_theme_js', $uploadUrl . $jsFileName, array('jquery'), null, true);
				}

				if (  isset($this->aStatus['css']) && !empty($this->aStatus['css']) ) {
					$cssFileName = get_option('wiloke_minify_theme_css');
					$this->aListOfCss = get_option('wiloke_minify_list_of_original_css');
					if ( !empty($this->aListOfCss) ){
						foreach ($this->aListOfCss as $cssTag){
							wp_dequeue_style($cssTag);
						}
					}
					wp_enqueue_style('wiloke_minify_theme_css', $uploadUrl . $cssFileName);
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
			$aData = wp_parse_args($aData, array('css'=>0, 'javascript'=>0));
	    	include plugin_dir_path(__FILE__) . 'html/minify/settings.php';
	    }

	    protected function _getScriptFromWPFolder($folder, $scriptName){
			$dir = ABSPATH . 'wp-includes/' . $folder . '/' . $scriptName . '.min.'.$folder;
			if ( is_file($dir) ){
				return $dir;
			}
			return '';
	    }

	    protected function _getScriptFromLibFolder($folder, $scriptName){
			$dir = get_template_directory() . '/' . $folder . '/lib/';
			if ( is_file($dir.$scriptName.$folder) ){
				return $dir.$scriptName.$folder;
			}
			return '';
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
		    return '';
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

		public function save_settings(){
			if ( check_ajax_referer('wiloke_minify_action', 'security', true) ){
				parse_str($_POST['data'], $aData);

				if ( isset($aData['wiloke_minify']['javascript']) && (absint($aData['wiloke_minify']['javascript']) === 1) ){
					$this->_proceedCompressJavaScript();
				}

				if ( isset($aData['wiloke_minify']['css']) && (absint($aData['wiloke_minify']['css']) === 1) ){
					$this->_proceedCompressCss();
				}

				update_option('_wiloke_minify', $aData['wiloke_minify']);
				wp_send_json_success();
			}

			wp_send_json_error();
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