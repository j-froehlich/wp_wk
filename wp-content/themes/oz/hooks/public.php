<?php
//Header
global $wiloke;
$this->_loader->add_action('wp_head', $this->_public, 'wp_head', 10);
$this->_loader->add_filter('wp_title', $this->_public, 'wp_title', 10);
$this->_loader->add_filter('wp_nav_menu', $this->_public, 'append_items_to_menu', 10, 2);
$this->_loader->add_filter('wiloke/oz/header/css_class', $this->_public, 'filter_class_of_header_tag', 10, 2);

// General
$this->_loader->add_action('wiloke/oz/after_header', $this->_public, 'breadcrumb');
add_action('wiloke/oz/breadcrumb', 'woocommerce_breadcrumb', 20);
//$this->_loader->add_action('wiloke/oz/after_header', $this->_public, 'product_mini_cart');
$this->_loader->add_action('wiloke/oz/inside_breadcrumb', $this->_public, 'product_mini_cart');
$this->_loader->add_action('wiloke/oz/before_wil_wrapper', $this->_public, 'render_preloader');
$this->_loader->add_action('wiloke/oz/set_header_animation', $this->_public, 'set_header_animation');
$this->_loader->add_action('wiloke/oz/inside_information_box', $this->_public, 'render_header_information_box');
$this->_loader->add_action('wiloke/oz/menu_place', $this->_public, 'render_menu');
$this->_loader->add_action('wiloke_filter_social_network', $this->_public, 'wiloke_filter_social_network');


// Portfolio
$this->_loader->add_action('wiloke/wiloke_shortcode_design_portfolio', $this->_public, 'oz_query_portfolio', 10, 4);
$this->_loader->add_action('wiloke/wiloke_shortcode_design_portfolio/after_design_layout', $this->_public, 'render_loadmore_btn', 10, 3);
$this->_loader->add_action('wiloke/wiloke_shortcode_design_portfolio/inside_work_parallax', $this->_public, 'render_portfolio_parallax_scrolling', 10);

// Project
$this->_loader->add_action('wiloke/oz/before_header', $this->_public, 'render_project_background');

$this->_loader->add_action('wiloke/oz/single-portfolio/project-header', $this->_public, 'render_project_title', 10);
$this->_loader->add_action('wiloke/oz/single-portfolio/project-header', $this->_public, 'render_project_info_open', 15);
$this->_loader->add_action('wiloke/oz/single-portfolio/project-header', $this->_public, 'render_project_info', 15, 2);
$this->_loader->add_action('wiloke/oz/single-portfolio/project-header', $this->_public, 'render_project_info_close', 15);
$this->_loader->add_action('wiloke/oz/single-portfolio/project-header', $this->_public, 'render_project_info_close', 15);

$this->_loader->add_action('wiloke/oz/single-portfolio/before-main-content', $this->_public, 'render_project_info_before_content_open', 10, 2);
$this->_loader->add_action('wiloke/oz/single-portfolio/before-main-content', $this->_public, 'render_project_info_before_content_close', 20, 2);
$this->_loader->add_action('wiloke/oz/single-portfolio/before-main-content', $this->_public, 'render_project_intro', 15, 3);
$this->_loader->add_action('wiloke/oz/single-portfolio/before-main-content', $this->_public, 'render_project_main_content_open', 20, 3);
$this->_loader->add_action('wiloke/oz/single-portfolio/after-main-content', $this->_public, 'render_sharing_box', 10, 3);
$this->_loader->add_action('wiloke/oz/single-portfolio/after-main-content', $this->_public, 'render_project_main_content_close', 10, 3);

$this->_loader->add_action('wiloke/oz/single-portfolio/after-body', $this->_public, 'render_project_pagination', 10);
$this->_loader->add_action('wiloke/oz/single-portfolio/ajax', $this->_public, 'render_popup_project');

// Loadmore project
$this->_loader->add_filter('wiloke_ajax_load_posts_input_args', $this->_public, 'filter_args_before_query_projects_in_ajax', 10, 1);
$this->_loader->add_filter('wiloke_ajax_filter_query_portfolio', $this->_public, 'loadmore_project', 10, 4);

// Blog layout
$this->_loader->add_filter('wiloke/oz/article/after_main_content', $this->_public, 'render_related_articles');
$this->_loader->add_filter('wiloke_oz_filter_main_wrapper_class', $this->_public, 'modify_wrapper_class_on_single');
$this->_loader->add_filter('wiloke_sharing_post_css_class', $this->_public, 'replace_the_default_sharing_wrapper_class');
$this->_loader->add_action('wiloke_sharing_post_filter_social_item', $this->_public, 'restructure_sharing_social_item');
$this->_loader->add_filter('comment_form_fields',  $this->_public, 'solve_stupid_idea_of_wordpressdotcom');
// Footer
$this->_loader->add_action('wiloke/oz/footer_section', $this->_public, 'render_before_footer_widget');
$this->_loader->add_action('wiloke/oz/footer_section', $this->_public, 'render_rest_of_footer');
$this->_loader->add_action('wiloke/oz/footer_section', $this->_public, 'render_popup_subscribe');

// WooCommerce
remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);
remove_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10);
remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);
remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);
remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10);
remove_action('woocommerce_shop_loop_subcategory_title', 'woocommerce_template_loop_category_title', 10);
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
remove_action('woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15);
remove_action('woocommerce_cart_collaterals', 'woocommerce_cross_sell_display');
add_action('woocommerce_after_cart', 'woocommerce_cross_sell_display');

$this->_loader->add_action('wp_enqueue_scripts', $this->_public, 'remove_some_woocommerce_scripts', 99);

$this->_loader->add_action('woocommerce_after_single_product', $this->_public, 'product_related_products', 10);
$this->_loader->add_action('woocommerce_after_single_product', $this->_public, 'product_upsell_display', 5);

remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
$this->_loader->add_action('woocommerce_before_main_content', $this->_public, 'product_section', 30);
$this->_loader->add_action('wp_ajax_render_total_cart', $this->_public, 'product_render_total_cart');
$this->_loader->add_action('wp_ajax_nopriv_render_total_cart', $this->_public, 'product_render_total_cart');

$this->_loader->add_action('woocommerce_before_shop_loop_item_title', $this->_public, 'product_header', 5);
//$this->_loader->add_action('woocommerce_before_shop_loop_item_title', $this->_public, 'product_add_to_cart', 5);
$this->_loader->add_action('woocommerce_after_shop_loop_item_title', $this->_public, 'product_out_of_stock', 5);
$this->_loader->add_action('woocommerce_after_shop_loop_item_title', $this->_public, 'product_header_close');
$this->_loader->add_action('woocommerce_after_shop_loop_item', $this->_public, 'product_before_info');
$this->_loader->add_action('woocommerce_after_shop_loop_item', $this->_public, 'product_info');
$this->_loader->add_action('woocommerce_after_shop_loop_item', $this->_public, 'product_before_info_close');
$this->_loader->add_filter('woocommerce_shortcode_products_query', $this->_public, 'product_tweak_shop_query', 10, 3);
$this->_loader->add_action('woocommerce_shortcode_after_recent_products_loop', $this->_public, 'product_tweak_pagination_for_woocommerce_sc');
$this->_loader->add_action('woocommerce_after_main_content',  $this->_public, 'product_section_close', 5);

$this->_loader->add_action('woocommerce_before_subcategory', $this->_public, 'woocommerce_product_cat_open', 5);
$this->_loader->add_action('woocommerce_after_subcategory', $this->_public, 'woocommerce_product_header_cat_close', 15);
$this->_loader->add_action('woocommerce_after_subcategory', $this->_public, 'woocommerce_product_cat_title', 20);

//single
$this->_loader->add_action('woocommerce_single_product_image_html', $this->_public, 'product_single_filter_image_html');
$this->_loader->add_action('wiloke/oz/page/main_content_wrapper', $this->_public, 'product_add_fullwidth_to_woocommercepage');
$this->_loader->add_filter( 'loop_shop_per_page', $this->_public, 'product_filter_posts_per_page' );

// Other
$this->_loader->add_filter('wiloke_sharing_post_show_on', $this->_public, 'reset_sharing_post_social_show_on', 10, 1);
$this->_loader->add_filter('wiloke_sharing_post_title', $this->_public, 'sharing_post_title', 10, 1);
$this->_loader->add_filter('wiloke_sharing_posts', $this->_public, 'check_conditional_to_sharing_post', 10, 1);
$this->_loader->add_filter('post_gallery', $this->_public, 'filter_post_gallery', 10, 2);
