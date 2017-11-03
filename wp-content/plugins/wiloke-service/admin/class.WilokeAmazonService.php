<?php
/**
 * Wiloke Amazone S3 Service
 * Migrate your images to Amazone cloud
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

class WilokeAmazonService
{
    public function __construct()
    {
    	if ( class_exists('AWS_Compatibility_Check') ){
    		return false;
    	}
    	
        include plugin_dir_path(__FILE__) . 'amazon-web-services/amazon-web-services.php';
        include plugin_dir_path(__FILE__) . 'amazon-s3-and-cloudfront/wordpress-s3.php';
    }
}