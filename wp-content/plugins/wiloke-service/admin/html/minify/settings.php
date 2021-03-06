<div class="wiloke-service-wrapper">
    <h3 class="ui header dividing"><?php esc_html_e('Minify Settings', 'wiloke-service'); ?></h3>
    <div class="ui message error"><?php esc_html_e('Putting all theme\'s scripts into one file, it helps to speed up your website. But sometimes, it may break WordPress plugins, theme or whole website. If you get this issue, please disable this feature.', 'wiloke-service'); ?></div>

    <form id="wiloke-compress-settings" class="form ui" action="<?php echo esc_url(admin_url('admin.php?page='.WilokeService::$aSlug['minify'])); ?>" method='POST' data-homeurl="<?php echo esc_url(get_option('home')); ?>">
        <div class="ui icon message success">
            <i class="icon pied piper alternate"></i>
            <div class="content">
                <p><?php esc_html_e('Congratulation! The scripts have been compressed.', 'wiloke-service'); ?></p>
            </div>
        </div>

        <div class="ui icon message error">
            <i class="icon pied piper alternate"></i>
            <div class="content">
                <p><?php esc_html_e('Oops! Something went to wrong. Please report this issue to sale@wiloke.com.', 'wiloke-service'); ?></p>
            </div>
        </div>

        <?php wp_nonce_field('wiloke_minify_action', 'wiloke_minify_nonce'); ?>
        <div class="field">
            <div class="ui toggle checkbox">
                <input id="minify-javascript" type="checkbox" name="wiloke_minify[javascript]" value="1" <?php checked($aData['javascript'], 1); ?> />
                <label for="minify-javascript">
                    Minify JavaScripts
                </label>
            </div>
        </div>
        <div class="field">
            <div class="ui toggle checkbox">
                <div class="checkbox">
                    <input id="minify-css" type="checkbox" name="wiloke_minify[css]" value="1" <?php checked($aData['css'], 1); ?> />
                    <label for="minify-css">Minify CSS</label>
                </div>
            </div>
        </div>
        <div class="field">
            <div class="ui toggle checkbox">
                <div class="checkbox">
                    <input id="toggle-add-additional-scripts" type="checkbox" name="wiloke_minify[toggle_custom_scripts]" value="1" <?php checked($aData['toggle_custom_scripts'], 1); ?> />
                    <label for="toggle-add-additional-scripts">Add Additional Scripts</label>
                </div>
            </div>
        </div>
        <div id="add-custom-js-wrapper" class="field hidden">
            <label for="add-more-js">Add Additional Scripts</label>
            <textarea id="add-more-js" name="wiloke_minify[additional_js]"><?php echo is_array($aData['additional_js']) ? implode("\n", $aData['additional_js']) : esc_html($aData['additional_js']); ?></textarea>
        </div>
        <div id="add-custom-css-wrapper" class="field hidden">
            <label for="add-more-js">Add Additional CSS</label>
            <textarea id="add-more-js" name="wiloke_minify[additional_css]"><?php echo is_array($aData['additional_css']) ? implode("\n", $aData['additional_css']) : esc_html($aData['additional_css']); ?></textarea>
        </div>
        <div class="field">
            <button type="submit" class="ui button green button-save inverted"><?php esc_html_e('Compress', 'wiloke-service'); ?></button>
        </div>
    </form>
</div>