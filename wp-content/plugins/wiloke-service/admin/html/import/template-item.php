<?php  if ( !$oTemplateDemos ) : ?>
<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title"><?php esc_html_e('Error', 'wiloke-service'); ?></h3>
  </div>
  <div class="panel-body">
    <?php echo get_option(WilokeService::$wilokeServiceError); ?>
  </div>
</div>
<?php else: ?>
    <ul class="wil-library__list">
        <?php   foreach ( $oTemplateDemos as $oDemo ) :   ?>
        <li>
            <div class="wil-library-item">

                <div class="wil-library__thumb">
                    <img src="<?php echo esc_url(WilokeService::$awsUrl . $oDemo->screenshot); ?>" alt="<?php echo esc_attr($oDemo->name); ?>" />
                    <div class="wil-library__actions">
                        <div class="tb-cell">
                            <a href="<?php echo esc_url($oDemo->url); ?>" target="_blank"><i class="dashicons dashicons-admin-links"></i></a>
                        </div>
                    </div>
                
                </div>
                
                <div class="wil-library__entry">
                    <div class="wil-library__meta">
                        <span class="wil-library__install">
                            <a href="<?php echo urlencode($oDemo->file); ?>" data-type="<?php echo esc_attr($oDemo->type); ?>" class="wiloke-start-import-template wil-library__install"><i class="dashicons dashicons-download"></i><?php esc_html_e('Import', 'wilok-service'); ?></a>
                        </span>
                    </div>
                    <h4 class="wil-library__name"><?php echo esc_html($oDemo->name); ?></h4>
                </div>
            </div>
        </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>