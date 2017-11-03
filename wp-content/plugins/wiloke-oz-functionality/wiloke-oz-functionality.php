<?php
/**
 * Plugin Name: Wiloke OZ Functionality
 * Plugin URI: https://wiloke.com
 * Author: Wiloke
 * Author URI: https://wiloke.com
 * Version: 1.0  
 * Text Domain: wiloke
 * Description: This guy is a part of OZ theme. Portfolio post type, widget, twitter, mailchimp and lots of other features were created by him
 */

add_action( 'init', 'wiloke_oz_functionality_load_textdomain' );
/**
 * Load plugin textdomain.
 *
 * @since 1.0.0
 */
function wiloke_oz_functionality_load_textdomain() {
    load_plugin_textdomain( 'wiloke', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

/**
 * Register Photo Post Type
 * @since 1.0
 */
if ( !function_exists('wiloke_oz_register_posttype') )
{
    add_action('init', 'wiloke_oz_register_posttype');

    function wiloke_oz_register_posttype()
    {
        $labels = array(
            'name'               => _x( 'Portfolios', 'post type general name', 'wiloke' ),
            'singular_name'      => _x( 'Project', 'post type singular name', 'wiloke' ),
            'menu_name'          => _x( 'Portfolios', 'admin menu', 'wiloke' ),
            'name_admin_bar'     => _x( 'Project', 'add new on admin bar', 'wiloke' ),
            'add_new'            => _x( 'Add New', 'book', 'wiloke' ),
            'add_new_item'       => esc_html__( 'Add New Project', 'wiloke' ),
            'new_item'           => esc_html__( 'New Project', 'wiloke' ),
            'edit_item'          => esc_html__( 'Edit Project', 'wiloke' ),
            'view_item'          => esc_html__( 'View Project', 'wiloke' ),
            'all_items'          => esc_html__( 'All Portfolios', 'wiloke' ),
            'search_items'       => esc_html__( 'Search Portfolios', 'wiloke' ),
            'parent_item_colon'  => esc_html__( 'Parent Portfolios:', 'wiloke' ),
            'not_found'          => esc_html__( 'No Portfolios found.', 'wiloke' ),
            'not_found_in_trash' => esc_html__( 'No Portfolios found in Trash.', 'wiloke' )
        );

        $args = array(
            'labels'             => $labels,
            'description'        => esc_html__( 'Description.', 'wiloke' ),
            'public'             => true,
            'menu_icon'          => 'dashicons-book',
            'taxonomies'         => array( 'post_tag' ),
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => 'portfolio', 'with_front' => false),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'custom-fields' )
        );

        register_post_type( 'portfolio', $args );
    }
}

/**
 * Register Photo Taxonomy
 * @since 1.0
 */
if ( !function_exists('wiloke_oz_register_taxonomy') )
{
    add_action( 'init', 'wiloke_oz_register_taxonomy', 0 );

    function wiloke_oz_register_taxonomy() {
        // Add new taxonomy, make it hierarchical (like categories)
        $labels = array(
            'name'              => _x( 'Portfolio Categories', 'taxonomy general name', 'wiloke' ),
            'singular_name'     => _x( 'Portfolio Category', 'taxonomy singular name', 'wiloke' ),
            'search_items'      => __( 'Search Portfolio Categories', 'wiloke' ),
            'all_items'         => __( 'All Portfolio Categories', 'wiloke' ),
            'parent_item'       => __( 'Parent Portfolio Category', 'wiloke' ),
            'parent_item_colon' => __( 'Parent Portfolio Category:', 'wiloke' ),
            'edit_item'         => __( 'Edit Portfolio Category', 'wiloke' ),
            'update_item'       => __( 'Update Portfolio Category', 'wiloke' ),
            'add_new_item'      => __( 'Add New Portfolio Category', 'wiloke' ),
            'new_item_name'     => __( 'New Portfolio Category Name', 'wiloke' ),
            'menu_name'         => __( 'Portfolio Categories', 'wiloke' ),
        );

        $args = array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'portfolio-category' ),
        );

        register_taxonomy( 'portfolio_category', array( 'portfolio' ), $args );

    }
}