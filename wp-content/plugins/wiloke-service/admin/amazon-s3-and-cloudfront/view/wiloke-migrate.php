<div id="tab-wiloke-migrate" class="aws-content as3cf-tab">
    <?php if ( empty($this->get_setting( 'bucket' )) ) : ?>
        <p class="notice wiloke-service-notice notice-error">
            <?php esc_html_e('You must chosen a Bucket for it, go back to Media Library tab to take one.', 'wiloke-service'); ?>
        </p>
    <?php else : ?>
        <p><?php esc_html_e('Migrate existed files to s3. This action may take awhile, so please be patient.', 'wiloke-service'); ?></p>
        <div class="wiloke-message">

        </div>
        <img class="hidden" style="display: block; max-height: 50px; max-width: 50px; margin-top: 20px; margin-bottom: 20px;" src="<?php echo esc_url(WilokeService::$wilokeServiceURI . 'img/gps.gif'); ?>" alt="" />
        <button id="wiloke-service-migrate-files-to-s3" class="button button-primary"><?php esc_html_e('Migrate', 'wiloke-service'); ?></button>
    <?php endif; ?>
</div>
