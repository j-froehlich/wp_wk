<?php
if ( !function_exists('wiloke_list_themes') )
{
	add_action('admin_menu', 'wiloke_list_themes', 30);
	add_action('admin_enqueue_scripts', 'wiloke_for_style_products');
	function wiloke_list_themes()
	{
		add_submenu_page( WilokeService::$aSlug['main'], esc_html__('Our Products', 'wiloke-service'), esc_html__('Our Products', 'wiloke-service'), 'edit_theme_options', 'wiloke-themes', 'wiloke_theme_showcase');
	}

	function wiloke_theme_showcase()
	{
		include plugin_dir_path(__FILE__) . 'html/wiloke-product-item.php';
	}

	function wiloke_for_style_products()
	{
		if ( isset($_GET['page']) && $_GET['page'] == 'wiloke-themes' ){
			wp_enqueue_style('wiloke-themes', plugin_dir_url(dirname(__FILE__)) . 'source/css/wiloke-themes-listing.css');
		}

		wp_enqueue_style('wiloke-product', plugin_dir_url(dirname(__FILE__)) . 'source/css/wiloke-product.css');
		wp_enqueue_script('wiloke-product', plugin_dir_url(dirname(__FILE__)) . 'source/js/wiloke-product.js', array('jquery'), null, true);	
	}
}