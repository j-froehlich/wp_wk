<div class="ui top attached tabular menu">
    <?php
    $i = 0;
    foreach ( $this->aTabs as $key => $name ) :
        $active = $i === 0 ? 'active' : '';
    ?>
        <a class="item <?php echo esc_attr($active); ?>" data-tab="<?php echo esc_attr($key); ?>"><?php echo esc_html_e($name); ?></a>
    <?php $i++;endforeach; ?>
</div>