<div id="wiloke-demo-importer" class="wil-library active wiloke-import-panel">

    <h2 class="header dividing ui"><?php esc_html_e('Importing Demo', 'wiloke-service'); ?></h2>
    <?php do_action('wiloke-service/admin/html/import/demo/after-import-title'); ?>
    <button id="wiloke-service-refresh" data-type="demo" data-printto="#wiloke-import-demos" class="ui positive basic button"><?php esc_html_e('Refresh', 'wiloke-service'); ?></button>

    <div class="wil-library__content" style="margin-top: 30px;">

        <div class="wil-library__panel wil-themes active" id="wil-themes">
            <div id="wiloke-import-demos">
                <?php include plugin_dir_path(__FILE__) . 'demo-item.php'; ?>
            </div>
            <div id="wiloke-import-demo-optiops" class="wil-library__options">
                <h2><?php esc_html_e('Choose what to import', 'wiloke-service'); ?></h2>
                <p class="help-block"><?php esc_html_e('If you already built the Theme Options before, You can uncheck the Theme Options box.') ?></p>

                <div class="message">
                    
                </div>
                <div style="margin-bottom: 20px;">
                    <img id="wiloke-service-importing" class="hidden" src="<?php echo plugins_url('wiloke-service/img/importing.gif'); ?>">
                </div>
                <form id="wiloke-import-options">
                    <p><label><input type="checkbox" name="themeoptions" value="yes" checked> <?php esc_html_e('Theme Options', 'wiloke-service'); ?></label></p>
                    <p><label><input type="checkbox" name="demodata" value="yes" checked> <?php esc_html_e('Posts and Pages', 'wiloke-service'); ?></label></p>
                    <p><label><input type="checkbox" name="attachment" value="yes" checked> <?php esc_html_e('Download and import file attachments', 'wiloke-service'); ?></label></p>
                    <input type="hidden" name="file" id="demo-url" />
                    <input type="hidden" name="type" id="demo-type" />
                    
                    <p class="submit">
                        <input id="wiloke-import-start" type="submit" class="button button-primary" value="<?php esc_html_e('Import', 'wiloke-service'); ?>">
                        <a class="button action wil-library__cancel"><?php esc_html_e('Cancel', 'wiloke-service'); ?></a>
                    </p>
                </form>

            </div>
            
        </div>

    </div>

</div>