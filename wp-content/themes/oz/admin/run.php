<?php

require_once get_template_directory() . '/admin/class.wiloke.php';

spl_autoload_register( 'Wiloke::autoload' );

function wilokeInit()
{
    $init = Wiloke::instance();
    return $init;
}

do_action('wiloke_action_before_core_init');
$GLOBALS['wiloke'] = wilokeInit();
do_action('wiloke_action_after_core_init');

include WILOKE_PUBLIC_DIR . 'template/vc/wiloke_blog.php';
include WILOKE_PUBLIC_DIR . 'template/vc/wiloke_design_shop.php';
