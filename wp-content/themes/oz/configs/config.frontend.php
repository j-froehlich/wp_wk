<?php

return array(
    'scripts' => array(
        'is_google_map'   => array('js', 'is_url'=>true, 'is_google_map'=>true),
        'hoverdir'        => array('js', 'jquery.hoverdir.min.'),
        'lazyload'        => array('js', 'jquery.lazyload.min.'),
        'imagesloaded'    => array('js', 'imagesloaded', 'is_wp_lib'=>true),
        'jquery-isotope'  => array('js', 'isotope.pkgd.min.'),
        'easing'          => array('js', 'jquery.easing.min.'),
        'smoothState'     => array('js', 'jquery.smoothState.min.'),
        'appear'          => array('js', 'jquery.appear.min.'),
        'swiper'          => array('both', 'swiper.min.'),
        'bootstrap'       => array('css', 'grid.min.'),
        'magnific-popup'  => array('both', 'magnific-popup.min.'),
        'odometer-theme-default' => array('css', 'odometer-theme-default.min.'),
        'odometer'        => array('js', 'odometer.min.'),
        'linea-font'      => array('css', 'linea-font.min.'),
        'font-awesome'    => array('css', 'font-awesome.min.'),
        'oz-googlefont'   => array('Poppins:400,700|Questrial', 'is_googlefont'=>true),
        'oz-main-style'   => array('css', 'style.', 'default'=>true, 'is_compress'=>true),
        'oz-script'       => array('js', 'scripts.', 'default'=>true, 'is_compress'=>true)
    ),
    'register_nav_menu'  => array(
        'menu'  => array(
            array(
                'key'   => 'oz_menu',
                'name'  => esc_html__('OZ Menu', 'oz'),
            )
        ),
        'config'=> array(
            'oz_menu'=> array(
                'theme_location'  => 'oz_menu',
                'name'            => esc_html__('OZ Menu', 'oz'),
                'menu'            => '',
                'container'       => 'nav',
                'container_class' => 'wil-navigation',
                'container_id'    => 'wiloke-oz-menu',
                'menu_class'      => 'wil-menu-list',
                'menu_id'         => '',
                'echo'            => true,
                'before'          => '',
                'after'           => '',
                'link_before'     => '',
                'link_after'      => '',
                'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                'depth'           => 0,
                'walker'          => ''
            )
        )
    ),
    'portfolio_item_sizes' => array(
        'fg' => 'large',
        'sg' => 'large',
        'tg' => array(
            'cube'          => 'wiloke_460_460',
            'large'         => 'wiloke_925_925',
            'high'          => 'wiloke_460_925',
            'wide'          => 'wiloke_925_460',
            'extra-large'   => 'large'
        ),
        'extra_small' => 'wiloke_460_460'
    )
);