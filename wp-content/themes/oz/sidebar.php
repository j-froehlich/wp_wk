<?php
    if ( function_exists('is_woocommerce') && ( is_checkout() || is_cart() || is_account_page() ) ) {
        return;
    }
?>
<?php $sidebar = 'wiloke-sidebar'; ?>
<div class="col-xs-12 col-md-4">
    <section class="wil-sidebar">
    <?php
    if ( is_active_sidebar($sidebar) )
    {
        dynamic_sidebar($sidebar);
    }
    ?>
    </section>
</div>