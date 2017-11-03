<?php
if ( !$this->oDemos || is_wp_error($this->oDemos) || ( is_string($this->oDemos) && $this->oDemos == 'temporary_disable_check' ) ) :
?>
    <div class="ui message warning visible">
        <?php
        if ( empty(WilokeService::$wilokeApiKey) ) {
            printf( __('An access token is required. Please <a href="%s">click on me</a> and enter in your access token.'), esc_url(admin_url('admin.php?page=wiloke-service')) );
        }else{
            esc_html_e('We didn\'t find any demo yet! Please try to click on Refresh button, If you still get the same result, it means this theme does not support the feature.', 'wiloke-service');
        }
        ?>
    </div>
<?php else: ?>
    <p style="padding: 20px;"><i><?php esc_html_e('If you get a notice of failure while importing or it takes more than 10 minutes, please try to access your hosting and grant write permission to wp-content folder.', 'wiloke-service'); ?></i></p>
    <ul class="wil-library__list">
        <?php
            foreach ( $this->oDemos as $key => $oDemo ) :
                if ( $oDemo->type == 'template' ) {
                    continue;
                }
        ?>
        <li>
            <div class="wil-library-item">
                <div class="wil-library__thumb">
                    <img src="<?php echo esc_url(WilokeService::$awsUrl . $oDemo->screenshot); ?>" alt="<?php echo esc_attr($oDemo->name); ?>" />
                    <div class="wil-library__actions">
                        <div class="tb-cell">
                            <a href="<?php echo esc_url($oDemo->url); ?>" target="_blank"><i class="dashicons dashicons-admin-links"></i></a>
                            <a href="<?php echo urlencode($oDemo->file); ?>" data-type="<?php echo esc_attr($oDemo->type); ?>" class="wil-btn_options"><i class="dashicons dashicons-admin-generic"></i></a>
                        </div>
                    </div>

                </div>

                <div class="wil-library__entry">
                    <h4 class="wil-library__name"><?php echo esc_html($oDemo->name); ?></h4>
                </div>
            </div>
        </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>