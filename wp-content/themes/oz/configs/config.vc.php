<?php
return array(
    'shortcodes' => array(
        // Heading & Description
        array(
            'name'	=> esc_html__('Heading and Description', 'oz'),
            'base'	=> 'wiloke_heading_description',
            'icon'	=> '',
            'show_settings_on_create'	=> true,
            'category'					=> WILOKE_THEMENAME,
            'controls'					=> true,
            'params'    => array(
                array(
                    'type'          => 'dropdown',
                    'heading'       => esc_html__('Heading Tag', 'oz'),
                    'description'   => Wiloke::wiloke_kses_simple_html( __('If you are not family with that, please visit this link: <a href="http://www.w3schools.com/html/html_headings.asp" target="_blank">w3schools.com</a> to know more', 'oz'), true),
                    'param_name'    => 'heading_tag',
                    'admin_label' => true,
                    'value'         => array(
                        'h1' => 'h1',
                        'h2' => 'h2',
                        'h3' => 'h3',
                        'h4' => 'h4',
                        'h5' => 'h5',
                        'h6' => 'h6'
                    ),
                    'std'   => 'h2'
                ),
                array(
                    'type'          => 'textfield',
                    'admin_label' => true,
                    'heading'       => esc_html__('Heading', 'oz'),
                    'param_name'    => 'heading'
                ),
                array(
                    'type'          => 'wiloke_colorpicker',
                    'heading'       => esc_html__('Heading Text Color', 'oz'),
                    'param_name'    => 'heading_color'
                ),
                array(
                    'type'          => 'textarea',
                    'admin_label' => true,
                    'heading'       => esc_html__('Description', 'oz'),
                    'param_name'    => 'description'
                ),
                array(
                    'type'          => 'wiloke_colorpicker',
                    'heading'       => esc_html__('Description Text Color', 'oz'),
                    'param_name'    => 'description_color'
                ),
                array(
                    'type'          => 'dropdown',
                    'heading'       => esc_html__('Toggle Divider', 'oz'),
                    'param_name'    => 'toggle_divider',
                    'group'         => esc_html__('Style', 'oz'),
                    'std'           => 'enable',
                    'value'         => array(
                        esc_html__('Enable', 'oz')  => 'enable',
                        esc_html__('Disable', 'oz') => 'disable'
                    ),
                ),
                array(
                    'type'          => 'wiloke_colorpicker',
                    'heading'       => esc_html__('Divider Color', 'oz'),
                    'param_name'    => 'divider_color',
                    'dependency'    => array('element'=>'toggle_divider', 'value'=>array('enable'))
                ),
                array(
                    'type'          => 'dropdown',
                    'admin_label' => true,
                    'heading'       => esc_html__('Alignment', 'oz'),
                    'param_name'    => 'alignment',
                    'value'         => array(
                        esc_html__('Center', 'oz') => 'wil-text-center',
                        esc_html__('Left', 'oz')   => 'wil-text-left',
                        esc_html__('Right', 'oz')  => 'wil-text-right'
                    )
                ),
            )
        ),
        // Single Images
        array(
            'name'	=> esc_html__('Single Image', 'oz'),
            'base'	=> 'wiloke_single_image',
            'icon'	=> '',
            'show_settings_on_create'	=> true,
            'category'					=> WILOKE_THEMENAME,
            'controls'					=> true,
            'params'    => array(
                array(
                    'type'        => 'attach_image',
                    'heading'     => esc_html__('Upload an Image', 'oz'),
                    'param_name'  => 'image',
                    'admin_label' => true
                ),
                array(
                    'type'          => 'dropdown',
                    'admin_label'   => true,
                    'heading'       => esc_html__('Image Style', 'oz'),
                    'value'         => array(
                        esc_html__('Default', 'oz') => 'default',
                        esc_html__('Circle', 'oz')   => 'cirlce'
                    ),
                    'param_name'    => 'style',
                    'std'           => 'default'
                ),
                array(
                    'type'          => 'textfield',
                    'admin_label'   => true,
                    'heading'       => esc_html__('Image Size', 'oz'),
                    'description'   => esc_html__('Enter image size (Example: "thumbnail", "medium", "large", "full", "wiloke_460_460", "wiloke_925_925", "wiloke_460_925", "wiloke_925_460"). Alternatively enter size in pixels (Example: 200x100 (Width x Height)).
', 'oz'),
                    'param_name'    => 'size'
                ),
                array(
                    'type'          => 'textfield',
                    'admin_label' => true,
                    'heading'       => esc_html__('Caption', 'oz'),
                    'param_name'    => 'caption'
                ),
                array(
                    'type'          => 'wiloke_colorpicker',
                    'heading'       => esc_html__('Caption Text Color', 'oz'),
                    'param_name'    => 'caption_color'
                ),
                array(
                    'type'          => 'dropdown',
                    'admin_label'   => true,
                    'heading'       => esc_html__('On click action', 'oz'),
                    'param_name'    => 'click_action',
                    'std'           => 'none',
                    'value'         => array(
                        esc_html__('None', 'oz') => 'none',
                        esc_html__('Link to Image', 'oz')   => 'to_image',
                        esc_html__('Custom Image Link', 'oz')  => 'custom'
                    )
                ),
                array(
                    'type'          => 'textfield',
                    'admin_label'   => true,
                    'heading'       => esc_html__('Button Name', 'oz'),
                    'dependency'    => array('element'=>'click_action', 'value'=>array('custom', 'to_image')),
                    'param_name'    => 'btn_name',
                    'std'           => 'Launch'
                ),
                array(
                    'type'          => 'textfield',
                    'admin_label'   => true,
                    'heading'       => esc_html__('Image Link', 'oz'),
                    'dependency'    => array('element'=>'click_action', 'value'=>array('custom')),
                    'param_name'    => 'custom_link'
                ),
                array(
                    'type'          => 'dropdown',
                    'admin_label'   => true,
                    'heading'       => esc_html__('Target', 'oz'),
                    'dependency'    => array('element'=>'click_action', 'value'=>array('custom', 'to_image')),
                    'param_name'    => 'target',
                    'value'         => array(
                        esc_html__('Self-Page', 'oz') => '_self',
                        esc_html__('Open in a new window', 'oz')   => '_blank'
                    )
                ),
                array(
                    'type'          => 'dropdown',
                    'admin_label' => true,
                    'heading'       => esc_html__('Alignment', 'oz'),
                    'param_name'    => 'alignment',
                    'value'         => array(
                        esc_html__('Center', 'oz') => 'text-center',
                        esc_html__('Left', 'oz')   => 'text-left',
                        esc_html__('Right', 'oz')  => 'text-right'
                    )
                ),
                array(
                    'type'          => 'dropdown',
                    'admin_label'   => true,
                    'heading'       => esc_html__('Lazy Load', 'oz'),
                    'param_name'    => 'toggle_lazyload',
                    'value'         => array(
                        esc_html__('Enable', 'oz')      => 'enable',
                        esc_html__('Disable', 'oz')     => 'disable'
                    )
                )
            )
        ),
        // Skills Bar
        array(
            'name'	=> esc_html__('Skills Bar', 'oz'),
            'base'	=> 'wiloke_skills_bar',
            'icon'	=> '',
            'show_settings_on_create'	=> true,
            'category'					=> WILOKE_THEMENAME,
            'controls'					=> true,
            'params'    => array(
                array(
                    'type'    => 'param_group',
                    'heading' => esc_html__('Skills', 'oz'),
                    'param_name' => 'skills',
                    'params' => array(
                        array(
                            'type'          => 'textfield',
                            'admin_label' => true,
                            'heading'       => esc_html__('Skill', 'oz'),
                            'param_name'    => 'skill'
                        ),
                        array(
                            'type'          => 'textfield',
                            'admin_label' => true,
                            'heading'       => esc_html__('Percentage', 'oz'),
                            'description'   => esc_html__('Describe your skill by using percentage. Range: 0 - 100.', 'oz'),
                            'param_name'    => 'percentage'
                        ),
                        array(
                            'type'          => 'wiloke_colorpicker',
                            'admin_label'   => true,
                            'heading'       => esc_html__('Text Color', 'oz'),
                            'param_name'    => 'skill_color'
                        ),
                        array(
                            'type'          => 'wiloke_colorpicker',
                            'admin_label'   => true,
                            'heading'       => esc_html__('Completed Skill Background Color', 'oz'),
                            'param_name'    => 'completed_skill_color'
                        ),
                    )
                ),
                array(
                    'type'          => 'wiloke_colorpicker',
                    'heading'       => esc_html__('Uncompleted skill background', 'oz'),
                    'param_name'    => 'uncompleted_skill_color'
                ),
                array(
                    'type'          => 'dropdown',
                    'heading'       => esc_html__('Toggle Animation', 'oz'),
                    'param_name'    => 'toggle_animation',
                    'value'         => array(
                        esc_html__('Enable', 'oz')    => 'wil-animation',
                        esc_html__('Disable', 'oz')   => ''
                    ),
                    'std'           => 'wil-animation' // Set default layout
                ),
            )
        ),
        // Design Shop
        array(
            'name'	=> esc_html__('Design Shop', 'oz'),
            'description' => esc_html__('WooCommerce plugin is required', 'oz'),
            'base'	=> 'wiloke_design_shop',
            'icon'	=> '',
            'has_autocomplete'	=> true,
            'show_settings_on_create'	=> true,
            'category'					=> WILOKE_THEMENAME,
            'controls'					=> true,
            'admin_enqueue_js'          => array(get_template_directory_uri().'/admin/asset/js/packery.pkgd.min.js'),
            'params'    => array(
                array(
                    'type'          => 'dropdown',
                    'heading'       => esc_html__('Source of projects', 'oz'),
                    'param_name'    => 'source_of_projects',
                    'admin_label'   => true,
                    'value'         => array(
                        esc_html__('Pickup Categories', 'oz')    => 'category',
                        esc_html__('Specify Project IDs', 'oz')  => 'custom'
                    ),
                    'std'           => 'category' // Set default layout
                ),
                array(
                    'type'          => 'wiloke_get_list_of_terms',
                    'heading'       => esc_html__('Pickup Categories', 'oz'),
                    'taxonomy'      => 'product_cat',
                    'is_multiple'   => true,
                    'description'   => esc_html__('Leave empty to get all.  Hold ctrl to select multi-categories.', 'oz'),
                    'param_name'    => 'terms',
                    'save_always'   => true,
                    'std'           => '',
                    'dependency'    => array('element'=>'source_of_projects', 'value'=>array('category'))
                ),
                array(
                    'type'        => 'autocomplete',
                    'heading'     => esc_html__('Products', 'oz'),
                    'param_name'  => 'include',
                    'admin_label'   => true,
                    'settings'    => array(
                        'multiple' => true,
                        'sortable' => true,
                        'groups'   => true,
                    ),
                    'dependency' => array(
                        'element' => 'source_of_projects',
                        'value'   => array('custom'),
                    ),
                ),
                array(
                    'type'          => 'dropdown',
                    'heading'       => esc_html__('Order By', 'oz'),
                    'param_name'    => 'orderby',
                    'admin_label'   => true,
                    'std'           => 'date',
                    'value'         => array(
                        esc_html__('Date', 'oz')          => 'post_date',
                        esc_html__('Title', 'oz')         => 'title',
                        esc_html__('Author', 'oz')        => 'author',
                        esc_html__('Name', 'oz')          => 'name',
                        esc_html__('Modified', 'oz')      => 'modified',
                        esc_html__('Comment Count', 'oz') => 'comment_count',
                        esc_html__('Menu Order', 'oz')    => 'menu_order'
                    ),
                ),
                array(
                    'type'          => 'dropdown',
                    'heading'       => esc_html__('Sort Order', 'oz'),
                    'param_name'    => 'order',
                    'admin_label'   => true,
                    'std'           => 'DESC',
                    'value'         => array(
                        '' => '',
                        esc_html__('Descending', 'oz')  => 'DESC',
                        esc_html__('Ascending', 'oz')   => 'ASC'
                    ),
                ),
                array(
                    'type'          => 'dropdown',
                    'heading'       => esc_html__('Toggle Animation', 'oz'),
                    'description'   => esc_html__('Projects will be shown by animation effect on the desktop devices', 'oz'),
                    'param_name'    => 'toggle_animation',
                    'std'           => 'enable',
                    'value'         => array(
                        esc_html__('Enable', 'oz')  => 'enable',
                        esc_html__('Disable', 'oz') => 'disable'
                    )
                ),
                array(
                    'type'          => 'dropdown',
                    'heading'       => esc_html__('Animation on Mobile', 'oz'),
                    'description'   => esc_html__('Projects will be shown by animation effect on the desktop devices', 'oz'),
                    'param_name'    => 'toggle_animation_on_mobile',
                    'std'           => 'enable',
                    'value'         => array(
                        esc_html__('Enable', 'oz')  => 'enable',
                        esc_html__('Disable', 'oz') => 'disable'
                    ),
                    'dependency'    => array('element'=>'toggle_animation', 'value'=>array('enable'))
                ),
                array(
                    'type'          => 'dropdown',
                    'heading'       => esc_html__('Shop Animation Effect', 'oz'),
                    'param_name'    => 'animation_affect',
                    'std'           => 'wil-anim--product',
                    'value'         => array(
                        esc_html__('Default', 'oz')    => 'wil-anim--product',
                        esc_html__('Fade', 'oz')       => 'wil-fade',
                        esc_html__('Fade Up', 'oz')    => 'wil-fadeUp',
                        esc_html__('Fade Down', 'oz')  => 'wil-fadeDown',
                        esc_html__('Fade Left', 'oz')  => 'wil-fadeLeft',
                        esc_html__('Fade Right', 'oz') => 'wil-fadeRight',
                        esc_html__('Zoom In', 'oz')    => 'wil-zoomIn'
                    ),
                    'dependency'    => array('element'=>'toggle_animation', 'value'=>array('enable'))
                ),
                array(
                    'type'          => 'wiloke_design_portfolio_choose_layout',
                    'heading'       => '',
                    'param_name'    => 'wiloke_design_shop_choose_layout',
                    'admin_label'   => true,
                    'options'       => array(
                        'grid' => array(
                            'heading'     => esc_html__('Grid', 'oz'),
                            'img_url'     => get_template_directory_uri() . '/admin/source/design-layout/img/1.jpg',
                            'is_customize' => 'no',
                            'value' => 'eyJpdGVtc19zaXplIjoiY3ViZSwgY3ViZSwgY3ViZSwgY3ViZSwgY3ViZSwgY3ViZSwgY3ViZSwgY3ViZSwgY3ViZSJ9'
                        ),
                        'masonry' => array(
                            'heading'     => esc_html__('Masonry', 'oz'),
                            'img_url'     => get_template_directory_uri() . '/admin/source/design-layout/img/3.jpg',
                            'is_customize' => 'no',
                            'value' => 'eyJpdGVtc19zaXplIjoiY3ViZSwgbGFyZ2UsIHdpZGUsIGN1YmUsIGhpZ2gsIGN1YmUsIGV4dHJhLWxhcmdlLCB3aWRlLCBjdWJlIn0='
                        ),
                        'creative' => array(
                            'heading'     => esc_html__('Creative', 'oz'),
                            'img_url'     => get_template_directory_uri() . '/admin/source/design-layout/img/5.jpg',
                            'is_customize' => 'yes',
                            'value' => 'eyJpdGVtc19zaXplIjoiY3ViZSwgbGFyZ2UsIHdpZGUsIGN1YmUsIGhpZ2gsIGN1YmUsIGV4dHJhLWxhcmdlLCB3aWRlLCBjdWJlIn0='
                        )
                    ),
                    'group'         => esc_html__( 'Choose Layout', 'oz'),
                    'save_always'   => true,
                    'std'           => 'creative' // Set default layout
                ),
                array(
                    'type'          => 'wiloke_design_portfolio_layout',
                    'heading'       => esc_html__('Products Design', 'oz'),
                    'general_settings' => array(
                        'number_of_posts' => 9
                    ),
                    'devices_settings' => array(
                        'large' => array(
                            'items_per_row'   => 4,
                            'horizontal'      => 0,
                            'vertical'        => 0
                        ),
                        'medium' => array(
                            'items_per_row'   => 3,
                            'horizontal'      => 0,
                            'vertical'        => 0
                        ),
                        'small' => array(
                            'items_per_row'   => 2,
                            'horizontal'      => 0,
                            'vertical'        => 0
                        ),
                        'extra_small' => array(
                            'items_per_row'   => 1,
                            'horizontal'      => 0,
                            'vertical'        => 0
                        )
                    ),
                    'param_name'    => 'wiloke_portfolio_layout',
                    'options'          => array(
                        'creative'     => array(
                            'heading'       => esc_html__('Creative', 'oz'),
                            'img_url'       => get_template_directory_uri() . '/admin/source/design-layout/img/3.jpg',
                            'is_dragdrop'   => 'yes',
                            'is_add_sub_btn'=> 'yes',
                            'params' => array(
                                array(
                                    'type'          => 'select',
                                    'param_name'    => 'items_per_row',
                                    'heading'       => esc_html__('Items Per Row', 'oz'),
                                    'description'   => esc_html__('How many items per row?', 'oz'),
                                    'options'       => array(
                                        5 => 5,
                                        4 => 4,
                                        3 => 3,
                                        2 => 2,
                                        1 => 1
                                    )
                                ),
                                array(
                                    'type'          => 'number',
                                    'param_name'    => 'horizontal',
                                    'heading'       => esc_html__('Horizontal Spacing', 'oz'),
                                    'description'   => ''
                                ),
                                array(
                                    'type'          => 'number',
                                    'param_name'    => 'vertical',
                                    'heading'       => esc_html__('Vertical Spacing', 'oz'),
                                    'description'   => ''
                                ),
                                array(
                                    'type'          => 'number',
                                    'param_name'    => 'amount_of_loadmore',
                                    'heading'       => esc_html__('Amount of Loadmore', 'oz'),
                                    'description'   => esc_html__('In the case Load more projects functionality (General Tab) to be used, this setting is effected. Leave empty means it is equal to number of posts.', 'oz')
                                ),
                            ),
                            'std'   => array(
                                'items_size' => 'large,cube,cube,cube,cube,cube,large,cube,cube'
                            )
                        )
                    ),
                    'group'         => 'Design Layout',
                    'save_always'   => true,
                    'std'           => 'creative' // Set default layout
                ),
                array(
                    'type'      => 'dropdown',
                    'heading'   => esc_html__('Pagination Type', 'oz'),
                    'param_name'=> 'pagination_type',
                    'value'         => array(
                        esc_html__('Pagination', 'oz') => 'reload',
                        esc_html__('Loadmore', 'oz')      => 'ajax'
                    ),
                    'std'       => 'ajax'
                )
            )
        ),
        // Testimonial
        array(
            'name'	=> esc_html__('Testimonials', 'oz'),
            'base'	=> 'wiloke_testimonials',
            'icon'	=> '',
            'show_settings_on_create'	=> true,
            'category'					=> WILOKE_THEMENAME,
            'controls'					=> true,
            'params'    => array(
                array(
                    'type'          => 'param_group',
                    'heading'       => esc_html__('Testimonials', 'oz'),
                    'param_name'    => 'testimonials',
                    'params'         => array(
                        array(
                            'type'          => 'attach_image',
                            'heading'       => esc_html__('Client Picture', 'oz'),
                            'param_name'    => 'client_picture',
                            'std'           => '' // Set default layout
                        ),
                        array(
                            'type'          => 'textfield',
                            'heading'       => esc_html__('Client Name', 'oz'),
                            'param_name'    => 'client_name',
                            'std'           => '' // Set default layout
                        ),
                        array(
                            'type'          => 'wiloke_colorpicker',
                            'heading'       => esc_html__('Client Name Color', 'oz'),
                            'param_name'    => 'client_name_color',
                            'std'           => '' // Set default layout
                        ),
                        array(
                            'type'          => 'textfield',
                            'heading'       => esc_html__('Client Professional', 'oz'),
                            'param_name'    => 'client_professional',
                            'std'           => '' // Set default layout
                        ),
                        array(
                            'type'          => 'wiloke_colorpicker',
                            'heading'       => esc_html__('Client Professional Color', 'oz'),
                            'param_name'    => 'client_professional_color',
                            'std'           => '' // Set default layout
                        ),
                        array(
                            'type'          => 'textarea',
                            'heading'       => esc_html__('Testimonial', 'oz'),
                            'param_name'    => 'testimonial',
                            'std'           => '' // Set default layout
                        ),
                        array(
                            'type'          => 'wiloke_colorpicker',
                            'heading'       => esc_html__('Testimonial Color', 'oz'),
                            'param_name'    => 'testimonial_color',
                            'std'           => '' // Set default layout
                        ),
                    )
                )
            )
        ),
        // CountTo
        array(
            'name'	=> esc_html__('Count To', 'oz'),
            'base'	=> 'wiloke_countto',
            'icon'	=> '',
            'show_settings_on_create'	=> true,
            'category'					=> WILOKE_THEMENAME,
            'controls'					=> true,
            'params'    => array(
                array(
                    'type'          => 'textfield',
                    'admin_label'   => true,
                    'heading'       => esc_html__('Value', 'oz'),
                    'param_name'    => 'countto',
                    'std'           => 1240 // Set default layout
                ),
                array(
                    'type'          => 'wiloke_colorpicker',
                    'heading'       => esc_html__('Value Color', 'oz'),
                    'param_name'    => 'countto_color',
                    'std'           => '' // Set default layout
                ),
                array(
                    'type'          => 'wiloke_colorpicker',
                    'heading'       => esc_html__('Divider Color', 'oz'),
                    'param_name'    => 'divider_color',
                    'std'           => '' // Set default layout
                ),
                array(
                    'type'          => 'textfield',
                    'admin_label'   => true,
                    'heading'       => esc_html__('Heading', 'oz'),
                    'param_name'    => 'heading',
                    'std'           => 'Cup Of Coffee' // Set default layout
                ),
                array(
                    'type'          => 'wiloke_colorpicker',
                    'heading'       => esc_html__('Heading Color', 'oz'),
                    'param_name'    => 'heading_color',
                    'std'           => '' // Set default layout
                ),
            )
        ),
        // Team member
        array(
            'name'	=> esc_html__('Team Member', 'oz'),
            'base'	=> 'wiloke_team_member',
            'icon'	=> '',
            'show_settings_on_create'	=> true,
            'category'					=> WILOKE_THEMENAME,
            'controls'					=> true,
            'params'    => array(
                array(
                    'type'          => 'attach_image',
                    'admin_label'   => true,
                    'heading'       => esc_html__('Profile Picture', 'oz'),
                    'description'   => esc_html__('You should consider about the image width. We strongly do not recommend using an image of 1200px in the box of 300px.', 'oz'),
                    'param_name'    => 'profile_picture',
                    'std'           => '' // Set default layout
                ),
                array(
                    'type'          => 'textfield',
                    'admin_label'   => true,
                    'heading'       => esc_html__('Name', 'oz'),
                    'param_name'    => 'name',
                    'std'           => 'Richard Hendricks' // Set default layout
                ),
                array(
                    'type'          => 'wiloke_colorpicker',
                    'admin_label'   => true,
                    'heading'       => esc_html__('Name Color', 'oz'),
                    'param_name'    => 'name_color',
                    'std'           => '' // Set default layout
                ),
                array(
                    'type'          => 'textfield',
                    'admin_label'   => true,
                    'heading'       => esc_html__('Position', 'oz'),
                    'param_name'    => 'position',
                    'std'           => 'Senior Systems Architect' // Set default layout
                ),
                array(
                    'type'          => 'wiloke_colorpicker',
                    'admin_label'   => true,
                    'heading'       => esc_html__('Position Color', 'oz'),
                    'param_name'    => 'position_color',
                    'std'           => '' // Set default layout
                ),
                array(
                    'type'          => 'param_group',
                    'heading'       => esc_html__('Social Networks', 'oz'),
                    'param_name'    => 'social_networks',
                    'params'         => array(
                        array(
                            'type'          => 'iconpicker',
                            'heading'       => esc_html__('Social Icon', 'oz'),
                            'param_name'    => 'icon',
                            'std'           => 'fa fa-facebook' // Set default layout
                        ),
                        array(
                            'type'          => 'wiloke_colorpicker',
                            'admin_label'   => true,
                            'heading'       => esc_html__('Social Icon Color', 'oz'),
                            'param_name'    => 'social_icon_color',
                            'std'           => '' // Set default layout
                        ),
                        array(
                            'type'          => 'textfield',
                            'heading'       => esc_html__('Social Link', 'oz'),
                            'param_name'    => 'link',
                            'std'           => 'https://www.facebook.com/wilokewp/' // Set default layout
                        ),
                    )
                ),
                array(
                    'type'          => 'dropdown',
                    'heading'       => esc_html__('Style', 'oz'),
                    'param_name'    => 'style',
                    'std'           => 'wil-team--2',
                    'value'         => array(
                        esc_html__('Modern', 'oz') => 'wil-team--2',
                        esc_html__('Classic', 'oz') => 'wil-team--1'
                    )
                )
            )
        ),
        // Twitter
        array(
            'name'	=> esc_html__('Twitter Feed', 'oz'),
            'base'	=> 'wiloke_twitter',
            'icon'	=> '',
            'show_settings_on_create'	=> true,
            'category'					=> WILOKE_THEMENAME,
            'controls'					=> true,
            'params'    => array(
                array(
                    'type'          => 'textfield',
                    'admin_label'   => true,
                    'heading'       => esc_html__('Number of Tweets', 'oz'),
                    'description'   => esc_html__('Wiloke Widget plugin is required and Twitter Information must be configured. Please go to Settings -> Wiloke Twitter from the admin panel.', 'oz'),
                    'param_name'    => 'number_of_tweets',
                    'std'           => 3 // Set default layout
                ),
                array(
                    'type'          => 'colorpicker',
                    'heading'       => esc_html__('Content Color', 'oz'),
                    'param_name'    => 'content_color'
                ),
                array(
                    'type'          => 'colorpicker',
                    'heading'       => esc_html__('Link Color', 'oz'),
                    'description'   => esc_html__('You can make a highlight for links in your tweets.', 'oz'),
                    'param_name'    => 'link_color'
                ),
                array(
                    'type'          => 'colorpicker',
                    'heading'       => esc_html__('Tweet Author Color', 'oz'),
                    'param_name'    => 'tweet_author_color'
                )
            )
        ),
        // Pricing table
        array(
            'name' => esc_html__('Pricing', 'oz'),
            'base' => 'wiloke_pricing',
            'icon' => '',
            'show_settings_on_create' => true,
            'category'=> WILOKE_THEMENAME,
            'controls' => true,
            'params' => array(
                array(
                    'type'    => 'dropdown',
                    'param_name' => 'icon_type',
                    'std'     => 'font-icon',
                    'value'   => array(
                        esc_html__('Font Icon', 'oz') => 'font-icon',
                        esc_html__('Image Icon', 'oz') => 'image-icon'
                    ),
                    'heading' => esc_html__('Icon Type', 'oz')
                ),
                array(
                    'type'    => 'iconpicker',
                    'param_name' => 'icon',
                    'admin_label' => true,
                    'std'     => 'icon-basic-lightbulb',
                    'value'   => '',
                    'dependency' => array(
                        'element' => 'icon_type',
                        'value'   => array('font-icon')
                    ),
                    'heading' => esc_html__('Icon', 'oz')
                ),
                array(
                    'type'          => 'wiloke_colorpicker',
                    'heading'       => esc_html__('Icon Color', 'oz'),
                    'dependency' => array(
                        'element' => 'icon_type',
                        'value'   => array('font-icon')
                    ),
                    'param_name'    => 'icon_color'
                ),
                array(
                    'type'    => 'attach_image',
                    'param_name' => 'image',
                    'admin_label' => true,
                    'std'     => '',
                    'value'   => '',
                    'dependency' => array(
                        'element' => 'icon_type',
                        'value'   => array('image-icon')
                    ),
                    'heading' => esc_html__('Upload an image icon', 'oz')
                ),
                array(
                    'type'    => 'textfield',
                    'param_name'=> 'heading',
                    'admin_label' => true,
                    'std'     => 'Basic',
                    'value'   => '',
                    'heading' => esc_html__('Package Name', 'oz')
                ),
                array(
                    'type'          => 'wiloke_colorpicker',
                    'heading'       => esc_html__('Heading Color', 'oz'),
                    'param_name'    => 'heading_color'
                ),
                array(
                    'type'    => 'textfield',
                    'param_name'    => 'currency',
                    'admin_label' => true,
                    'value'   => '',
                    'std'     => '$',
                    'heading' => esc_html__('Currency symbols', 'oz')
                ),
                array(
                    'type'          => 'wiloke_colorpicker',
                    'heading'       => esc_html__('Currency Color', 'oz'),
                    'param_name'    => 'currency_color'
                ),
                array(
                    'type'    => 'textfield',
                    'param_name'    => 'price',
                    'admin_label' => true,
                    'value'   => '',
                    'std'     => '09',
                    'heading' => esc_html__('Price', 'oz')
                ),
                array(
                    'type'          => 'wiloke_colorpicker',
                    'heading'       => esc_html__('Price Color', 'oz'),
                    'param_name'    => 'price_color'
                ),
                array(
                    'type'    => 'textfield',
                    'param_name'    => 'period',
                    'admin_label' => true,
                    'value'   => '',
                    'std'     => 'mo',
                    'heading' => esc_html__('Period', 'oz')
                ),
                array(
                    'type'          => 'wiloke_colorpicker',
                    'heading'       => esc_html__('Period Color', 'oz'),
                    'param_name'    => 'period_color'
                ),
                array(
                    'type'   => 'textarea',
                    'param_name'   => 'features',
                    'heading'=> esc_html__('Features', 'oz'),
                    'description'=> esc_html__('Enter each feature on a line, The text after * will be displayed as its limited. For example *Free Support means the package without Free Support', 'oz'),
                ),
                array(
                    'type'          => 'wiloke_colorpicker',
                    'heading'       => esc_html__('Features Color', 'oz'),
                    'param_name'    => 'features_color'
                ),
                array(
                    'type'       => 'textfield',
                    'param_name' => 'btn_name',
                    'value'   => '',
                    'heading'    => esc_html__('Button Name', 'oz'),
                    'std'        => 'Buy Now'
                ),
                array(
                    'type'          => 'wiloke_colorpicker',
                    'heading'       => esc_html__('Button Color', 'oz'),
                    'param_name'    => 'button_color'
                ),
                array(
                    'type'          => 'wiloke_colorpicker',
                    'heading'       => esc_html__('Button Background Color', 'oz'),
                    'param_name'    => 'button_bg_color'
                ),
                array(
                    'type'       => 'textfield',
                    'param_name' => 'btn_link',
                    'value'   => '',
                    'heading'    => esc_html__('Button Link', 'oz'),
                    'std'        => '#'
                ),
                array(
                    'type'   => 'checkbox',
                    'param_name'   => 'highlight',
                    'value'   => '',
                    'heading'=> esc_html__('Highlight', 'oz'),
                    'std'    => 1
                )
            )
        ),
        // Content Box
        array(
            'name'	=> esc_html__('Content Box', 'oz'),
            'base'	=> 'wiloke_content_box',
            'icon'	=> '',
            'show_settings_on_create'	=> true,
            'category'					=> WILOKE_THEMENAME,
            'controls'					=> true,
            'params'    => array(
                array(
                    'type'          => 'iconpicker',
                    'admin_label'   => true,
                    'heading'       => esc_html__('Icon', 'oz'),
                    'param_name'    => 'icon',
                    'std'           => 'icon-basic-lightbulb' // Set default layout
                ),
                array(
                    'type'          => 'wiloke_colorpicker',
                    'heading'       => esc_html__('Icon Color', 'oz'),
                    'param_name'    => 'icon_color'
                ),
                array(
                    'type'          => 'textfield',
                    'heading'       => esc_html__('Title', 'oz'),
                    'admin_label'   => true,
                    'param_name'    => 'title',
                    'std'           => 'Wiloke' // Set default layout
                ),
                array(
                    'type'          => 'wiloke_colorpicker',
                    'heading'       => esc_html__('Title Color', 'oz'),
                    'param_name'    => 'title_color'
                ),
                array(
                    'type'          => 'textarea',
                    'heading'       => esc_html__('Description', 'oz'),
                    'admin_label'   => true,
                    'param_name'    => 'description',
                    'std'           => 'Writing something talk about you here' // Set default layout
                ),
                array(
                    'type'          => 'wiloke_colorpicker',
                    'heading'       => esc_html__('Description Color', 'oz'),
                    'param_name'    => 'description_color'
                ),
                array(
                    'type'          => 'textfield',
                    'heading'       => esc_html__('Link to', 'oz'),
                    'param_name'    => 'linkto',
                    'std'           => '' // Set default layout
                ),
                array(
                    'type'          => 'dropdown',
                    'heading'       => esc_html__('Target', 'oz'),
                    'description'   => esc_html__('Action when an user click on it.', 'oz'),
                    'param_name'    => 'target',
                    'value'         => array(
                        esc_html__('Self Page', 'oz') => '_self',
                        esc_html__('Open a New Window', 'oz') => '_blank'
                    ),
                    'std'           => '_self' // Set default layout
                ),
                array(
                    'type'          => 'attach_image',
                    'heading'       => esc_html__('Background Image', 'oz'),
                    'param_name'    => 'bg',
                    'std'           => '' // Set default layout
                ),
                array(
                    'type'          => 'dropdown',
                    'heading'       => esc_html__('Alignment', 'oz'),
                    'param_name'    => 'alignment',
                    'value'         => array(
                        esc_html__('Center', 'oz') => 'text-center',
                        esc_html__('Left', 'oz')   => 'text-left',
                        esc_html__('Right', 'oz')   => 'text-right'
                    ),
                    'std'           => 'text-center' // Set default layout
                ),
            )
        ),
        // Design Portfolio
        array(
            'name'	=> esc_html__('Design Portfolio', 'oz'),
            'base'	=> 'wiloke_design_portfolio',
            'icon'	=> '',
            'show_settings_on_create'	=> true,
            'category'					=> WILOKE_THEMENAME,
            'controls'					=> true,
            'admin_enqueue_js'          => array(get_template_directory_uri().'/admin/asset/js/packery.pkgd.min.js'),
            'params'    => array(
                array(
                    'type'          => 'dropdown',
                    'heading'       => esc_html__('Source of projects', 'oz'),
                    'param_name'    => 'source_of_projects',
                    'value'         => array(
                        esc_html__('Pickup Categories', 'oz')    => 'category',
                        esc_html__('Specify Project IDs', 'oz')  => 'custom'
                    ),
                    'std'           => 'category' // Set default layout
                ),
                array(
                    'type'          => 'wiloke_get_list_of_terms',
                    'heading'       => esc_html__('Pickup Categories', 'oz'),
                    'taxonomy'      => 'portfolio_category',
                    'is_multiple'   => true,
                    'description'   => esc_html__('Leave empty to get all. Hold ctrl to select multi-categories.', 'oz'),
                    'param_name'    => 'terms',
                    'save_always'   => true,
                    'std'           => '',
                    'dependency'    => array('element'=>'source_of_projects', 'value'=>array('category'))
                ),
                array(
                    'type'        => 'autocomplete',
                    'heading'     => esc_html__('Projects', 'oz'),
                    'param_name'  => 'include',
                    'settings'    => array(
                        'multiple' => true,
                        'sortable' => true,
                        'groups'   => true,
                    ),
                    'admin_label' => true,
                    'dependency' => array(
                        'element' => 'source_of_projects',
                        'value'   => array('custom'),
                    ),
                ),
                array(
                    'type'          => 'dropdown',
                    'heading'       => esc_html__('Load more function', 'oz'),
                    'param_name'    => 'loadmore_type',
                    'std'           => 'none',
                    'value'         => array(
                        esc_html__('None', 'oz')             => 'none',
                        esc_html__('Load More Button', 'oz') => 'btn',
                        esc_html__('Infinite Scroll', 'oz')  => 'infinite_scroll',
                        esc_html__('Mixed Load More button and Infinite Scroll', 'oz')  => 'mixed_loadmore_infinite_scroll',
                    ),
                    'dependency'    => array('element'=>'source_of_projects', 'value'=>array('category'))
                ),
                array(
                    'type'          => 'dropdown',
                    'heading'       => esc_html__('Order By', 'oz'),
                    'param_name'    => 'order_by',
                    'std'           => 'date',
                    'value'         => array(
                        esc_html__('Date', 'oz')          => 'post_date',
                        esc_html__('Title', 'oz')         => 'title',
                        esc_html__('Author', 'oz')        => 'author',
                        esc_html__('Name', 'oz')          => 'name',
                        esc_html__('Modified', 'oz')      => 'modified',
                        esc_html__('Comment Count', 'oz') => 'comment_count'
                    ),
                ),
                array(
                    'type'          => 'textfield',
                    'heading'       => esc_html__('Loadmore button name', 'oz'),
                    'param_name'    => 'loadmore_btn_name',
                    'std'           => 'Load more',
                    'dependency'    => array('element'=>'source_of_projects', 'value'=>array('custom'))
                ),
                array(
                    'type'          => 'dropdown',
                    'heading'       => esc_html__('Navigation Filter', 'oz'),
                    'param_name'    => 'is_navigation_filter',
                    'std'           => 'yes',
                    'value'         => array(
                        esc_html__('Yes', 'oz')  => 'yes',
                        esc_html__('No', 'oz')   => 'no'
                    )
                ),
                array(
                    'type'          => 'dropdown',
                    'heading'       => esc_html__('Navigation Style', 'oz'),
                    'param_name'    => 'nav_filter_style',
                    'dependency'    => array('element'=>'is_navigation_filter', 'value'=>array('yes')),
                    'std'           => 'wil-work-filter--1 ',
                    'value'         => array(
                        esc_html__('Style 1', 'oz')  => 'wil-work-filter--1',
                        esc_html__('Style 2', 'oz')   => 'wil-work-filter--2'
                    )
                ),
                array(
                    'type'          => 'textfield',
                    'heading'       => esc_html__('All Text', 'oz'),
                    'description'   => esc_html__('Enter a text for this link. When someone click on the link, all projects will be shown. Leave empty to remove it.', 'oz'),
                    'param_name'    => 'all_text',
                    'std'           => 'All Works',
                    'save_always'   => true,
                    'dependency'    => array('element'=>'is_navigation_filter', 'value'=>array('yes'))
                ),
                array(
                    'type'          => 'textfield',
                    'heading'       => esc_html__('Navigation Tooltip', 'oz'),
                    'description'   => esc_html__('This text will be shown when someone hover on it.', 'oz'),
                    'param_name'    => 'navigation_tooltip',
                    'std'           => 'Work Filter',
                    'dependency'    => array('element'=>'is_navigation_filter', 'value'=>array('yes'))
                ),
                array(
                    'type'          => 'dropdown',
                    'heading'       => esc_html__('Toggle Animation', 'oz'),
                    'description'   => esc_html__('Projects will be shown by animation effect on the desktop devices', 'oz'),
                    'param_name'    => 'toggle_animation',
                    'std'           => 'enable',
                    'value'         => array(
                        esc_html__('Enable', 'oz')  => 'enable',
                        esc_html__('Disable', 'oz') => 'disable'
                    )
                ),
                array(
                    'type'          => 'dropdown',
                    'heading'       => esc_html__('Animation on Mobile', 'oz'),
                    'description'   => esc_html__('Projects will be shown by animation effect on the desktop devices', 'oz'),
                    'param_name'    => 'toggle_animation_on_mobile',
                    'std'           => 'enable',
                    'value'         => array(
                        esc_html__('Enable', 'oz')  => 'enable',
                        esc_html__('Disable', 'oz') => 'disable'
                    ),
                    'dependency'    => array('element'=>'toggle_animation', 'value'=>array('enable'))
                ),
                array(
                    'type'          => 'dropdown',
                    'heading'       => esc_html__('Portfolio Animation Effect', 'oz'),
                    'param_name'    => 'portfolio_animation_affect',
                    'std'           => 'wil-anim--work',
                    'value'         => array(
                        esc_html__('Default', 'oz')    => 'wil-anim--work',
                        esc_html__('Fade', 'oz')       => 'wil-fade',
                        esc_html__('Fade Up', 'oz')    => 'wil-fadeUp',
                        esc_html__('Fade Down', 'oz')  => 'wil-fadeDown',
                        esc_html__('Fade Left', 'oz')  => 'wil-fadeLeft',
                        esc_html__('Fade Right', 'oz') => 'wil-fadeRight',
                        esc_html__('Zoom In', 'oz')    => 'wil-zoomIn'
                    ),
                    'dependency'    => array('element'=>'toggle_animation', 'value'=>array('enable'))
                ),
                array(
                    'type'          => 'dropdown',
                    'heading'       => esc_html__('Hover Effect', 'oz'),
                    'description'   => esc_html__('Choose an affect that be used when someone hover on the project item.', 'oz'),
                    'param_name'    => 'hover_effect',
                    'std'           => 'wil-work-item--over',
                    'save_always'   => true,
                    'value'         => array(
                        esc_html__('Hover Dir', 'oz')       => 'wil-work-item--over',
                        esc_html__('Simple Overlay', 'oz')  => 'wil-work-item--static',
                        esc_html__('Grayscale', 'oz')       => 'wil-work-item--grayscale'
                    )
                ),
                array(
                    'type'          => 'wiloke_design_portfolio_choose_layout',
                    'heading'       => '',
                    'param_name'    => 'wiloke_design_portfolio_choose_layout',
                    'options'       => array(
                        'grid' => array(
                            'heading'     => esc_html__('Grid', 'oz'),
                            'img_url'     => get_template_directory_uri() . '/admin/source/design-layout/img/1.jpg',
                            'is_customize' => 'no',
                            'value' => 'eyJpdGVtc19zaXplIjoiY3ViZSwgY3ViZSwgY3ViZSwgY3ViZSwgY3ViZSwgY3ViZSwgY3ViZSwgY3ViZSwgY3ViZSJ9'
                        ),
                        'masonry' => array(
                            'heading'     => esc_html__('Masonry', 'oz'),
                            'img_url'     => get_template_directory_uri() . '/admin/source/design-layout/img/3.jpg',
                            'is_customize' => 'no',
                            'value' => 'eyJpdGVtc19zaXplIjoiY3ViZSwgbGFyZ2UsIHdpZGUsIGN1YmUsIGhpZ2gsIGN1YmUsIGV4dHJhLWxhcmdlLCB3aWRlLCBjdWJlIn0='
                        ),
                        'creative' => array(
                            'heading'     => esc_html__('Creative', 'oz'),
                            'img_url'     => get_template_directory_uri() . '/admin/source/design-layout/img/5.jpg',
                            'is_customize' => 'yes',
                            'value' => 'eyJpdGVtc19zaXplIjoiY3ViZSwgbGFyZ2UsIHdpZGUsIGN1YmUsIGhpZ2gsIGN1YmUsIGV4dHJhLWxhcmdlLCB3aWRlLCBjdWJlIn0='
                        )
                    ),
                    'group'         => esc_html__( 'Choose Layout', 'oz'),
                    'save_always'   => true,
                    'std'           => 'creative' // Set default layout
                ),
                array(
                    'type'             => 'wiloke_design_portfolio_layout',
                    'heading'          => esc_html__('Portfolio', 'oz'),
                    'general_settings' => array(
                        'number_of_posts' => 9
                    ),
                    'devices_settings' => array(
                        'large' => array(
                            'items_per_row'   => 4,
                            'horizontal'      => 0,
                            'vertical'        => 0
                        ),
                        'medium' => array(
                            'items_per_row'   => 3,
                            'horizontal'      => 0,
                            'vertical'        => 0
                        ),
                        'small' => array(
                            'items_per_row'   => 2,
                            'horizontal'      => 0,
                            'vertical'        => 0
                        ),
                        'extra_small' => array(
                            'items_per_row'   => 1,
                            'horizontal'      => 0,
                            'vertical'        => 0
                        )
                    ),
                    'param_name'       => 'wiloke_portfolio_layout',
                    'options'          => array(
                        'creative'     => array(
                            'heading'       => esc_html__('Creative', 'oz'),
                            'img_url'       => get_template_directory_uri() . '/admin/source/design-layout/img/3.jpg',
                            'is_dragdrop'   => 'yes',
                            'is_add_sub_btn'=> 'yes',
                            'params' => array(
                                array(
                                    'type'          => 'select',
                                    'param_name'    => 'items_per_row',
                                    'heading'       => esc_html__('Items Per Row', 'oz'),
                                    'description'   => esc_html__('How many items per row?', 'oz'),
                                    'options'       => array(
                                        5 => 5,
                                        4 => 4,
                                        3 => 3,
                                        2 => 2,
                                        1 => 1
                                    )
                                ),
                                array(
                                    'type'          => 'number',
                                    'param_name'    => 'horizontal',
                                    'heading'       => esc_html__('Horizontal Spacing', 'oz'),
                                    'description'   => ''
                                ),
                                array(
                                    'type'          => 'number',
                                    'param_name'    => 'vertical',
                                    'heading'       => esc_html__('Vertical Spacing', 'oz'),
                                    'description'   => ''
                                ),
                                array(
                                    'type'          => 'number',
                                    'param_name'    => 'amount_of_loadmore',
                                    'heading'       => esc_html__('Amount of Loadmore', 'oz'),
                                    'description'   => esc_html__('In the case Load more projects functionality (General Tab) to be used, this setting is effected. Leave empty means it is equal to number of posts.', 'oz')
                                ),
                            ),
                            'std'   => array(
                                'items_size' => 'large,cube,cube,cube,cube,cube,large,cube,cube'
                            )
                        )
                    ),
                    'group'         => 'Design Layout',
                    'save_always'   => true,
                    'std'           => 'creative' // Set default layout
                ),
                array(
                    'type'          => 'checkbox',
                    'heading'       => esc_html__('Using the design in Load More', 'oz'),
                    'description'   => esc_html__('Checked this box if you want to inherit your designs in Load more functionality. Unchecked to only display Cube style for items that be got from load more action.', 'oz'),
                    'param_name'    => 'is_used_design_in_loadmore',
                    'value'         => array(
                      esc_html__('Yes', 'oz') => 'yes'
                    ),
                    'group'         => 'Design Layout',
                    'std'           => 'yes' // Set default layout
                ),
                array(
                    'type'          => 'wiloke_colorpicker',
                    'heading'       => esc_html__('Navigator Item Text Color', 'oz'),
                    'param_name'    => 'style_navigator_item_color',
                    'group'         => 'Style',
                    'std'           => '' // Set default layout
                ),
                array(
                    'type'          => 'wiloke_colorpicker',
                    'heading'       => esc_html__('Navigator Activated Item Color', 'oz'),
                    'param_name'    => 'style_navigator_activated_item_color',
                    'group'         => 'Style',
                    'std'           => '' // Set default layout
                ),
                array(
                    'type'          => 'wiloke_colorpicker',
                    'heading'       => esc_html__('Project Title Color', 'oz'),
                    'param_name'    => 'style_project_title_color',
                    'group'         => 'Style',
                    'std'           => '' // Set default layout
                ),
                array(
                    'type'          => 'wiloke_colorpicker',
                    'heading'       => esc_html__('Project Category Color', 'oz'),
                    'param_name'    => 'style_project_category_color',
                    'group'         => 'Style',
                    'std'           => '' // Set default layout
                ),
                array(
                    'type'          => 'wiloke_colorpicker',
                    'heading'       => esc_html__('Project Overlay Color', 'oz'),
                    'description'   => esc_html__('Leave empty to use the setting in the ThemeOption', 'oz'),
                    'param_name'    => 'style_project_overlay_color',
                    'group'         => 'Style',
                    'std'           => '' // Set default layout
                ),
                array(
                    'type'          => 'wiloke_colorpicker',
                    'heading'       => esc_html__('Button Color', 'oz'),
                    'param_name'    => 'style_button_color',
                    'group'         => 'Style',
                    'std'           => '' // Set default layout
                )
            )
        ),
        // Blog Layout
        array(
            'name'	=> esc_html__('Blog Layout', 'oz'),
            'base'	=> 'wiloke_blog',
            'icon'	=> '',
            'show_settings_on_create'	=> true,
            'category'					=> WILOKE_THEMENAME,
            'controls'					=> true,
            'params'    => array(
                array(
                    'type'       => 'dropdown',
                    'heading'    => esc_html__('Blog Layout', 'oz'),
                    'param_name' => 'layout',
                    'value'      => array(
                        esc_html__('Creative', 'oz') => 'creative',
                        esc_html__('Alternate Grids', 'oz') => 'style1',
                        esc_html__('Classic', 'oz') => 'style2'
                    ),
                    'std' => 'style2'
                ),
                array(
                    'type'       => 'wiloke_get_list_of_terms',
                    'heading'    => esc_html__('Categories', 'oz'),
                    'is_multiple'=> true,
                    'taxonomy'   => 'category',
                    'description'=> esc_html__('Choose categories that you wanna show on this shortcode. Leave empty to show the latest posts.  Hold ctrl to select multi-categories.', 'oz'),
                    'param_name' => 'categories',
                ),
                array(
                    'type'      => 'dropdown',
                    'heading'   => esc_html__('Pagination Type', 'oz'),
                    'param_name'=> 'pagination_type',
                    'value'     => array(
                        esc_html__('Page numbers', 'oz') => 'page_numbers',
                        esc_html__('None', 'oz') => 'none'
                    ),
                    'std'   => 'page_numbers'
                ),
                array(
                    'type'      => 'dropdown',
                    'heading'   => esc_html__('Kind of pagination', 'oz'),
                    'description'=> esc_html__('Do you want to refresh the page when the user click on a new page or using ajax technology?', 'oz'),
                    'param_name'=> 'kind_of_pagination',
                    'value'     => array(
                        esc_html__('Refresh Page', 'oz') => 'refresh',
                        esc_html__('Ajax', 'oz') => 'ajax'
                    ),
                    'std'   => 'ajax',
                    'dependency'   => array('element'=>'pagination_type', 'value'=>'page_numbers'),
                ),
                array(
                    'type'      => 'textfield',
                    'heading'   => esc_html__('Posts Per Page', 'oz'),
                    'param_name'=> 'posts_per_page',
                    'always_save'=>true,
                    'std'       => get_option('posts_per_page')
                ),
            )
        ),
        // Swiper Slider
        array(
            'name'	=> esc_html__('Swiper Slider', 'oz'),
            'base'	=> 'wiloke_swiper_slider',
            'icon'	=> '',
            'has_autocomplete'	=> true,
            'show_settings_on_create'	=> true,
            'category'					=> WILOKE_THEMENAME,
            'controls'					=> true,
            'params'    => array(
                array(
                    'type'        => 'dropdown',
                    'heading'     => esc_html__( 'Data source', 'oz' ),
                    'param_name'  => 'post_type',
                    'value'       => array(
                        esc_html__('Product', 'oz')    => 'product',
                        esc_html__('Portfolio', 'oz')  => 'portfolio',
                        esc_html__('Post', 'oz')       => 'post',
                        esc_html__('Custom IDs', 'oz') => 'ids',
                        esc_html__('Attachments', 'oz')=> 'attachment'
                    ),
                    'save_always' => true,
                    'description' => esc_html__( 'Select content type for your grid.', 'oz' ),
                    'admin_label' => true,
                    'std'     => 'attachment'
                ),
                array(
                    'type'        => 'autocomplete',
                    'heading'     => esc_html__('Include only', 'oz'),
                    'param_name'  => 'include',
                    'description' => esc_html__('Add posts, pages, etc. by title.', 'oz'),
                    'settings'    => array(
                        'multiple' => true,
                        'sortable' => true,
                        'groups'   => true,
                    ),
                    'admin_label' => true,
                    'dependency' => array(
                        'element' => 'post_type',
                        'value'   => array('ids'),
                    ),
                ),
                array(
                    'type'        => 'autocomplete',
                    'heading'     => esc_html__( 'Narrow data source', 'oz'),
                    'param_name'  => 'taxonomies',
                    'admin_label' => true,
                    'settings'    => array(
                        'multiple'      => true,
                        'min_length'    => 1,
                        'groups'        => true,
                        'unique_values' => true,
                        'display_inline'=> true,
                        'delay'         => 500,
                        'auto_focus'    => true
                    ),
                    'description'        => esc_html__( 'Enter categories, tags or custom taxonomies.', 'oz'),
                    'dependency'         => array(
                        'element'        => 'post_type',
                        'value_not_equal_to' => array(
                            'ids',
                            'attachment'
                        ),
                    ),
                ),
                array(
                    'type'        => 'attach_images',
                    'heading'     => esc_html__( 'Upload Images', 'oz'),
                    'param_name'  => 'attachments',
                    'admin_label' => true,
                    'dependency'  => array(
                        'element' => 'post_type',
                        'value'   => array('attachment'),
                    ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => esc_html__( 'Number of posts', 'oz'),
                    'param_name'  => 'number_of_posts',
                    'std'     => 5,
                    'admin_label' => true,
                    'dependency'  => array(
                        'element' => 'post_type',
                        'value_not_equal_to' => array(
                            'ids',
                            'attachment'
                        ),
                    ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => esc_html__( 'Slides Per View', 'oz'),
                    'param_name'  => 'slides_per_view',
                    'admin_label' => true,
                    'std'     => 1
                ),
                array(
                    'type'        => 'textfield',
                    'std'     => 'large',
                    'heading'     => esc_html__( 'Image Size', 'oz'),
                    'description' => esc_html__( 'For example: large, medium, thumbnail, wiloke_460_460, wiloke_925_925, wiloke_460_925, wiloke_925_460', 'oz'),
                    'admin_label' => true,
                    'param_name'  => 'image_size'
                ),
                array(
                    'type'        => 'dropdown',
                    'heading'     => esc_html__( 'Toggle Pagination', 'oz'),
                    'description' => esc_html__( 'Enable/Disable the pagination', 'oz'),
                    'param_name'  => 'toggle_pagination',
                    'std'     => 'true',
                    'value'       => array(
                        esc_html__('Enable', 'oz')  => 'true',
                        esc_html__('Disable', 'oz') => 'false'
                    )
                ),
                array(
                    'type'        => 'dropdown',
                    'heading'     => esc_html__( 'Toggle Navigation', 'oz'),
                    'description' => esc_html__( 'Enable/Disable the navigation', 'oz'),
                    'param_name'  => 'toggle_navigation',
                    'std'     => 'true',
                    'value'       => array(
                        esc_html__('Enable', 'oz')  => 'true',
                        esc_html__('Disable', 'oz') => 'false'
                    )
                ),
                array(
                    'type'        => 'dropdown',
                    'heading'     => esc_html__( 'Navigation & Pagination Style', 'oz'),
                    'param_name'  => 'navigation_pagination_style',
                    'std'     => 2,
                    'value'       => array(
                        esc_html__('Inside Slider', 'oz')  => 2,
                        esc_html__('Outside Slider', 'oz') => 1
                    )
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => esc_html__( 'Swiper Width', 'oz'),
                    'description' => esc_html__( 'Parameter allows to force Swiper width. Warning: Setting this parameter will make Swiper not responsive.', 'oz'),
                    'param_name'  => 'width',
                    'std'     => ''
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => esc_html__( 'Swiper Height', 'oz'),
                    'description' => esc_html__( 'Parameter allows to force Swiper height. Warning: Setting this parameter will make Swiper not responsive.', 'oz'),
                    'param_name'  => 'height',
                    'std'     => ''
                ),
            )
        ),

        // Google Map
        array(
            'name'	=> esc_html__('Google MAP', 'oz'),
            'base'	=> 'wiloke_google_map',
            'icon'	=> '',
            'show_settings_on_create'	=> true,
            'category'					=> WILOKE_THEMENAME,
            'controls'					=> true,
            'params'    => array(
                array(
                    'type'      => 'textfield',
                    'heading'   => esc_html__('Latitude & Longitude', 'oz'),
                    'description'=> Wiloke::wiloke_kses_simple_html( __('To use the shortcode, you must provided Google API ey, please go to Appearance -> Theme Options -> General Settings to enter in it. Go to this link <a href="http://www.latlong.net/" target="_blank">www.latlong.net</a> to looking for your Latitude and Longitude. For example: 40.712784x-74.005941', 'oz'), true),
                    'param_name'=> 'lat_long',
                    'std'       => ''
                ),
                array(
                    'type'      => 'dropdown',
                    'heading'   => esc_html__('Map Style', 'oz'),
                    'param_name'=> 'style',
                    'std'       => 'grayscale',
                    'value'     => array(
                        'grayscale' => 'grayscale',
                        'blue'      => 'blue',
                        'pink'      => 'pink',
                        'dark'      => 'dark',
                        'cobalt'    => 'cobalt',
                        'brownie'   => 'brownie',
                    )
                ),
                array(
                    'type'      => 'attach_image',
                    'heading'   => esc_html__('Map marker', 'oz'),
                    'param_name'=> 'marker',
                    'std'       => ''
                ),
                array(
                    'type'      => 'textfield',
                    'heading'   => esc_html__('Map Zoom', 'oz'),
                    'param_name'=> 'zoom',
                    'std'       => 13
                ),
                array(
                    'type'      => 'textfield',
                    'heading'   => esc_html__('Map Height', 'oz'),
                    'param_name'=> 'height',
                    'std'       => '500'
                ),
                array(
                    'type'      => 'textarea',
                    'heading'   => esc_html__('Info', 'oz'),
                    'param_name'=> 'info',
                    'std'       => 'Wiloke - Professional WordPress'
                )
            )
        ),

        // Button
        array(
            'name'	=> esc_html__('Button', 'oz'),
            'base'	=> 'wiloke_button',
            'icon'	=> '',
            'show_settings_on_create'	=> true,
            'category'					=> WILOKE_THEMENAME,
            'controls'					=> true,
            'params'    => array(
                array(
                    'type'      => 'textfield',
                    'heading'   => esc_html__('Button Name', 'oz'),
                    'param_name'=> 'name',
                    'admin_label'=>true,
                    'std'       => 'Button'
                ),
                array(
                    'type'      => 'textfield',
                    'heading'   => esc_html__('Button Link', 'oz'),
                    'param_name'=> 'link',
                    'admin_label'=>true,
                    'std'       => '#'
                ),
                array(
                    'type'      => 'dropdown',
                    'heading'   => esc_html__('Button Action', 'oz'),
                    'description'=> esc_html__('Open a new window or self-page when someone click on it', 'oz'),
                    'param_name'=> 'target',
                    'admin_label'=>true,
                    'std'       => '_self',
                    'value'     => array(
                        esc_html__('Self Page', 'oz') => '_self',
                        esc_html__('New Page', 'oz')  => '_blank'
                    )
                ),
                array(
                    'type'      => 'dropdown',
                    'heading'   => esc_html__('Button Style', 'oz'),
                    'param_name'=> 'style',
                    'admin_label'=>true,
                    'std'       => 'wil-btn--defaultstyle',
                    'value'     => array(
                        esc_html__('Strong border button', 'oz')       => 'wil-btn--defaultstyle',
                        esc_html__('A little bit border button', 'oz') => 'wil-btn--rounded',
                        esc_html__('Square Button', 'oz')              => 'wil-btn--square'
                    )
                ),
                array(
                    'type'      => 'dropdown',
                    'heading'   => esc_html__('Button Background', 'oz'),
                    'param_name'=> 'color',
                    'admin_label'=>true,
                    'std'       => 'wil-btn-maincolor',
                    'value'     => array(
                        esc_html__('Main Color', 'oz') => 'wil-btn-maincolor',
                        esc_html__('Grey', 'oz')    => 'wil-btn--gray',
                        esc_html__('Black', 'oz')   => 'wil-btn--dark'
                    )
                ),
                array(
                    'type'      => 'dropdown',
                    'heading'   => esc_html__('Button Size', 'oz'),
                    'param_name'=> 'size',
                    'admin_label'=>true,
                    'std'       => 'wil-btn-medium',
                    'value'     => array(
                        esc_html__('Small', 'oz')    => 'wil-btn--small',
                        esc_html__('Medium', 'oz')   => 'wil-btn-medium',
                        esc_html__('Large', 'oz')    => 'wil-btn--large'
                    )
                ),
            )
        )
    )
);
