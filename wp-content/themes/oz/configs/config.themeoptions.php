<?php

$aConfigureMailchimp = array();

return array(
    'menu_name' => esc_html__('Theme Options', 'oz'),
    'menu_slug' => 'wiloke',
    'redux'     => array(
        'args'      => array(
                // TYPICAL -> Change these values as you need/desire
                'opt_name'             => 'wiloke_options',
                // This is where your data is stored in the database and also becomes your global variable name.
                'display_name'         => 'wiloke',
                // Name that appears at the top of your panel
                'display_version'      => WILOKE_THEMEVERSION,
                // Version that appears at the top of your panel
                'menu_type'            => 'submenu',
                //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
                'allow_sub_menu'       => false,
                // Show the sections below the admin menu item or not
                'menu_title'           => esc_html__( 'Theme Options', 'oz' ),
                'page_title'           => esc_html__( 'Theme Options', 'oz' ),
                // You will need to generate a Google API key to use this feature.
                // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
                'google_api_key'       => '',
                // Set it you want google fonts to update weekly. A google_api_key value is required.
                'google_update_weekly' => false,
                // Must be defined to add google fonts to the typography module
                'async_typography'     => true,
                // Use a asynchronous font on the front end or font string
                //'disable_google_fonts_link' => true,                    // Disable this in case you want to create your own google fonts loader
                'admin_bar'            => true,
                // Show the panel pages on the admin bar
                'admin_bar_icon'     => 'dashicons-portfolio',
                // Choose an icon for the admin bar menu
                'admin_bar_priority' => 50,
                // Choose an priority for the admin bar menu
                'global_variable'      => '',
                // Set a different name for your global variable other than the opt_name
                'dev_mode'             => false,
                // Show the time the page took to load, etc
                'update_notice'        => false,
                // If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
                'customizer'           => false,
                // Enable basic customizer support
                //'open_expanded'     => true,                    // Allow you to start the panel in an expanded way initially.
                //'disable_save_warn' => true,                    // Disable the save warning when a user changes a field

                // OPTIONAL -> Give you extra features
                'page_priority'        => null,
                // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
                'page_parent'          => 'themes.php',
                // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
                'page_permissions'     => 'manage_options',
                // Permissions needed to access the options panel.
                'menu_icon'            => '',
                // Specify a custom URL to an icon
                'last_tab'             => '',
                // Force your panel to always open to a specific tab (by id)
                'page_icon'            => 'icon-themes',
                // Icon displayed in the admin panel next to your menu_title
                'page_slug'            => '',
                // Page slug used to denote the panel, will be based off page title then menu title then opt_name if not provided
                'save_defaults'        => true,
                // On load save the defaults to DB before user clicks save or not
                'default_show'         => false,
                // If true, shows the default value next to each field that is not the default value.
                'default_mark'         => '',
                // What to print by the field's title if the value shown is default. Suggested: *
                'show_import_export'   => true,
                // Shows the Import/Export panel when not used as a field.

                // CAREFUL -> These options are for advanced use only
                'transient_time'       => 60 * MINUTE_IN_SECONDS,
                'output'               => true,
                // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
                'output_tag'           => true,
                // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
                // 'footer_credit'     => '',                   // Disable the footer credit of Redux. Please leave if you can help it.

                // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
                'database'             => '',
                // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
                'system_info'          => false,
                // REMOVE

                // HINTS
                'hints'                => array(
                    'icon'          => 'el el-question-sign',
                    'icon_position' => 'right',
                    'icon_color'    => 'lightgray',
                    'icon_size'     => 'normal',
                    'tip_style'     => array(
                        'color'   => 'light',
                        'shadow'  => true,
                        'rounded' => false,
                        'style'   => '',
                    ),
                    'tip_position'  => array(
                        'my' => 'top left',
                        'at' => 'bottom right',
                    ),
                    'tip_effect'    => array(
                        'show' => array(
                            'effect'   => 'slide',
                            'duration' => '500',
                            'event'    => 'mouseover',
                        ),
                        'hide' => array(
                            'effect'   => 'slide',
                            'duration' => '500',
                            'event'    => 'click mouseleave',
                        ),
                    ),
                )
        ),
        'sections'  => array(
            // General Settings
            array(
                'title'            => esc_html__('General', 'oz'),
                'id'               => 'general_settings',
                'subsection'       => false,
                'customizer_width' => '500px',
                'pages'            => array('page'),
                'fields'           => array(
                    array(
                        'id'       => 'general_logo',
                        'type'     => 'media',
                        'title'    => esc_html__( 'Logo', 'oz'),
                        'subtitle' => esc_html__( 'Upload a logo for this site. This logo will be displayed at the top right of header.', 'oz'),
                        'default'  => array(
                            'url'  => WILOKE_THEME_URI . 'img/logo.png'
                        )
                    ),
                    array(
                        'id'       => 'general_retina_logo',
                        'type'     => 'media',
                        'title'    => esc_html__( 'Retina Logo', 'oz'),
                        'subtitle' => esc_html__( 'Upload a logo for retina-display devices.', 'oz'),
                        'default'  => ''
                    ),
                    array(
                        'type'         => 'select',
                        'id'           => 'general_toggle_logo_animation',
                        'title'        => esc_html__('Toggle Logo Animation', 'oz'),
                        'default'      => 'enable',
                        'options'      => array(
                            'enable'   => esc_html__('Enable', 'oz'),
                            'disable'  => esc_html__('Disable', 'oz')
                        )
                    ),
                    array(
                        'id'       => 'general_favicon',
                        'type'     => 'media',
                        'title'    => esc_html__( 'Upload favicon', 'oz'),
                        'default'  => ''
                    ),
                    array(
                        'id'       => 'general_apple_touch_icon',
                        'type'     => 'media',
                        'title'    => esc_html__( 'Upload Apple Touch Icon', 'oz'),
                        'default'  => ''
                    ),
                    array(
                        'id'       => 'is_preloader',
                        'type'     => 'select',
                        'title'    => esc_html__( 'Enable Pre-loader', 'oz'),
                        'subtitle' => esc_html__( 'This feature is automatically enabled if you are using Ajax Feature Loading.', 'oz'),
                        'default'  => 'no',
                        'options'  => array(
                            'yes' => esc_html__('Yes', 'oz'),
                            'no'  => esc_html__('Thanks, but no thanks', 'oz')
                        )
                    ),
                    array(
                        'type'    => 'media',
                        'required'=> array('is_preloader', '=', 'yes'),
                        'id'      => 'general_preloader',
                        'title'   => esc_html__('Your Own Preloader', 'oz'),
                        'subtitle'=> esc_html__('You can upload an another preloader for your website.', 'oz')
                    ),
                    array(
                        'type'    => 'section',
                        'id'      => 'general_header',
                        'title'   => esc_html__('Header', 'oz'),
                        'indent'  => true
                    ),
                    array(
                        'type'         => 'select',
                        'id'           => 'general_header_skin',
                        'title'        => esc_html__('Header Skin', 'oz'),
                        'options'      => array(
                            'wil-theme-dark'   => esc_html__('Dark', 'oz'),
                            'wil-theme-light'  => esc_html__('Light', 'oz')
                        ),
                        'default'      => 'disable'
                    ),
                    array(
                        'type'         => 'select',
                        'id'           => 'general_header_sticky',
                        'title'        => esc_html__('Toggle Sticky Header', 'oz'),
                        'options'      => array(
                            'enable'   => esc_html__('Enable', 'oz'),
                            'disable'  => esc_html__('Disable', 'oz')
                        ),
                        'default'      => 'disable'
                    ),
                    array(
                        'type'         => 'text',
                        'id'           => 'general_breakpoint_menu',
                        'title'        => esc_html__('Breakpoint For Menu', 'oz'),
                        'subtitle'     => esc_html__('If the screen width is smaller than or equal to the breakpoint, Three Line menu style will be used instead of the Horizontal menu. This is very useful in the case you have a long menu items.', 'oz'),
                        'default'      => 1240
                    ),
                    array(
                        'type'         => 'select',
                        'id'           => 'general_toggle_menu_animation',
                        'title'        => esc_html__('Toggle Menu Animation', 'oz'),
                        'default'      => 'enable',
                        'options'      => array(
                            'enable'   => esc_html__('Enable', 'oz'),
                            'disable'  => esc_html__('Disable', 'oz')
                        )
                    ),
                    array(
                        'type'         => 'select',
                        'id'           => 'general_header_information_box',
                        'title'        => esc_html__('Toggle Information Box', 'oz'),
                        'subtitle'     => esc_html__('This box will be shown on the top right of the site.', 'oz'),
                        'default'      => 'disable',
                        'options'      => array(
                            'disable' => esc_html__('Disable', 'oz'),
                            'enable'  => esc_html__('Enable', 'oz'),
                        )
                    ),
                    array(
                        'type'    => 'text',
                        'id'      => 'general_header_information_box_following',
                        'title'   => esc_html__('Social Following', 'oz'),
                        'subtitle'=> esc_html__('Navigate to Social Networks tab to enter your social networks. Enter "disable" to turn off it. ', 'oz'),
                        'default'  => 'Follow us on',
                        'required' => array('general_header_information_box', '=', 'enable')
                    ),
                    array(
                        'type'    => 'multi_text',
                        'id'      => 'general_header_information_box_other_information',
                        'title'   => esc_html__('Other information', 'oz'),
                        'subtitle'=> esc_html__('For example: Title &lt;span wilokestyle>+123 987654321&lt;/span>. Note that wilokestyle is required', 'oz'),
                        'default'  => array(
                            'Email <a href="mailto:onstudio@email.com" wilokestyle>onstudio@email.com</a>',
                            'Phone <span wilokestyle>+123 987654321</span>'
                        ),
                        'show_empty' => false,
                        'required' => array('general_header_information_box', '=', 'enable')
                    ),
                    array(
                        'type'         => 'select',
                        'id'           => 'general_toggle_header_information_box',
                        'title'        => esc_html__('Toggle Information Box Animation', 'oz'),
                        'default'      => 'enable',
                        'options'      => array(
                            'disable' => esc_html__('Disable', 'oz'),
                            'enable'  => esc_html__('Enable', 'oz'),
                        ),
                        'required' => array('general_header_information_box', '=', 'enable')
                    ),
                    array(
                        'type'         => 'select',
                        'id'           => 'general_toggle_add_search_to_menu',
                        'title'        => esc_html__('Toggle Search Feature', 'oz'),
                        'subtitle'     => esc_html__('Enable / Disable the feature to header', 'oz'),
                        'default'      => 'enable',
                        'options'      => array(
                            'disable' => esc_html__('Disable', 'oz'),
                            'enable'  => esc_html__('Enable', 'oz'),
                        )
                    ),
                    array(
                        'type'         => 'select',
                        'id'           => 'general_toggle_search_feature_animation',
                        'title'        => esc_html__('Toggle Search Animation', 'oz'),
                        'default'      => 'enable',
                        'options'      => array(
                            'disable' => esc_html__('Disable', 'oz'),
                            'enable'  => esc_html__('Enable', 'oz'),
                        ),
                        'required' => array('general_toggle_add_search_to_menu', '=', 'enable')
                    ),
                    array(
                        'type'         => 'text',
                        'id'           => 'general_search_suggestion_title',
                        'title'        => esc_html__('Search Suggestion Title', 'oz'),
                        'default'      => 'Trend'
                    ),
                    array(
                        'type'         => 'text',
                        'id'           => 'general_search_suggestion_limited',
                        'title'        => esc_html__('Number of posts', 'oz'),
                        'default'      => 4
                    ),
                    array(
                        'type'         => 'select',
                        'id'           => 'general_search_suggestion',
                        'title'        => esc_html__('Search Suggestion By', 'oz'),
                        'default'      => 'portfolio',
                        'options'      => array(
                            'portfolio'=> esc_html__('Most of the Projects Viewed', 'oz'),
                            'product'  => esc_html__('Most of the Products Viewed', 'oz'),
                            'post'     => esc_html__('Most of the Posts Viewed', 'oz'),
                        )
                    ),
                    array(
                        'type'         => 'text',
                        'id'           => 'general_search_suggestion_update',
                        'title'        => esc_html__('Updating the suggestion each x seconds', 'oz'),
                        'default'      => 1800
                    ),
                    array(
                        'type'         => 'select',
                        'multi'        => true,
                        'id'           => 'general_breadcrumb',
                        'title'        => esc_html__('Disable Breadcrumb on', 'oz'),
                        'subtitle'     => esc_html__('Note that if you are using WooCommerce plugin, the mini-cart will automatically be added to top if the breadcrumb is disabled', 'oz'),
                        'default'      => array('portfolio'),
                        'options'      => array(
                            'post'     => esc_html__('Single post', 'oz'),
                            'page'     => esc_html__('Single page', 'oz'),
                            'home'     => esc_html__('Home page', 'oz'),
                            'product'  => esc_html__('Product page', 'oz'),
                            'portfolio_category'  => esc_html__('Portfolio Category', 'oz'),
                            'category'  => esc_html__('Post Category', 'oz'),
                            'author'       => esc_html__('Author page', 'oz'),
                            'post_tag'     => esc_html__('Tag page', 'oz'),
                            'search'       => esc_html__('Search page', 'oz'),
                            'product_cat'  => esc_html__('Product Category', 'oz'),
                        )
                    ),
                    array(
                        'type'    => 'section',
                        'id'      => 'general_header_close',
                        'title'   => '',
                        'indent'  => false
                    ),
                    array(
                        'type'         => 'text',
                        'id'           => 'general_map_api',
                        'title'        => esc_html__('Google Map API', 'oz'),
                        'subtitle'     => Wiloke::wiloke_kses_simple_html( __('It is required if you wanna use Google MAP. Please go to this link to generate your <a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank">Google API key</a>', 'oz'), true),
                        'default'      => ''
                    )
                )
            ),

            // SEO
            array(
                'title'            => esc_html__('SEO', 'oz'),
                'id'               => 'seo_settings',
                'subsection'       => false,
                'customizer_width' => '500px',
                'icon'             => 'dashicons dashicons-search',
                'fields'           => array(
                    array(
                        'id'        => 'seo_open_graph_meta',
                        'type'      => 'select',
                        'options'   => array(
                            'enable'  => esc_html__('Enable', 'oz'),
                            'disable' => esc_html__('Disable', 'oz')
                        ),
                        'default'  => 'enable',
                        'title'    => esc_html__('Open Graph Meta', 'oz'),
                        'subtitle' => esc_html__('Elements that describe the object in different ways and are represented by meta tags included on the object page', 'oz')
                    ),
                    array(
                        'type'     => 'text',
                        'id'       => 'seo_home_custom_title',
                        'title'    => esc_html__('Homepage custom title', 'oz'),
                        'subtitle' => esc_html__('The title will be displayed in homepage between &lt;title>&lt;/title> tags', 'oz')
                    ),
                    array(
                        'id'        => 'seo_home_title_format',
                        'type'      => 'select',
                        'options'   => array(
                            'blogname_blogdescription'  => esc_html__('Blog Name | Blog Description', 'oz'),
                            'blogdescription_blogname'  => esc_html__('Blog Description | Blog Name', 'oz'),
                            'blogname' => esc_html__('Blog Name Only', 'oz')
                        ),
                        'default'  => 'blogname_blogdescription',
                        'title'    => esc_html__('Home Title Format', 'oz'),
                        'subtitle' => esc_html__('If Homepage custom title not set', 'oz')
                    ),
                    array(
                        'id'        => 'seo_archive_title_format',
                        'type'      => 'select',
                        'options'   => array(
                            'categoryname_blogname'  => esc_html__('Category Name | Blog Name', 'oz'),
                            'blogname_categoryname'  => esc_html__('Blog Name | Category Name', 'oz'),
                            'category' => esc_html__('Category Name Only', 'oz')
                        ),
                        'default'     => 'categoryname_blogname',
                        'title'       => esc_html__('Category Title Format', 'oz'),
                        'subtitle'    => esc_html__('If Homepage custom title not set', 'oz')
                    ),
                    array(
                        'id'        => 'seo_single_post_page_title_format',
                        'type'      => 'select',
                        'options'   => array(
                            'posttitle_blogname'  => esc_html__('Post Title | Blog Name', 'oz'),
                            'blogname_posttitle'  => esc_html__('Blog Name | Post Title', 'oz'),
                            'posttitle' => esc_html__('Post Title Only', 'oz')
                        ),
                        'default'     => 'posttitle_blogname',
                        'title'       => esc_html__('Single Post Page Title Format', 'oz')
                    ),
                    array(
                        'id'       => 'seo_home_meta_keywords',
                        'type'     => 'textarea',
                        'title'    => esc_html__('Home Meta Keywords', 'oz'),
                        'subtitle' => esc_html__('Add tags for the search engines and especially Google', 'oz')
                    ),
                    array(
                        'id'    => 'seo_home_meta_description',
                        'type'  => 'textarea',
                        'title' => esc_html__('Home Meta Description', 'oz')
                    ),
                    array(
                        'id'     => 'seo_author_meta_description',
                        'type'   => 'textarea',
                        'title'  => esc_html__('Author Meta Description', 'oz'),
                        'default'=>'wiloke.com'
                    ),
                    array(
                        'id'     => 'seo_contact_meta_description',
                        'type'   => 'textarea',
                        'title'  => esc_html__('Contact Meta Description', 'oz'),
                        'default'=>'piratesmorefun@gmail.com'
                    ),
                    array(
                        'id'     => 'seo_other_meta_keywords',
                        'type'   => 'textarea',
                        'title'  => esc_html__('Other Meta Keywords', 'oz'),
                        'default'=>''
                    ),
                    array(
                        'id'     => 'seo_other_meta_description',
                        'type'   => 'textarea',
                        'title'  => esc_html__('Other Meta Description', 'oz'),
                        'default'=>''
                    )
                )
            ),

            // Sidebar
            array(
                'title'            => esc_html__('Sidebar', 'oz'),
                'id'               => 'sidebar_settings',
                'subsection'       => false,
                'customizer_width' => '500px',
                'icon'             => 'dashicons dashicons-align-right',
                'fields'           => array(
                    array(
                        'id'        => 'post_sidebar',
                        'type'      => 'select',
                        'options'   => array(
                            'left'  => esc_html__('Left Sidebar', 'oz'),
                            'right' => esc_html__('Right Sidebar', 'oz'),
                            'no'    => esc_html__('No Sidebar', 'oz'),
                        ),
                        'default'   => 'right',
                        'title'     => esc_html__('Post Sidebar', 'oz')
                    ),
                    array(
                        'id'        => 'page_sidebar',
                        'type'      => 'select',
                        'options'   => array(
                            'left'  => esc_html__('Left Sidebar', 'oz'),
                            'right' => esc_html__('Right Sidebar', 'oz'),
                            'no'    => esc_html__('No Sidebar', 'oz'),
                        ),
                        'default'   => 'no',
                        'title'     => esc_html__('Page Sidebar', 'oz')
                    ),
                    array(
                        'id'        => 'archive_search_sidebar',
                        'type'      => 'select',
                        'options'   => array(
                            'left'  => esc_html__('Left Sidebar', 'oz'),
                            'right' => esc_html__('Right Sidebar', 'oz'),
                            'no'    => esc_html__('No Sidebar', 'oz'),
                        ),
                        'default'   => 'right',
                        'title'     => esc_html__('Archive, Home, Search ', 'oz')
                    ),
                    array(
                        'id'        => 'shop_sidebar',
                        'type'      => 'select',
                        'options'   => array(
                            'left'  => esc_html__('Left Sidebar', 'oz'),
                            'right' => esc_html__('Right Sidebar', 'oz'),
                            'no'    => esc_html__('No Sidebar', 'oz'),
                        ),
                        'default'   => 'no',
                        'title'     => esc_html__('Shop Sidebar ', 'oz')
                    ),
                    array(
                        'id'        => 'product_sidebar',
                        'type'      => 'select',
                        'options'   => array(
                            'left'  => esc_html__('Left Sidebar', 'oz'),
                            'right' => esc_html__('Right Sidebar', 'oz'),
                            'no'    => esc_html__('No Sidebar', 'oz'),
                        ),
                        'default'   => 'no',
                        'title'     => esc_html__('Product Sidebar ', 'oz')
                    ),
                )
            ),

            // Social networks
            array(
                'title'            => esc_html__('Social Networks', 'oz'),
                'id'               => 'social_network_settings',
                'subsection'       => false,
                'icon'             => 'dashicons dashicons-share',
                'customizer_width' => '500px',
                'fields'           => WilokeSocialNetworks::render_setting_field()
            ),

            // Blog Single
            array(
                'title'            => esc_html__('Blog', 'oz'),
                'id'               => 'blog_single',
                'icon'             => 'dashicons dashicons-media-spreadsheet',
                'subsection'       => false,
                'customizer_width' => '500px',
                'fields'           => array(
                    array(
                        'type'        => 'section',
                        'id'          => 'section_blog_section',
                        'title'       => esc_html__('General Settings', 'oz'),
                        'indent'      => true
                    ),
                    array(
                        'type'        => 'select',
                        'id'          => 'blog_layout',
                        'title'       => esc_html__('Blog Layout', 'oz'),
                        'options'     => array(
                            'creative' => esc_html__('Creative', 'oz'),
                            'style1'   => esc_html__('Alternate Grids', 'oz'),
                            'style2'   => esc_html__('Classic', 'oz'),
                        ),
                        'default'     => 'enable'
                    ),
                    array(
                        'type'        => 'section',
                        'id'          => 'section_blog_section_close',
                        'title'       => '',
                        'indent'      => false
                    ),
                    array(
                        'type'        => 'section',
                        'id'          => 'section_single_post',
                        'title'       => esc_html__('Article Settings', 'oz'),
                        'indent'      => true
                    ),
                    array(
                        'type'        => 'select',
                        'id'          => 'single_post_toggle_related_posts',
                        'title'       => esc_html__('Related Posts', 'oz'),
                        'options'     => array(
                            'enable'  => esc_html__('Enable', 'oz'),
                            'disable' => esc_html__('Disable', 'oz')
                        ),
                        'default'     => 'enable'
                    ),
                    array(
                        'type'        => 'text',
                        'id'          => 'single_post_related_posts_title',
                        'title'       => esc_html__('Title', 'oz'),
                        'default'     => 'You may also like'
                    ),
                    array(
                        'type'        => 'select',
                        'id'          => 'single_post_related_number_of_articles',
                        'title'       => esc_html__('Number of articles', 'oz'),
                        'options'     => array(
                            'col-md-4'  => esc_html__('3 Articles', 'oz'),
                            'col-md-6' => esc_html__('2 Articles', 'oz')
                        ),
                        'default'     => 'col-md-6'
                    )
                ),
            ),

            // Single Portfolio
            array(
                'title'          => esc_html__('Portfolio', 'oz'),
                'metabox_title'  => esc_html__('Project Settings', 'oz'),
                'id'             => 'portfolio',
                'metabox_id'     => 'project_general_settings',
                'icon'           => 'dashicons dashicons-media-spreadsheet',
                'subsection'     => false,
                'context'        => 'after_title',
                'metabox_order'  => 'before',
                'priority'       => 'high',
                'pages'          => array('portfolio'), // Using this key in the case you wanna use the settings for Meta box too
                'customizer_width' => '500px',
                'fields'           => array(
                    array(
                        'type'        => 'section',
                        'id'          => 'portfolio_settings_section',
                        'title'       => esc_html__('Portfolio Settings', 'oz'),
                        'indent'      => true
                    ),
                    array(
                        'type'      => 'color_rgba',
                        'title'     => esc_html__('Project Overlay Color', 'oz'),
                        'id'        => 'portfolio_hover_overlay',
                    ),
                    array(
                        'type'        => 'section',
                        'id'          => 'close_portfolio_settings_section',
                        'title'       => '',
                        'indent'      => false
                    ),
                    array(
                        'type'        => 'section',
                        'id'          => 'project_section',
                        'title'       => esc_html__('Project Settings', 'oz'),
                        'indent'       => true
                    ),
                    array(
                        'type'         => 'media',
                        'id'           => 'project_header_background',
                        'title'        => esc_html__('Header Background', 'oz'),
                        'subtitle'     => esc_html__('This setting will override the setting in the Theme Options.', 'oz'),
                        'default'      => ''
                    ),
                    array(
                        'type'         => 'section',
                        'id'           => 'project_advanced_settings',
                        'title'        => esc_html__('Advanced Settings', 'oz'),
                        'indent'       => true
                    ),
                    array(
                        'type'         => 'select',
                        'id'           => 'project_skin',
                        'title'        => esc_html__('Project Skin', 'oz'),
                        'with_meta_box'=> 'project_skin',
                        'default'      => 'wil-theme-light',
                        'options'      => array(
                            'wil-theme-light'   => esc_html__('Theme Light', 'oz'),
                            'wil-theme-dark'    => esc_html__('Theme Dark', 'oz')
                        )
                    ),
                    array(
                        'type'         => 'select',
                        'id'           => 'project_bg_type',
                        'with_meta_box'=> 'project_bg_type',
                        'title'        => esc_html__('Page Background Type', 'oz'),
                        'subtitle'     => esc_html__('Choose kind of background for the page. We offer 3 types: Gradient, Color and Image.', 'oz'),
                        'options'      => array(
                            'gradient' => esc_html__('Gradient', 'oz'),
                            'color'    => esc_html__('Color', 'oz')
                        ),
                        'default'      => 'color'
                    ),
                    array(
                        'type'         => 'color_rgba',
                        'id'           => 'project_bg_color',
                        'with_meta_box'=> 'project_bg_color',
                        'default'      => '',
                        'required'     => array('project_bg_type', '=', 'color'),
                        'title'        => esc_html__('Background Color', 'oz'),
                        'subtitle'     => esc_html__('You can override background color of the above skin by this setting.', 'oz')
                    ),
                    array(
                        'type'         => 'color_rgba',
                        'id'           => 'project_bg_gradient_s',
                        'with_meta_box'=> 'project_bg_gradient_s',
                        'required'     => array('project_bg_type', '=', 'gradient'),
                        'title'        => esc_html__('Background Gradient - Right Color', 'oz'),
                    ),
                    array(
                        'type'         => 'color_rgba',
                        'id'           => 'project_bg_gradient_f',
                        'with_meta_box'=> 'project_bg_gradient_f',
                        'required'     => array('project_bg_type', '=', 'gradient'),
                        'title'        => esc_html__('Background Gradient - Left color', 'oz'),
                    ),
                    array(
                        'type'         => 'media',
                        'id'           => 'project_content_bg_img',
                        'with_meta_box'=> 'project_content_bg_img',
                        'title'        => esc_html__('Content Background Image', 'oz'),
                        'subtitle'     => esc_html__('Upload an image background for the content section.', 'oz')
                    ),
                    array(
                        'type'         => 'media',
                        'id'           => 'project_logo',
                        'with_meta_box'=> 'project_logo',
                        'title'        => esc_html__('Logo', 'oz'),
                        'subtitle'     => esc_html__('Leave empty to use the default setting', 'oz'),
                        'default'      => '',
                        'options'      => ''
                    ),
                    array(
                        'type'         => 'select',
                        'id'           => 'project_style',
                        'title'        => esc_html__('Portfolio Style', 'oz'),
                        'subtitle'     => esc_html__('Choose a style that you want', 'oz'),
                        'with_meta_box'=> 'project_style',
                        'default'      => 'style1',
                        'options'      => array(
                            'style1' => esc_html__('No Sidebar', 'oz'),
                            'style2' => esc_html__('Left Sidebar Info', 'oz'),
                            'style3' => esc_html__('Right Sidebar Info', 'oz')
                        )
                    ),
                    array(
                        'type'         => 'select',
                        'id'           => 'project_boxed',
                        'title'        => esc_html__('Toggle Boxed layout', 'oz'),
                        'with_meta_box'=> 'project_boxed',
                        'default'      => 'enable',
                        'options'      => array(
                            'enable'   => esc_html__('Enable', 'oz'),
                            'disable'  => esc_html__('Disable', 'oz')
                        )
                    ),
                    array(
                        'type'         => 'section',
                        'id'           => 'project_advanced_settings_close',
                        'title'        => '',
                        'indent'       => false
                    ),
                    array(
                        'type'         => 'color_rgba',
                        'with_meta_box'=> 'project_gcfirst_title_s',
                        'id'           => 'project_gcfirst_title_s',
                        'title'        => esc_html__('Gradient - Right Color', 'oz'),
                        'description'  => esc_html__('Create a linear gradient for first charater of the title.', 'oz'),
                        'default'      => ''
                    ),
                    array(
                        'type'         => 'color_rgba',
                        'with_meta_box'=> 'project_gcfirst_title_f',
                        'id'           => 'project_gcfirst_title_f',
                        'title'        => esc_html__('Gradient - Left Color', 'oz'),
                        'description'  => esc_html__('Create a linear gradient for first charater of the title.', 'oz'),
                        'default'      => ''
                    ),
                    array(
                        'type'         => 'color_rgba',
                        'with_meta_box'=> 'project_title_color',
                        'id'           => 'project_title_color',
                        'title'        => esc_html__('Title Color', 'oz'),
                        'default'      => ''
                    ),
                    array(
                        'type'        => 'text',
                        'id'          => 'project_info_created_by_text',
                        'title'       => esc_html__('By', 'oz'),
                        'default'     => 'By:'
                    ),
                    array(
                        'type'        => 'text',
                        'id'          => 'project_info_in_categories_text',
                        'title'       => esc_html__('Categories', 'oz'),
                        'default'     => 'Categories:',
                        'subtitle'    => esc_html__('Leave empty if you don\'t want to display Categories in the intro box', 'oz')
                    ),
                    array(
                        'type'        => 'text',
                        'id'          => 'project_info_published_text',
                        'title'       => esc_html__('Date', 'oz'),
                        'default'     => 'Date:'
                    ),
                    array(
                        'type'        => 'color_rgba',
                        'id'          => 'project_intro_label_color',
                        'with_meta_box'=> 'project_intro_label_color',
                        'title'       => esc_html__('Label Color Of Project Meta Data', 'oz'),
                        'subtitle'    => esc_html__('Set color for By, Categories, Date text.', 'oz'),
                        'default'     => ''
                    ),
                    array(
                        'type'        => 'color_rgba',
                        'with_meta_box'=> 'project_intro_content_color',
                        'id'          => 'project_intro_content_color',
                        'title'       => esc_html__('Meta Data Text Color', 'oz'),
                        'default'     => ''
                    ),
                    array(
                        'type'        => 'color_rgba',
                        'id'          => 'project_text_intro_color',
                        'with_meta_box'=> 'project_text_intro_color',
                        'title'       => esc_html__('Text Intro Color', 'oz'),
                        'default'     => ''
                    ),
                    array(
                        'type'         => 'text',
                        'id'           => 'project_launch_btn',
                        'title'        => esc_html__('Launch Project Button', 'oz'),
                        'default'      => 'Launch Project'
                    ),
                    array(
                        'type'         => 'select',
                        'id'           => 'project_launch_type',
                        'title'        => esc_html__('Launch Project Type', 'oz'),
                        'subtitle'     => esc_html__('When a visitor click on this button, It is going to open a new tab or self-page.', 'oz'),
                        'default'      => '_blank',
                        'options'      => array(
                            '_blank' => esc_html__('New Tab', 'oz'),
                            '_self'  => esc_html__('Self Page', 'oz'),
                        )
                    ),
                    array(
                        'type'         => 'select',
                        'id'           => 'project_launch_btn_style',
                        'with_meta_box'=> 'project_launch_btn_style',
                        'title'        => esc_html__('Launch Button Style', 'oz'),
                        'default'      => 'inherit',
                        'options'      => array(
                            'wil-btn--defaultstyle' => esc_html__('Strong Border Button', 'oz'),
                            'wil-btn--rounded'      => esc_html__('A little bit border around the button', 'oz'),
                            'wil-btn--square'       => esc_html__('Square button', 'oz'),
                        )
                    ),
                    array(
                        'type'        => 'select',
                        'id'          => 'project_launch_btn_bg',
                        'with_meta_box' => 'project_launch_btn_bg',
                        'title'       => esc_html__('Button Background', 'oz'),
                        'default'     => 'wil-btn--main',
                        'options'     => array(
                            'wil-btn--gray'  => esc_html__('Grey Color', 'oz'),
                            'wil-btn--dark'  => esc_html__('Dark Color', 'oz'),
                            'wil-btn--main'  => esc_html__('Theme Color', 'oz'),
                        )
                    ),
                    array(
                        'type'        => 'select',
                        'id'          => 'project_launch_btn_size',
                        'with_meta_box' => 'project_launch_btn_size',
                        'title'       => esc_html__('Button Size', 'oz'),
                        'default'     => 'wil-btn--medium',
                        'options'     => array(
                            'wil-btn--medium'  => esc_html__('Medium', 'oz'),
                            'wil-btn--large'   => esc_html__('Large', 'oz'),
                            'wil-btn--small'   => esc_html__('Small', 'oz'),
                        )
                    ),
                    array(
                        'type'         => 'select',
                        'id'           => 'portfolio_page',
                        'data'         => 'page',
                        'title'        => esc_html__('Set Portfolio Page', 'oz'),
                        'subtitle'     => esc_html__('This link will be used on the project page. When an user click on this, it will redirect to the page', 'oz')
                    )
                )
            ),

            // Footer Settings
            array(
                'title'            => esc_html__('Footer', 'oz'),
                'id'               => 'footer_settings',
                'subsection'       => false,
                'customizer_width' => '500px',
                'icon'             => 'dashicons dashicons-hammer',
                'fields'           => array(
                    array(
                        'id'        => 'footer_before_widget_section',
                        'type'      => 'section',
                        'indent'    => true,
                        'title'     => esc_html__('Before Widget', 'oz')
                    ),
                    array(
                        'id'        => 'footer_toggle_before_widget',
                        'type'      => 'select',
                        'title'     => esc_html__('Toggle Before Footer Widget', 'oz'),
                        'options'   => array(
                            'enable'  => esc_html__('Enable', 'oz'),
                            'disable' => esc_html__('Disable', 'oz'),
                        ),
                        'default'   => 'enable'
                    ),
                    array(
                        'title'   => esc_html__('Boxes Content', 'oz'),
                        'type'    => 'wiloke_repeater_field',
                        'id'      => 'footer_boxes_content',
                        'required'=> array('footer_toggle_before_widget', '=', 'enable'),
                        'repeatable' => false,
                        'sortable'=> true, // Allow the users to sort the repeater blocks or not
                        'fields'  => array(
                            array(
                                'type'   => 'text',
                                'default'=> 'icon icon-basic-lightbulb',
                                'title'  => esc_html__('Icon (*)', 'oz'),
                                'id'     => 'icon'
                            ),
                            array(
                                'type'    => 'text',
                                'default' => 'About',
                                'id'      => 'title',
                                'title'   => esc_html__('Title (*)', 'oz')
                            ),
                            array(
                                'type'    => 'text',
                                'default' => '',
                                'id'      => 'description',
                                'title'   => esc_html__('Description', 'oz')
                            ),
                            array(
                                'type'    => 'media',
                                'default' => '',
                                'id'      => 'bg',
                                'title'   => esc_html__('Background Image (*)', 'oz')
                            ),
                            array(
                                'type'    => 'text',
                                'default' => '#',
                                'id'      => 'link',
                                'title'   => esc_html__('Link to', 'oz')
                            ),
                        )
                    ),
                    array(
                        'id'        => 'footer_toggle_boxes_content_animation',
                        'type'      => 'select',
                        'title'     => esc_html__('Toggle Boxes Content Animation', 'oz'),
                        'options'   => array(
                            'wil-animation'  => esc_html__('Enable', 'oz'),
                            '' => esc_html__('Disable', 'oz'),
                        ),
                        'default'   => 'wil-animation'
                    ),
                    array(
                        'id'        => 'footer_before_widget_close_section',
                        'type'      => 'section',
                        'indent'    => false,
                        'title'     => ''
                    ),
                    array(
                        'type'        => 'textarea',
                        'id'          => 'footer_other_information',
                        'title'       => esc_html__('Other Information', 'oz'),
                        'description' => Wiloke::wiloke_kses_simple_html( __('<ul><li><i>OZ uses MailChimp for subscribe functionality, so you need to configure MailChimp before (To configure MailChimp, Wiloke Widget plugin must be activated). Please go to Settings->Wiloke Mailchimp to do it</i>. </li><li><i>[wiloke_social_networks] will show your social network.</i></li></ul>', 'oz'), true),
                        'default'     => '<span class="wil-footer__text__sm">You may love the ideas of ​​us?</span>
                            <p class="wil-footer__text__lg">You can <a href="#wil-subscribe" class="wil-text-underline">Subscribe</a> &amp; follow us on [wiloke_social_networks]'
                    ),
                    array(
                        'type'        => 'textarea',
                        'id'          => 'footer_copyright',
                        'title'       => esc_html__('Copyright', 'oz'),
                        'description' => esc_html__('Using [oz_apcy] to automatically print the current year', 'oz'),
                        'default'     => '&copy; Copyright [oz_apcy] <a href="https://wiloke.com">Wiloke</a> - All Rights Reserved.'
                    )
                )
            ),

            // Advanced Settings
            array(
                'title'            => esc_html__('Advanced Settings', 'oz'),
                'id'               => 'advanced_settings',
                'icon'             => 'dashicons dashicons-lightbulb',
                'subsection'       => false,
                'customizer_width' => '500px',
                'fields'           => array(
                    array(
                        'id'        => 'advanced_ajax_feature',
                        'type'      => 'select',
                        'title'     => esc_html__('Toggle Ajax Loading Feature', 'oz'),
                        'options'   => array(
                            'disable'  => esc_html__('Disable', 'oz'),
                            'enable'   => esc_html__('Enable', 'oz')
                        ),
                        'default'   => 'enable'
                    ),
                    array(
                        'id'        => 'advanced_google_fonts',
                        'type'      => 'select',
                        'title'     => esc_html__('Google Fonts', 'oz'),
                        'options'   => array(
                            'default'   => esc_html__('Default', 'oz'),
                            'general'   => esc_html__('Custom', 'oz'),
                            // 'detail'    => esc_html__('Detail Custom', 'oz')
                        ),
                        'default'   => 'default'
                    ),
                    array(
                        'id'            => 'advanced_general_google_fonts',
                        'type'          => 'text',
                        'title'         => esc_html__('Google Fonts', 'oz'),
                        'required'      => array('advanced_google_fonts', '=', 'general'),
                        'description'   => esc_html__('The theme allows replace current Google Fonts with another Google Fonts. Go to https://fonts.google.com/specimen to get a the Font that you want. For example: https://fonts.googleapis.com/css?family=Prompt', 'oz')
                    ),
                    array(
                        'id'            => 'advanced_general_google_fonts_css_rules',
                        'type'          => 'text',
                        'required'      => array('advanced_google_fonts', '=', 'general'),
                        'title'         => esc_html__('Css Rules', 'oz'),
                        'description'   => esc_html__('This code shoule be under Google Font link. For example: font-family: \'Prompt\', sans-serif;', 'oz')
                    ),
                    array(
                        'id'        => 'advanced_main_color',
                        'type'      => 'select',
                        'title'     => esc_html__('Theme Color', 'oz'),
                        'options'   => array(
                            'default' => esc_html__('Default', 'oz'),
                            'blue'    => esc_html__('Blue', 'oz'),
                            'cyan'    => esc_html__('Cyan', 'oz'),
                            'red'     => esc_html__('Red', 'oz'),
                            'custom'  => esc_html__('Custom', 'oz')
                        ),
                        'default'   => 'default'
                    ),

                    array(
                        'id'        => 'advanced_custom_main_color',
                        'type'      => 'color_rgba',
                        'title'     => esc_html__('Custom Color', 'oz'),
                        'required'  => array('advanced_main_color', '=', 'custom')
                    ),
                    array(
                        'id'        => 'advanced_css_code',
                        'type'      => 'ace_editor',
                        'title'     => esc_html__('Custom CSS Code', 'oz'),
                        'mode'      => 'css',
                        'theme'    => 'monokai'
                    ),
                    array(
                        'id'        => 'advanced_js_code',
                        'type'      => 'ace_editor',
                        'title'     => esc_html__('Custom Javascript Code', 'oz'),
                        'mode'      => 'javascript',
                        'default'   => ''
                    ),
                )
            )
        )
    )
);