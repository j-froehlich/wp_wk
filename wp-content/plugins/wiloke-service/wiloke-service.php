<?php
/**
Plugin Name: Wiloke Service
Plugin URI: http://wiloke.com
Author URI: wiloke
Author URI: http://wiloke.com
Description: Automatically Update Wiloke's Products
Version: 0.9.7
*/

if ( !defined('ABSPATH') )
{
    wp_die('You dont have permission to access to this page');
}

register_activation_hook( __FILE__, 'wiloke_service_deactivate_wiloke_import_plugin' );
function wiloke_service_deactivate_wiloke_import_plugin(){
	// deactivate_plugins( plugin_basename( 'amazon-s3-and-cloudfront/wordpress-s3.php' ) );
	// deactivate_plugins( plugin_basename( 'amazon-web-services/amazon-web-services.php' ) );
	deactivate_plugins( plugin_basename( 'wiloke-importer/class.WilokeImporter.php' ) );
}

require_once plugin_dir_path(__FILE__) . 'includes/class.WilokeService.php';
require_once plugin_dir_path(__FILE__) . 'admin/func.wiloke-products.php';
spl_autoload_register('WilokeService::wiloke_service_autoloader');

function wiloke_service_init()
{
    $init = WilokeService::instance();
    $init->run();
    return $init;
}

$GLOBALS['WilokeService'] = wiloke_service_init();
