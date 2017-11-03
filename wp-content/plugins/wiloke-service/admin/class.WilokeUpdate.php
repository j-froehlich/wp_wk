<?php
/**
 * Wiloke Update 
 * Automatically Update Theme and Plugins
 *
 * @link        https://wiloke.com
 * @since       1.0
 * @package     WilokeService
 * @subpackage  WilokeService/admin
 * @author      Wiloke 
 */

if ( !defined('ABSPATH') )
{
	wp_die( esc_html__('You do not permission to access to this page', 'wiloke-service') );
}

if ( !defined('WILOKE_DEBUG') || (defined('WILOKE_DEBUG') && !WILOKE_DEBUG) ) {
	error_reporting(0);
}

if ( !class_exists('WilokeUpdate') )
{
	class WilokeUpdate
	{
		/**
		 * Defines Wiloke's URLs
		 * @since 1.0
		 */
		public $wilokeAPIURL = 'http://wiloke.net/client-update/';
		public $wilokeStore  = 'http://wiloke.net/storage/';

		// private $wilokeAPIURL = 'http://localhost:8000/client-update';
		// private $wilokeStore  = 'http://localhost:8000/storage/';

		/**
		 * Define Wiloke Update Slug
		 * @since 1.0
		 */
		public $slug         = 'wiloke-update';

		/**
		 * Saving disable check by this key
		 * @since 1.0
		 */
		private $temporaryDisableCheck = '_wiloke_service_temporary_disable_check';

		/** 
		 * Put all themes and plugins into guys
		 * @since 1.0
		 */
		private $aThemes     = array();
		private $aPlugins    = array();

		/**
		 * We will request to server each seconds
		 * @since 1.0
		 */
		private  $cacheIterval = 3600;

		/**
		 * All plugins in this site
		 * @since 1.0
		 */
		private static $wp_plugins = array();

		public function refreshUpdate(){
			delete_transient($this->temporaryDisableCheck);
			delete_transient($this->get_option_name('wiloke_update_themes'));
			delete_transient($this->get_option_name('wiloke_update_plugins'));
		}

		public function get_option_name($key='')
		{
			return 'wiloke_tfp_'.$key;
		}

		/**
		 * Checking Plugin Update
		 * @since 0.1
		 */
		public function update_plugins($transient, $debug=false)
		{
			if ( isset($transient->checked) || $debug )
			{
				// send purchased code
				$this->set_plugins();
				
				self::$wp_plugins = self::wp_plugins();
				
				if ( empty($this->aPlugins) || is_wp_error($this->aPlugins) )
				{
					return false;
				}

				foreach ( $this->aPlugins as $plugin => $aInfo ) {
					if ( isset( self::$wp_plugins[$plugin] ) && version_compare( self::$wp_plugins[$plugin]['Version'], $aInfo['version'], '<' ) ) {
						$_plugin = array(
							'slug'        => $aInfo['slug'],
							'plugin'      => $plugin,
							'new_version' => $aInfo['version'],
							'url'         => 'https://wiloke.com',
							'package'     => $aInfo['package']
						);

						$transient->response[$plugin] = (object)$_plugin;
						set_transient(WilokeService::$hasUpdateKey, true, 86400);
					}
				}
			}

			return $transient;
		}

		/**
		 * Checking Theme Update
		 * @since 0.1
		 */
		public function update_themes($transient, $isDebug = true)
		{
			// Process premium theme updates.
			if ( isset( $transient->checked ) || $isDebug = true ) {
				
				$this->set_plugins();
				
				if ( is_wp_error($this->aThemes) || empty($this->aThemes) )
				{
					return false;
				}
				
				foreach ( $this->aThemes as $slug => $premium ) {
					$theme = wp_get_theme( $slug );
				
					if ( $theme->exists() && version_compare( $theme->get( 'Version' ), $premium['version'], '<' ) ) 
					{
						$transient->response[ $slug ] = array(
							'theme'       => $slug,
							'new_version' => $premium['version'],
							'url'         => $premium['url'],
							'package'     => $premium['package']
						);
                        set_transient(WilokeService::$hasUpdateKey, true, 86400);
					}
				}

			}

			return $transient;
		}

		/**
		 * Get the list of WordPress plugins
		 *
		 * @since 1.0.0
		 * @param bool $flush Forces a cache flush. Default is 'false'.
		 * @return array
		 */
		public static function wp_plugins( $flush = false ) {
			if ( empty( self::$wp_plugins ) || true === $flush ) {
				wp_cache_flush();
				if ( ! function_exists( 'get_plugins' ) ) {
					require_once ABSPATH . 'wp-admin/includes/plugin.php';
				}
				self::$wp_plugins = get_plugins();
			}

			return self::$wp_plugins;
		}

		/**
		 * Get Plugins belongs to the theme
		 *
		 * @since 0.1
		 */
		public function set_plugins() {
			$this->_set_plugins();
		}
		
		protected function _set_plugins()
		{
			if ( empty(WilokeService::$wilokeApiKey) || empty(WilokeService::$aThemeInfo) || ( WilokeService::$aThemeInfo == 'notwiloke' ) || is_wp_error(WilokeService::$aThemeInfo) || get_transient($this->temporaryDisableCheck) )
			{
				return false;
			}

			$this->aPlugins = get_transient($this->get_option_name('wiloke_update_plugins'));
			$oInfo = false;
			$this->aThemes  = get_transient($this->get_option_name('wiloke_update_themes'));

			if ( (false === $this->aPlugins) ||  ( false === $this->aThemes ) )
			{
				$oInfo  = WilokeService::request($this->wilokeAPIURL);
				if ( ( is_string($oInfo) && $oInfo === 'temporary_disable_check' ) || is_wp_error($oInfo) ) {
					set_transient($this->temporaryDisableCheck, true, $this->cacheIterval);
					return false;
				}else{
            		delete_transient($this->temporaryDisableCheck);
				}
			}

			if ( is_wp_error($oInfo) || empty($oInfo) )
			{
				set_transient($this->temporaryDisableCheck, true, $this->cacheIterval);
				return false;
			}

			if ( false === $this->aPlugins )
			{
				$oPlugins = isset($oInfo->plugins) ? $oInfo->plugins : '';
				
				if ( !empty($oPlugins) )
				{
					foreach ( $oPlugins as $oPlugin )
					{
						$aPlugin['slug']    =  $oPlugin->slug;
						$aPlugin['plugin']  =  $oPlugin->name;
						$aPlugin['version'] =  $oPlugin->version;
						$aPlugin['url'] 	 =  $this->wilokeStore;
						$aPlugin['package'] =  WilokeService::$awsUrl . 'plugins/' . $oPlugin->slug . '.zip';
						$aPlugin['description'] =  $oPlugin->description;
						$this->aPlugins[$oPlugin->slug . '/' . $oPlugin->file_init] = $aPlugin;
					}

					delete_transient($this->get_option_name('wiloke_update_plugins'));
					set_transient($this->get_option_name('wiloke_update_plugins'), $this->aPlugins, $this->cacheIterval);
                    update_option('wiloke_plugins_changelog', $this->aPlugins);
				}else{
					set_transient($this->temporaryDisableCheck, true, $this->cacheIterval);
				}
			}

			if ( false ===  $this->aThemes ) {
				$oThemes  = isset($oInfo->themes) ? $oInfo->themes : '';

				if ( !empty($oThemes) )
				{
					foreach ( $oThemes as $oTheme )
					{
						$this->aThemes[$oTheme->slug]['slug']        =  $oTheme->slug;
						$this->aThemes[$oTheme->slug]['version']     =  $oTheme->version;
						$this->aThemes[$oTheme->slug]['url'] 	     =  'https://wiloke.com';
						$this->aThemes[$oTheme->slug]['package']     =   WilokeService::$awsUrl . 'themes/' . $oTheme->slug . '.zip';
						$this->aThemes[$oTheme->slug]['description'] =  $oTheme->description;
					}

					delete_transient($this->get_option_name('wiloke_update_themes'));
					set_transient($this->get_option_name('wiloke_update_themes'), $this->aThemes, $this->cacheIterval);
					update_option('wiloke_themes_changelog', $this->aThemes);
				}else{
					set_transient($this->temporaryDisableCheck, true, $this->cacheIterval);
				}
			}

            set_transient($this->temporaryDisableCheck, true, $this->cacheIterval);
		}

		/**
		 * Disables requests to the wp.org repository for premium themes.
		 *
		 * @since 1.0.0
		 *
		 * @param array  $request An array of HTTP request arguments.
		 * @param string $url The request URL.
		 * @return array
		 */
		public function update_check( $request, $url ) 
		{
			// Theme update request.
			if ( false !== strpos( $url, '//api.wordpress.org/themes/update-check/1.1/' ) ) {

				/**
				 * Excluded theme slugs that should never ping the WordPress API.
				 * We don't need the extra http requests for themes we know are premium.
				 */
				$this->set_plugins();

				if ( empty($this->aThemes)  || is_wp_error($this->aThemes) )
				{
					return array();
				}
				
				// Decode JSON so we can manipulate the array.
				$data = json_decode( $request['body']['themes'] );

				// Remove the excluded themes.
				foreach ( $this->aThemes as $slug => $id ) {
					unset( $data->themes->$slug );
				}

				// Encode back into JSON and update the response.
				$request['body']['themes'] = wp_json_encode( $data );
			}

			if ( false !== strpos( $url, '//api.wordpress.org/plugins/update-check/1.1/' ) ) {

				/**
				 * Excluded theme slugs that should never ping the WordPress API.
				 * We don't need the extra http requests for themes we know are premium.
				 */
				
				if ( empty($this->aPlugins) || is_wp_error($this->aPlugins) )
				{
					$this->set_plugins();

					if ( empty($this->aPlugins) || is_wp_error($this->aPlugins) )
					{
						return array();
					}
				}
				
				// Decode JSON so we can manipulate the array.
				$data = json_decode( $request['body']['plugins'] );

				// Remove the excluded themes.
				foreach ( $this->aPlugins as $slug => $id ) {
					if ( isset($data->plugins->$slug) )
					{
						unset( $data->plugins->$slug );
					}
				}

				// Encode back into JSON and update the response.
				$request['body']['plugins'] = wp_json_encode( $data );
			}

			return $request;
		}

		/**
		 * @deprecated 0.2
		 */
		public function register_submenu()
		{
			add_submenu_page( WilokeService::$aSlug['main'], esc_html__('Automatic Update', 'wiloke-service'), esc_html__('Automatic Update', 'wiloke-service'), 'edit_theme_options', WilokeService::$aSlug['update'], array($this, 'update_area'));
		}

		public function update_area()
		{
			$this->update_plugins('', true);
			if ( isset($_POST['wiloke-update-action']) && !empty($_POST['wiloke-update-action']) )
			{
				if ( wp_verify_nonce($_POST['wiloke-update-action'], 'wiloke-update-nonce') )
				{
					if ( isset($_POST['wiloke_update']['secret_token']) )
					{
						$secretToken = filter_var($_POST['wiloke_update']['secret_token'], FILTER_SANITIZE_STRING);
						update_option('_wiloke_service_client_token', $secretToken);
					}
				}
			}else{
				$secretToken = get_option('_wiloke_service_client_token');
			}

			include plugin_dir_path( dirname(__FILE__) ) . 'admin/html/information.php';
		}
	}
}

