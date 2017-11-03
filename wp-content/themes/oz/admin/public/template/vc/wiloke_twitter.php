<?php
function wiloke_shortcode_twitter($atts){
    $aTwitter = get_option('_pi_twitter_settings');

    if ( !$aTwitter || !is_file( WP_PLUGIN_DIR . '/wiloke-widgets/modules/pitwitterfeed/twitter/twitteroauth.php' ) ) {
        if ( current_user_can('edit_theme_options') ) {
            ob_start();
            WilokeAlert::render_alert( esc_html__('Twitter Information have not configured yet. Please go to Settings -> Wiloke Twitter to do it. If you don\'t see the menu item, please go to Plugins -> Add New / Activate Wiloke Widget plugin.', 'oz'), 'alert' );
            $content = ob_get_contents();
            ob_end_clean();
            return $content;
        }
    }

    $atts = shortcode_atts(
        array(
            'number_of_tweets'   => 3,
            'css'                => '',
            'link_color'         => '',
            'content_color'      => '',
            'tweet_author_color' => '',
            'extract_class'      => ''
        ),
        $atts
    );

    $wrapperClass = 'wil-swiper wil-animation ' . $atts['extract_class'] . ' ' . vc_shortcode_custom_css_class($atts['css'], ' ');

    if ( !class_exists('TwitterOAuth') ) {
        require_once WP_PLUGIN_DIR . '/wiloke-widgets/modules/pitwitterfeed/twitter/twitteroauth.php';
    }


    $initTWitter = new TwitterOAuth($aTwitter['consumer_key'], $aTwitter['consumer_secret'], $aTwitter['access_token'], $aTwitter['access_token_secret'], $aTwitter['cache_interval']);

    $initTWitter->ssl_verifypeer = true;

    $tweets = $initTWitter->get('statuses/user_timeline', array('screen_name' => $aTwitter['username'], 'include_rts' => 'false', 'count' => $atts['number_of_tweets']));

    ob_start();
    if ( !empty($tweets) ) :
        $tweets = json_decode($tweets);

        if( is_array($tweets) ) :
            ?>
            <div data-swiper-pagination="false" data-swiper-navigation="true" class="<?php echo esc_attr($wrapperClass); ?>">
                <div class="swiper-wrapper">
                    <?php foreach($tweets as $control) : ?>
                        <div class="swiper-slide">
                            <div class="wil-tweet">
                                <div class="wil-tweet__header">
                                    <div class="wil-tweet__icon">
                                        <div class="wil-icon--bg-gradient"><i class="fa fa-twitter"></i></div>
                                    </div>
                                </div>
                                <div class="wil-tweet__divider"><span class="wil-tweet__divider-item"></span><span class="wil-tweet__divider-item"></span><span class="wil-tweet__divider-item"></span></div>
                                <div class="wil-tweet__body">
                                    <p style="color: <?php echo esc_attr($atts['content_color']); ?>">
                                    <?php
                                        $status =  preg_replace('/http?s:\/\/([^\s][^,]+)/i', '<a style="color:'.esc_attr($atts['link_color']).';" href="http://$1" target="_blank">$1</a>', $control->text);
                                        Wiloke::wiloke_kses_simple_html($status);
                                    ?>
                                    </p>
                                </div>
                                <a href="<?php echo esc_url('https://twitter.com/'.$aTwitter['username']); ?>"><cite class="wil-tweet__name" style="color: <?php echo esc_attr($atts['tweet_author_color']); ?>">@<?php echo esc_html($aTwitter['username']); ?></cite></a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="wil-swiper__pagination"></div>
                <div class="wil-swiper__button">
                    <div class="wil-swiper__button-item wil-swiper__button-prev"><i class="fa fa-angle-left"></i></div>
                    <div class="wil-swiper__button-item wil-swiper__button-next"><i class="fa fa-angle-right"></i></div>
                </div>
            </div>
            <?php
        endif;
    else:
        if ( current_user_can('edit_theme_options') ) {
            WilokeAlert::render_alert(esc_html__('There are no tweets yet.', 'oz'));
        }
    endif;
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}