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

class WilokeImport
{
	/**
     * Update End point
     * @since 0.2
     */
    // private $wilokeAPIURL = 'http://localhost:8000/demo/request';
    private $wilokeAPIURL = 'https://wiloke.net/demo/request';

    /**
     * VC Portfolio Demos End Point
     * @since 0.3
     */
    private $_wilokePortfolioDemoURL = 'https://wiloke.net/vc-portfolio-demos/request';

    /**
     * Transient Store
     * @since 0.2
     */
    private $transientDemo = 'wiloke_demos';

    /**
     * Transient VC Portfolio
     * @since 0.2
     */
    public static $wilokePortfolioDesignStore = 'wiloke_design_portfolio_demo';

    /**
     * Wiloke Design Portfolio Prefix
     * @since 0.2
     */
    public static $designPortfolioPrefix = 'wiloke_design_portfolio_';

    /**
     * Export zone
     * @since 0.3
     */
    protected $_exportTo;

    protected $_wilokeVCPortfolioDemo;

    /**
     * Demo Store
     * @since 0.2
     */
    public $oDemos;

    /**
     * Wiloke Portfolio Demos
     * @since 0.2
     */
    public $oWilokePortfolioDemos;

    public function __construct()
    {
        $this->_exportTo = plugin_dir_path(__FILE__) . 'export-demo/';
    }

    /**
     * Refresh Demo Data
     * @since 0.2
     */
    public function request_demo_data(){
        if ( !check_ajax_referer(WilokeService::$nonceKey, 'security', false) || empty($_POST['type']) ){
            wp_send_json_error();
        }

        delete_transient($this->transientDemo);
        
        $this->request();

        if ( $_POST['type'] == 'demo' ) {
            include plugin_dir_path( dirname(__FILE__) ) . 'admin/html/import/demo-item.php';
        }elseif ( $_POST['type'] == 'template' ) {
            $oTemplateDemos = $this->filter_template_demo();
            if ( !empty($oTemplateDemos) ) {
                include plugin_dir_path( dirname(__FILE__) ) . 'admin/html/import/template-item.php';
            }
        }

        wp_die();
    }

    public function importXML($aFiles, $isAttachment=true){
	    if ( !class_exists('WP_Import') ){
		    include plugin_dir_path(__FILE__) . 'wordpress-importer/wordpress-importer.php';
	    }

	    $oWPImport = new WP_Import;
	    $message = '';

	    if ( !empty($aFiles) ) {
		    foreach ( $aFiles as $file )
		    {
			    ob_start();
			    $oWPImport->fetch_attachments = $isAttachment;
			    $oWPImport->import($file);
			    $res = ob_get_contents();
			    ob_clean();

			    if ( $res == 404 ) {
				    $message .= sprintf( esc_html__('We could not import %s', 'wiloke-service'), $res );
			    }else{
				    $message .= sprintf(  esc_html__('%s has been imported', 'wiloke-service'), basename($file) );
				    $message .= '<br />';

				    if ( strpos($aFiles, 'wiloke-service') !== false ){
					    wp_delete_file($file);
                    }

			    }
		    }

		    wp_send_json_success(array('message'=>$message, 'keep_alive'=>true));
	    }
    }

	/**
	 * Retrieve the download URL for a WP repo package.
	 *
	 * @since 2.5.0
	 *
	 * @param string $slug Plugin slug.
	 * @return string Plugin download URL.
	 */
	protected function unZipFile($package, $isLive=false){
		WP_Filesystem();
		$status = unzip_file( $package,  plugin_dir_path(__FILE__) . 'export-demo/');
		if ( $isLive ){
			@unlink($package);
		}
		if ( $status ){
			return true;
		}

		return false;
	}

	protected function downloadDemo($downloadLink){
		$package = download_url( $downloadLink, 18000 );
		if (is_wp_error($package)){
			return false;
		}

		return $this->unZipFile($package, true);
    }

    /**
     * Importing
     * @since 0.2
     */
    public function import_demo(){
        if ( !check_ajax_referer(WilokeService::$nonceKey, 'security', false) || empty($_POST['settings']) ){
            wp_send_json_error();
        }

        parse_str($_POST['settings'], $aSettings);

        if ( !isset($aSettings['file']) || empty($aSettings['file']) ){
            wp_send_json_error();
        }

        $fileName = urldecode($aSettings['file']);
        $demoUrl = WilokeService::$awsUrl . $fileName;

        $isContinueImport = isset($_POST['keep_alive']) && $_POST['keep_alive'] ? true : false;
        $isAttachment     = isset($aSettings['attachment']) && $aSettings['attachment'] == 'yes' ? true : false;
        
        $target  = plugin_dir_path(__FILE__) . 'export-demo/';

        $folder = str_replace('.zip', '', basename($fileName));

        if ( isset($aSettings['demodata']) && ($aSettings['demodata'] === 'yes') && !isset($_POST['isDownloadedDemos']) ){
	        $aXml = glob($target . '*.xml');

            if (empty($aXml)){
	            $downloadStatus = $this->downloadDemo($demoUrl);
	            if ( !$downloadStatus ){
		            wp_send_json_error(array('message'=>esc_html__('Our hosting provider do not allow import the demo from our server, please contact '.WilokeService::$emailContact.' to solve this issue.', 'wiloke-service'), 'keep_alive'=>true));
                }
            }

	        wp_send_json_success(array( 'message' => esc_html__('The demo data has been downloaded from our server. The import process is starting! ', 'wiloke-service'), 'keep_alive'=>true, 'start_importing_demos'=>true));
        }

        if ( isset($_POST['start_importing_demos']) ){
	        $aXml = glob($target . '*.xml');
	        $this->importXML($aXml);
        }

        if ( isset($aSettings['themeoptions']) && ($aSettings['themeoptions'] === 'yes') ){
            $optionKey = 'wiloke_options';
            
            if ( is_file(get_template_directory() . '/demos/'.$folder.'/wiloke.json') ){
                $content = file_get_contents(get_template_directory() . '/demos/'.$folder.'/wiloke.json');
                $aConfig = json_decode($content, true);
                $optionKey = $aConfig['themeoptions']['key'];
                $target = get_template_directory() . '/demos/';
            }elseif ( is_file($target . 'wiloke.json') ){
	            $content = file_get_contents($target . 'wiloke.json');
	            $aConfig = json_decode($content, true);
	            $optionKey = $aConfig['themeoptions']['key'];
            }

            if ( is_file($target . 'themeoptions.json') ){
	            $aOptionValue = file_get_contents($target . 'themeoptions.json');
	            update_option($optionKey, $aOptionValue);
            }

            if ( isset($aConfig['slug']) && !empty($aConfig['slug']) ) {
                $args = array(
                    'name'        => $aConfig['slug'],
                    'post_type'   => 'page',
                    'post_status' => 'publish',
                    'numberposts' => 1
                );
                $oPosts = get_posts($args);
                if( $oPosts ){
                    update_option('show_on_front', 'page');
                    update_option('page_on_front', $oPosts[0]->ID);
                }
            }
            wp_delete_file($target.'themeoptions.json');
            wp_delete_file($target.'wiloke.json');
            wp_send_json_success(array('message'=>esc_html__('Theme Options has been updated', 'wiloke-service'), 'keep_alive'=>false));
        }

        $this->cleanExportFolder($target);
//
        wp_send_json_success(array('message'=>esc_html__('The demo has been imported successfully!'), 'keep_alive'=>false));
    }

    /**
     * Import Template
     * @since 0.2
     */
    public function import_template(){
        if ( !check_ajax_referer(WilokeService::$nonceKey, 'security', false) || empty($_POST['file']) ){
            wp_send_json_error();
        }

        $file = urldecode($_POST['file']);
        $folder = str_replace('.zip', '', basename($file));

        if ( !$this->extractFile($file) ) {
            wp_send_json_error();
        }

        if ( !is_file($this->_exportTo . $folder . '/shortcode.php') ) {
            wp_send_json_error();
        }

        $content = file_get_contents($this->_exportTo . $folder . '/shortcode.php');
        $this->cleanExportFolder($this->_exportTo);
        wp_send_json_success(array('shortcode'=>$content));
    }

    /**
     * Downloading file from s3 to your WordPress folder
     * @since 0.3
     */
    protected function extractFile($file){
        $file = WilokeService::$awsUrl . $file;

        if ( !copy($file, $this->_exportTo . 'demo.zip') ) {
            if ( chmod($this->_exportTo, 755) ) {
                copy($file, $this->_exportTo . 'demo.zip');
            }else{
                wp_send_json_error();
            }
        }

        $zip    = new ZipArchive();
        $zip->open($this->_exportTo . 'demo.zip');
        $zip->extractTo($this->_exportTo);
        $zip->close();
        unlink($file);
        $zip->close();

        return true;
    }

    public function cleanExportFolder($folder){
        foreach(glob($folder.'*') as $file)
        {
           $this->deleteFile($file);
        }
    }

    public function deleteFile($dir){
        if (!file_exists($dir)){
            return true;
        }

        if (!is_dir($dir)){
           return unlink($dir);
        }

        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..'){
                continue;
            }

            if (!$this->deleteFile($dir.DIRECTORY_SEPARATOR.$item)){
                return false;
            }
        }

        return rmdir($dir);
    }

    public function deleteExportFolder(){
        
    }

    public function generate_key(){
        if ( !isset(WilokeService::$aThemeInfo['name']) ) {
            WilokeService::get_theme_info(true);
        }
        $this->transientDemo = '_wiloke_demo_' . WilokeService::handle_text(WilokeService::$aThemeInfo['name']);
    }

	public function register_scripts($hook)
	{
		wp_enqueue_style('wiloke-import', plugin_dir_url(dirname(__FILE__)) . 'source/css/import.css');

        wp_enqueue_style('wiloke-import-template', plugin_dir_url(dirname(__FILE__)) . 'source/css/import-template.css');
        wp_enqueue_script('base64', plugin_dir_url(dirname(__FILE__)) . 'assets/base64/base64.js', array('jquery'), null, true);
        wp_enqueue_script('wiloke-import-template', plugin_dir_url(dirname(__FILE__)) . 'source/js/import-template.js', array('jquery'), null, true);
	}

    public function admin_footer(){
        $oTemplateDemos = $this->filter_template_demo();
        if ( !empty($oTemplateDemos) ) {
            include plugin_dir_path(dirname(__FILE__)) . 'admin/html/import/template.php';
        }
    }

    public function filter_template_demo(){
        $this->request();

        if (!$this->oDemos || is_wp_error($this->oDemos) || ( isset($this->oDemos->errors) && !empty($this->oDemos->errors) ) || ( is_string($this->oDemos) && $this->oDemos == 'temporary_disable_check' )
        ) {
            return false;
        }
        $oTemplateDemos = array_filter((array)$this->oDemos, function($oDemo){
            if ( isset($oDemo->type) && ($oDemo->type == 'template') ){
                return true;
            }
            return false;
        });

        return $oTemplateDemos;
    }

    public function register_menu()
    {
        add_submenu_page( WilokeService::$aSlug['main'], esc_html__('Import', 'wiloke-service'), esc_html__('Import', 'wiloke-service'), 'edit_theme_options', WilokeService::$aSlug['import'], array($this, 'settings'));
    }

    public function settings(){
        $this->request();
        include plugin_dir_path(dirname(__FILE__)) . 'admin/html/import/demo.php';
    }

    public function request(){
        if ( !$this->oDemos = get_transient($this->transientDemo) )
        {
            $this->oDemos = WilokeService::request($this->wilokeAPIURL);
            if ( !empty($this->oDemos) && !is_wp_error($this->oDemos) ) {
                set_transient($this->transientDemo, json_encode($this->oDemos), 86400);
            }else{
                $this->oDemos = false;
                delete_transient($this->transientDemo);
            }
        }else{
            $this->oDemos = json_decode($this->oDemos);
        }
    }

    public function wiloke_service_request_vc_portfolio(){
        if ( !check_ajax_referer(WilokeService::$nonceKey, 'security', false) ) {
            wp_send_json_error();
        }
        
        $this->_wilokeVCPortfolioDemo = get_transient(self::$wilokePortfolioDesignStore);

        if ( !$this->_wilokeVCPortfolioDemo || empty($this->_wilokeVCPortfolioDemo) )
        {
            $this->_wilokeVCPortfolioDemo = WilokeService::request($this->_wilokePortfolioDemoURL);

            if ( !empty($this->_wilokeVCPortfolioDemo) && !is_wp_error($this->_wilokeVCPortfolioDemo) ) {
                if ( $this->_wilokeVCPortfolioDemo == 'temporary_disable_check' ) {
                    delete_transient(self::$wilokePortfolioDesignStore);
                    wp_send_json_error();
                }else{
                    $this->_wilokeVCPortfolioDemo = json_encode($this->_wilokeVCPortfolioDemo);
                    set_transient(self::$wilokePortfolioDesignStore, $this->_wilokeVCPortfolioDemo, 86400);
                }
            }else{
                delete_transient(self::$wilokePortfolioDesignStore);
                wp_send_json_error();
            }
        }

        $this->_wilokeVCPortfolioDemo = json_decode($this->_wilokeVCPortfolioDemo);

        ob_start();
        foreach ( $this->_wilokeVCPortfolioDemo as $oDemo ) :
        ?>
            <label class="item wiloke-service">
                <input name="wiloke_design_portfolio_choose_layout[value]" type="hidden" class="wo_portfolio_layout_settings" value="<?php echo esc_url(WilokeService::$awsUrl.$oDemo->file); ?>" />
                <input name="wiloke_design_portfolio_choose_layout[layout]" class="wo_portfolio_layout_value" type="radio" value="<?php echo esc_attr($oDemo->name); ?>" />
                <img src="<?php echo esc_url(WilokeService::$awsUrl.$oDemo->screenshot); ?>" alt="<?php echo esc_attr($oDemo->name); ?>" />
                <?php if ( !empty($oDemo->url) ) : ?>
                    <a href="<?php echo esc_url($oDemo->url); ?>"><span style="text-align:center; font-weight: bold;"><?php echo esc_attr($oDemo->name); ?></span></a>
                <?php else : ?>
                    <span style="text-align:center; font-weight: bold;"><?php echo esc_attr($oDemo->name); ?></span>
                <?php endif; ?>

            </label>
        <?php
        endforeach;
        $content = ob_get_clean();

        wp_send_json_success($content);

        wp_die();
    }

    public function wiloke_service_import_vc_portfolio(){
        if ( !check_ajax_referer(WilokeService::$nonceKey, 'security', false) ) {
            wp_send_json_error();
        }

        $supFix = preg_replace_callback('/\s+/', function(){
            return '';
        }, $_POST['name']);
        $supFix = strtolower($supFix);

        $aCaching = get_transient(self::$designPortfolioPrefix.$supFix);

        if ( empty($_POST['url']) ) {
            wp_send_json_error();
        }

        if ( !$aCaching ) {

            if ( !$this->extractFile($_POST['url']) ) {
                wp_send_json_error();
            }

            if (!$content = file_get_contents($this->_exportTo . 'wiloke.json')){
                wp_send_json_error();
            }

            $content = json_decode($content);
            set_transient(self::$designPortfolioPrefix.$supFix, $content->value, 604800);
            $aCaching = $content->value;
        }

        wp_send_json_success($aCaching);
    }
}