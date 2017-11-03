<?php
global $wiloke;

return array(
    'single_post'    => array(
        'id'         => 'single_post_settings',
        'title'      => esc_html__( 'Settings', 'oz' ),
        'pages'      => array( 'post', 'oz'), // Post type
        'context'    => 'normal',
        'priority'   => 'low',
        'show_names' => true, // Show field names on the left
        'fields'     => array(
            array(
                'type'          => 'text',
                'id'            => 'description',
                'name'          => esc_html__('Description', 'oz')
            )
        )
    ),
    'woocommerce_settings'   => array(
        'title'	     => esc_html__('Shop Settings', 'oz'),
        'subtitle'	 => esc_html__('These settings is only available for the shop page.', 'oz'),
        'id'	     => 'woocommerce_settings',
        'pages'      => array( 'page'), // Post type
        'context'    => 'normal',
        'priority'   => 'low',
        'show_names' => true,
        'dependency_on_template' => array('=', 'default'),
        'fields'    => array(
            array(
                'type'        => 'select',
                'id'          => 'is_wiloke_design_shop',
                'name'        => esc_html__('Using Wiloke Design Shop Page', 'oz'),
                'description' => esc_html__('As default, the content in the WordPress Editor will be displayed at the top of the page (Above the products). Checked this box combine with Design Shop shortcode to create an awesome shop layout.', 'oz'),
                'options'     => array(
                    'yes'   => esc_html__('Yes', 'oz'),
                    'no'   => esc_html__('No', 'oz')
                ),
                'default'     => 'no'
            )
        )
    ),
    'single_page'    => array(
        'id'         => 'single_page_settings',
        'title'      => esc_html__( 'Page Settings', 'oz' ),
        'pages'      => array('page'), // Post type
        'context'    => 'normal',
        'priority'   => 'low',
        'show_names' => true, // Show field names on the left
        'fields'     => array(
            array(
                'type'         => 'file',
                'id'           => 'logo',
                'name'         => esc_html__('Logo', 'oz'),
                'description'  => esc_html__('Leave empty to use the default setting', 'oz'),
                'default'      => '',
                'options'      => ''
            ),
            array(
                'type'         => 'select',
                'id'           => 'header_skin',
                'name'         => esc_html__('Header Skin', 'oz'),
                'options'      => array(
                    'inherit'          => esc_html__('Inherit Theme Options', 'oz'),
                    'wil-theme-dark'   => esc_html__('Dark', 'oz'),
                    'wil-theme-light'  => esc_html__('Light', 'oz')
                ),
                'default'      => 'inherit'
            ),
            array(
                'type'         => 'select',
                'id'           => 'header_set_to_top',
                'name'         => esc_html__('Assign Header to the top', 'oz'),
                'options'      => array(
                    'enable'   => esc_html__('Enable', 'oz'),
                    'disable'  => esc_html__('Disable', 'oz')
                ),
                'default'      => 'disable'
            ),
            array(
                'type'         => 'select',
                'id'           => 'header_sticky',
                'name'         => esc_html__('Toggle Sticky Header', 'oz'),
                'options'      => array(
                    'inherit'  => esc_html__('Inherit Theme Options', 'oz'),
                    'enable'   => esc_html__('Enable', 'oz'),
                    'disable'  => esc_html__('Disable', 'oz')
                ),
                'default'      => 'inherit'
            ),
	        array(
		        'type'         => 'select',
		        'id'           => 'page_container',
		        'name'         => esc_html__('Toggle Page Container', 'oz'),
		        'description'  => esc_html__('Enable this feature to set the maximum width of 1110px for this page.', 'oz'),
		        'default'      => 'disable',
		        'options'      => array(
			        'disable'   => esc_html__('Disable', 'oz'),
			        'enable'    => esc_html__('Enable', 'oz')
		        )
	        ),
            array(
                'type'         => 'select',
                'id'           => 'theme_skin',
                'name'         => esc_html__('Page Skin', 'oz'),
                'with_meta_box'=> 'page_skin',
                'default'      => 'wil-theme-light',
                'options'      => array(
                    'wil-theme-light'   => esc_html__('Theme Light', 'oz'),
                    'wil-theme-dark'    => esc_html__('Theme Dark', 'oz')
                )
            ),
            array(
                'type'     => 'colorpicker',
                'id'       => 'theme_color',
                'name'     => esc_html__('Override Theme Color', 'oz'),
                'description' => esc_html__('Leave empty to use the setting in the ThemeOptions.', 'oz')
            ),
            array(
                'type'     => 'textarea',
                'id'       => 'page_description',
                'name'     => esc_html__('Page Description', 'oz'),
                'description' => esc_html__('The description will show in content of meta tag for SEO + Sharing purpose.', 'oz')
            ),
            array(
                'type'     => 'select',
                'id'       => 'toggle_breadcrumb',
                'name'     => esc_html__('Toggle Breadcrumb', 'oz'),
                'description' => esc_html__('Note that if you are using WooCommerce plugin, the mini-cart will be added to the top menu automatically.', 'oz'),
                'options'  => array(
                    'enable'  => esc_html__('Enable', 'oz'),
                    'disable' => esc_html__('Disable', 'oz')
                )
            )
        )
    ),
    'project_header_background' => array(
        'id'                => 'project_header_settings',
        'title'             => esc_html__( 'Header Background', 'oz' ),
        'pages'             => array( 'portfolio'), // Post type
        'context'           => 'after_title',
        'priority'          => 'high',
        'show_names'        => true, // Show field names on the left
        'fields'            => array(
            array(
                'type'   => 'file',
                'id'     => 'background',
                'name'   => ''
            )
        )
    ),
    'portfolio_desc'         => array(
        'id'                => 'photo_desc',
        'title'             => esc_html__( 'Portfolio Info Guide', 'oz' ),
        'pages'             => array( 'portfolio'), // Post type
        'context'           => 'side',
        'additional_class'  => 'alert alert-info',
        'priority'          => 'low',
        'show_names'        => true, // Show field names on the left
        'fields'            => array(
            array(
                'type'          => 'alert_box',
                'id'            => 'guide_custom_fields',
                'alert'         => Wiloke::wiloke_kses_simple_html( __('OZ uses Custom Fields functionality to show the photo intro. If you don\'t see Custom Fields box on the screen, please click on Screen Options (top-right conner) then checked Custom Fields item. As default there are two default intro key that defined By and Date info. You can also add more new intro items, You should click on <code>Enter new</code> and use the structure: wiloke_project_info_My-Title. The custom intro will be displayed on front-end like this: My Title: [Value]', 'oz'), true )
            )
        )
    ),
    'single_portfolio_intro' => array(
        'id'         => 'single_portfolio_intro',
        'title'      => esc_html__( 'Project Intro', 'oz' ),
        'description'=> esc_html__( 'Let\'s talk a bit more about this project.', 'oz' ),
        'pages'      => array( 'portfolio'), // Post type
        'context'    => 'after_title',
        'priority'   => 'high',
        'show_names' => true, // Show field names on the left
        'fields'     => array(
            array(
                'type'      => 'textarea',
                'id'        => 'project_intro',
                'name'      => esc_html__('Introduction', 'oz'),
            ),
            array(
                'type'        => 'select',
                'id'          => 'project_launch_type',
                'name'        => esc_html__('Launch Project Type', 'oz'),
                'description' => esc_html__('When a visitor click on this button, It is going to open a new tab or self-page.', 'oz'),
                'default'     => 'inherit',
                'options'     => array(
                    'inherit' => esc_html__('Inherit Theme Options', 'oz'),
                    '_blank'  => esc_html__('New Tab', 'oz'),
                    '_self'   => esc_html__('Self Page', 'oz'),
                )
            ),
            array(
                'type'        => 'text',
                'id'          => 'project_link',
                'name'        => esc_html__('Project Link', 'oz'),
                'placeholder' => esc_html__('Project Link', 'oz'),
                'subtitle'    => esc_html__('Enter the link of the project', 'oz')
            ),
            array(
                'type'         => 'text',
                'id'           => 'project_launch_btn',
                'name'         => esc_html__('Launch Project Button Name', 'oz'),
                'placeholder'  => esc_html__('Launch Project', 'oz'),
                'description'  => esc_html__('Leave empty to inherit the Theme Option\'s setting ', 'oz'),
                'default'      => ''
            )
        )
    )
);