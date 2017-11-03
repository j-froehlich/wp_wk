<?php

return array(
    array(
        'name'          => esc_html__( 'Main Sidebar', 'oz' ),
        'id'            => 'wiloke-sidebar',
        'description'   => esc_html__( 'Widgets in this area will be shown on Blog, Archive, Search and Single page.', 'oz' ),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    )
);