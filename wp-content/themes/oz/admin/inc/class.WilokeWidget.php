<?php
/**
 * WilokeWidget Class
 *
 * @category Widget
 * @package Wiloke Framework
 * @author Wiloke Team
 * @version 1.0
 */

if ( !defined('ABSPATH') )
{
    exit;
}

class WilokeWidget
{
    public function register_widgets()
    {
        global $wiloke;

        if ( isset($wiloke->aConfigs['widgets']) )
        {
            if ( isset($wiloke->aThemeOptions['sidebar_additional']) && !empty($wiloke->aThemeOptions['sidebar_additional']) )
            {
                $aParse = explode(',', $wiloke->aThemeOptions['sidebar_additional']);
                foreach ( $aParse as $sidebar )
                {
                    $sidebar = trim($sidebar);
                    $wiloke->aConfigs['widgets'][] = array(
                        'name'          => $sidebar,
                        'id'            => $sidebar,
                        'description'   => esc_html__( 'This is a custom sidebar, which is used in Blog Layout module of Page builder.', 'oz' ),
                        'before_widget' => '<div id="%1$s" class="widget %2$s">',
                        'after_widget'  => '</div>',
                        'before_title'  => '<h4 class="widget_title">',
                        'after_title'   => '</h4>',
                    );
                }
            }

            foreach ( $wiloke->aConfigs['widgets'] as $aWidget )
            {
                register_sidebar($aWidget);
            }
        }
    }
}