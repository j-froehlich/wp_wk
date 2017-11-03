<div class="wiloke-service-wrapper ui two column centered grid">
    <div class="column">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="header ui dividing"><?php esc_html_e('Welcome!', 'wiloke-service'); ?></h3>
            </div>
            <div class="ui segment">
                <p><?php esc_html_e('First of all, Thanks for using our product!', 'wiloke-service'); ?> </p>
                <p><?php esc_html_e('Wiloke Service is a bridge between Wiloke and your site. You can get automatic update Wiloke\'s themes and Wiloke\'s plugins by using this tool. We also have a long plan to develop our products to you through it.', 'wiloke-service'); ?></p>
            </div>
        </div>

        <div id="wiloke-service-wrapper">
            <div class="well bs-component">
                <form class="ui form" method="POST">
                    <?php
                    $alert = get_option(WilokeService::$wilokeServiceError);
                    if ( $alert ){
                        $msg = esc_html__('Error: ', 'wiloke-service') . esc_html($alert);
                        $classStatus = 'error';
                    }else if ( empty($secretToken) ){
	                    $msg = esc_html__('Just one last step to activate Wiloke Service!', 'wiloke-service');
	                    $classStatus = 'info';
                    }else{
	                    $msg = esc_html__('Congrats! You are using Wiloke Service.', 'wiloke-service');
	                    $classStatus = 'success';
                    }
                    ?>
                    <p style="margin-top: 20px; margin-bottom: 20px;" class="ui message visible <?php echo esc_attr($classStatus); ?>"><?php echo esc_html($msg); ?></p>
                    <?php if ( defined('WILOKE_DISABLE_REQUEST') && WILOKE_DISABLE_REQUEST ) : ?>
                    <div class="ui message warning visible"><?php esc_html_e('Temporary Disable Request feature is enabling. To turn off this feature, please go to wp-config.php and switch WILOKE_DISABLE_REQUEST to false.', 'wiloke-service'); ?></div>
                    <?php endif; ?>
                    <?php wp_nonce_field('wiloke-update-nonce', 'wiloke-update-action'); ?>

                    <div class="field">
                        <textarea class="form-control" rows="20" id="token-secret" name="wiloke_update[secret_token]" placeholder="<?php esc_html_e('Enter in your Wiloke Access Token', 'wiloke-service'); ?>"><?php echo esc_textarea($secretToken); ?></textarea>
                        <div class="ui info message"><?php esc_html_e('Need help getting your Access Keys?', 'wiloke-service'); ?>  <a href="http://wiloke.net" target="_blank"><?php esc_html_e('Check out the Quick Start Guide →', 'wiloke-service'); ?></a></div>
                    </div>

                    <div class="form-group">
                      <div class="col-lg-10 col-lg-offset-2">
                        <button type="submit" name="submit" id="submit" class="ui primary basic button">Submit</button>
                      </div>
                    </div>

                </form>
            </div>
        </div>

    </div>
</div>