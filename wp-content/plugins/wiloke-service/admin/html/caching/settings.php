<div class="well bs-component col-md-8 col-md-offset-2">
    <fieldset>
        <legend><?php esc_html_e('Redis Cache', 'wiloke-service'); ?></legend>
        <form id="wiloke-redis-settings" action="<?php echo esc_url(admin_url('admin.php?page='.WilokeService::$aSlug['caching'])); ?>" method='POST'>
            <?php wp_nonce_field('wiloke_redis_caching_action', 'wiloke_redis_caching_nonce'); ?>
            <div class="form-group">
                <label class="control-label" for="toggle"><?php esc_html_e('Cache Interval (seconds)', 'wiloke-service'); ?></label>
                <input type="text" name="wiloke_caching[redis][caching_interval]" value="<?php echo absint(self::$aOptions['redis']['caching_interval']); ?>" />
            </div>
            <div class="form-group">
                <button type="submit" class="button-save btn btn-primary"><?php esc_html_e('Save', 'wiloke-service'); ?></button>
            </div>
        </form>
    </fieldset>
</div>
<div class="well bs-component col-md-8 col-md-offset-2">
    <fieldset>
        <legend><?php esc_html_e('Purge Cache', 'wiloke-service'); ?></legend>
        <form action="<?php echo esc_url(admin_url('admin.php?page='.WilokeService::$aSlug['caching'].'&amp;target=redis')); ?>" method='POST'>
            <?php wp_nonce_field('wiloke_redis_caching_action', 'wiloke_redis_caching_nonce'); ?>
            <div class="form-group">
                <?php $message = Wiloke::getTemporarySession('wiloke_service_purged_redis'); ?>
                <?php if ( !empty($message) ) : ?>
                <div class="alert alert-success"><?php echo esc_html($message); ?></div>
                <?php endif; ?>
                <input type="submit" class="btn btn-primary" value="<?php esc_html_e('Purge Redis Cache', 'wiloke-service'); ?>" />
            </div>
        </form>
    </fieldset>
</div>