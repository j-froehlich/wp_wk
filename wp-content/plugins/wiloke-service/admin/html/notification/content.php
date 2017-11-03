<?php
$i = 0;
foreach ( $this->aTabs as $key => $name ) :
    $active = $i === 0 ? 'active' : '';
?>
    <div class="ui bottom attached tab segment <?php echo esc_attr($active); ?>" data-tab="<?php echo esc_attr($key); ?>">
        <?php
            if ( $key === 'changelog' ) :
                $aThemeLogs = get_option('wiloke_themes_changelog');

                $slug = isset(WilokeService::$aThemeInfo['slug']) ? strtolower(WilokeService::$aThemeInfo['slug']) : get_template();
                echo '<h3 class="header ui">' . esc_html(WilokeService::$aThemeInfo['name']) . '</h3>';
                if ( isset($aThemeLogs[$slug]) && !empty($aThemeLogs[$slug]['description']) ){
                    echo wp_kses($aThemeLogs[$slug]['description'], array(
                        'a' => array('href'=>array(), 'class'=>array()),
                        'ul'=> array('class'=>array()),
                        'li'=> array('class'=>array())
                    ));
                }else{
                    esc_html_e('The theme is all up to date.', 'wiloke-service');
                }

                $aPluginLogs = get_option('wiloke_plugins_changelog');

                if ( !empty($aPluginLogs) ){
                    foreach ( $aPluginLogs as $aPluginLog ){
                        echo '<h3 class="header ui">' . esc_html($aPluginLog['plugin']) . '</h3>';
                        echo $aPluginLog['description'];
                    }
                }

            endif;
        ?>
    </div>
<?php
    $i++;endforeach;
?>