<?php
/**
 * @param Array(key=>val)
 * key: key of aConfigs
 * val: a part of file name: config.val.php
 */
return array(
    array(
        'name'               => esc_html__('Redux Framework', 'oz'),
        'slug'               => 'redux-framework', // The plugin slug (typically the folder name).
        'required'           => true, // If false, the plugin is only 'recommended' instead of required.
        'version'            => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher. If the plugin version is higher than the plugin version installed, the user will be notified to update the plugin.
        'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
        'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
        'is_automatic' => true
    ),
    array(
        'name'               => esc_html__('Visual Composer', 'oz'),
        'slug'               => 'js_composer', // The plugin slug (typically the folder name).
        'source'             => get_template_directory() . '/plugins/js_composer.zip', // The plugin source.
        'required'           => true, // If false, the plugin is only 'recommended' instead of required.
        'version'            => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher. If the plugin version is higher than the plugin version installed, the user will be notified to update the plugin.
        'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
        'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
    ),
    array(
        'name'               => esc_html__('Wiloke Service', 'oz'),
        'slug'               => 'wiloke-service', // The plugin slug (typically the folder name).
        'source'             => get_template_directory() . '/plugins/wiloke-service.zip', // The plugin source.
        'required'           => true, // If false, the plugin is only 'recommended' instead of required.
        'version'            => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher. If the plugin version is higher than the plugin version installed, the user will be notified to update the plugin.
        'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
        'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
    ),
    array(
        'name'               => esc_html__('Wiloke OZ Functionality', 'oz'),
        'slug'               => 'wiloke-oz-functionality', // The plugin slug (typically the folder name).
        'source'             => get_template_directory() . '/plugins/wiloke-oz-functionality.zip', // The plugin source.
        'required'           => true, // If false, the plugin is only 'recommended' instead of required.
        'version'            => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher. If the plugin version is higher than the plugin version installed, the user will be notified to update the plugin.
        'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
        'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
    ),
    array(
        'name'               => esc_html__('Wiloke Sharing Post', 'oz'),
        'slug'               => 'wiloke-sharing-post', // The plugin slug (typically the folder name).
        'source'             => get_template_directory() . '/plugins/wiloke-sharing-post.zip', // The plugin source.
        'required'           => true, // If false, the plugin is only 'recommended' instead of required.
        'version'            => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher. If the plugin version is higher than the plugin version installed, the user will be notified to update the plugin.
        'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
        'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
    ),
    array(
        'name'               => esc_html__('Wiloke OZ Widgets', 'oz'),
        'slug'               => 'wiloke-oz-widgets', // The plugin slug (typically the folder name).
        'source'             => get_template_directory() . '/plugins/wiloke-oz-widgets.zip', // The plugin source.
        'required'           => true, // If false, the plugin is only 'recommended' instead of required.
        'version'            => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher. If the plugin version is higher than the plugin version installed, the user will be notified to update the plugin.
        'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
        'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
    ),
    array(
        'name'               => esc_html__('Contact Form 7', 'oz'),
        'slug'               => 'contact-form-7', // The plugin slug (typically the folder name).
        'required'           => false, // If false, the plugin is only 'recommended' instead of required.
        'version'            => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher. If the plugin version is higher than the plugin version installed, the user will be notified to update the plugin.
        'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
        'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
    ),
    array(
        'name'               => esc_html__('WooCommcerce', 'oz'),
        'slug'               => 'woocommerce', // The plugin slug (typically the folder name).
        'required'           => false, // If false, the plugin is only 'recommended' instead of required.
        'version'            => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher. If the plugin version is higher than the plugin version installed, the user will be notified to update the plugin.
        'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
        'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
    )
);