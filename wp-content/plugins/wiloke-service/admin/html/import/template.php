<div id="wiloke-template-importer" class="wil-library wiloke-import-panel">

    <div class="wil-library__header">
        <h2><?php esc_html_e('Wiloke Template Importer', 'wiloke-service'); ?></h2>
        <span class="wil-library__close"><i class="dashicons dashicons-no-alt"></i></span>
    </div>

    <ul class="wil-library__nav">
        <li><a href="#" id="wiloke-service-refresh" data-type="template" data-printto="#wiloke-import-templates"><?php esc_html_e('Refresh', 'wiloke-service'); ?></a></li>
    </ul>

    <div class="wil-library__content">

        <div class="wil-library__panel wil-themes active" id="wil-themes">
            <div id="wiloke-import-templates">
                <?php include plugin_dir_path(__FILE__) . 'template-item.php'; ?>
            </div>
            <div id="wiloke-import-demo-optiops" class="wil-library__options">
                <h2><?php esc_html_e('Choose what to import', 'wiloke-service'); ?></h2>
                <p class="help-block"><?php esc_html_e('If you already built the Theme Options before, You can uncheck the Theme Options box.') ?></p>

                <div class="message">
                    
                </div>

                <form id="wiloke-import-options">
                    <p><label><input type="checkbox" name="themeoptions" value="yes" checked> <?php esc_html_e('Theme Options', 'wiloke-service'); ?></label></p>
                    <p><label><input type="checkbox" name="demodata" value="yes" checked> <?php echo esc_html_e('Posts and Pages', 'wiloke-service'); ?></label></p>
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