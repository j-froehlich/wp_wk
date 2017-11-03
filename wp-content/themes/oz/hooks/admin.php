<?php
$this->_loader->add_action('init', $this->_themeOptions, 'render');

// Admin General
$this->_loader->add_filter('wiloke_post_format_filter_post_formats', $this->_adminGeneral, 'unset_audio_post_format');
$this->_loader->add_action('admin_head', $this->_adminGeneral, 'add_script_to_admin_head');
$this->_loader->add_action('admin_enqueue_scripts', $this->_adminGeneral, 'enqueue_scripts', 69);
$this->_loader->add_action('admin_notices', $this->_adminGeneral, 'notices');
$this->_loader->add_action('init', $this->_adminGeneral, 'register_menu');
$this->_loader->add_filter('tiny_mce_before_init', $this->_adminGeneral, 'fontsize_formats');
$this->_loader->add_filter('mce_buttons_2', $this->_adminGeneral, 'add_font_setting_menus');

// Taxonomy Settings
$this->_loader->add_action('admin_enqueue_scripts', $this->_taxonomy, 'enqueue_scripts');

// Edit  Custom Field Keys
$this->_loader->add_filter('postmeta_form_keys', $this->_adminGeneral, 'add_photo_info_keys', 10, 2);

// portfolio setting
$this->_loader->add_action('edit_form_after_title', $this->_adminGeneral, 'add_toggle_live_builder_settings_button', 1);
$this->_loader->add_action('edit_form_after_title', $this->_adminGeneral, 'add_save_and_settings_btn', 1);
$this->_loader->add_action('edit_form_after_title', $this->_adminGeneral, 'render_entries_meta', 1);
$this->_loader->add_filter('wiloke/admin/inc/metaboxes/filter_metabox_items', $this->_adminGeneral, 'add_set_theme_color', 10, 2);

// Theme Options
$this->_loader->add_action('init', $this->_themeOptions, 'update_theme_options');

// Hook into jetpack gallery
$this->_loader->add_action('wp_enqueue_media', $this->_adminGeneral, 'set_gallery_spacing');
$this->_loader->add_action('admin_footer', $this->_adminGeneral, 'gallery_spacing_settings');