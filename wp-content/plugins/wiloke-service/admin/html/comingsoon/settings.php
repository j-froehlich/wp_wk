<div class="wiloke-service-wrapper">
    <h3 class="ui header">ComingSoon Settings</h3>
    <form id="wiloke-comingsoon-settings" class="form ui" action="<?php echo esc_url(admin_url('admin.php?page='.WilokeService::$aSlug['comingsoon'])); ?>" method='POST'>
		<?php wp_nonce_field('wiloke_comingsoon_action', 'wiloke_comingsoon_nonce'); ?>
        <div class="field">
            <label class="control-label" for="toggle"><?php esc_html_e('Toggle', 'wiloke-service'); ?></label>
            <select id="toggle" class="form-control ui fluid dropdown" name="comingsoon[toggle]">
                <option value="0" <?php (bool)selected($aData['toggle'], 0); ?>><?php _e("Disable", "wiloke") ?></option>
                <option value="1" <?php (bool)selected($aData['toggle'], 1); ?>><?php _e("Enable", "wiloke") ?></option>
            </select>
        </div>
        <div class="field">
            <label class="control-label" for="background"><?php esc_html_e('Background', "wiloke-service"); ?></label>
            <div class="input-group wiloke-media-uploader ui action input">
                <input class="pi_insert_val form-control" type="text" name="comingsoon[background]" value="<?php echo esc_url($aData['background']); ?>">
                <button id="background" class="js_upload ui button" data-geturl="true" data-insertto=".pi_wrap_image"><?php esc_html_e('Upload', 'wiloke-service'); ?></button>
            </div>

        </div>
        <div class="field">
            <label for="heading"><?php esc_html_e('Heading', 'wiloke-service'); ?></label>
            <input id="heading" class="form-control" type="text" name="comingsoon[heading]" value="<?php echo esc_attr($aData['heading']); ?>">
        </div>
        <div class="field">
            <label class="control-label" for="datepicker"><?php esc_html_e('Countdown', 'wiloke-service'); ?></label>
            <input id="datepicker" class="form-control" type="text" name="comingsoon[countdown]" value="<?php echo esc_attr($aData['countdown']); ?>" placeholder="yy/mm/dd 2020/01/22">
        </div>
        <div class="field">
            <button type="submit" class="button-save ui green basic button"><?php esc_html_e('Save', 'wiloke-service'); ?></button>
        </div>
    </form>
</div>