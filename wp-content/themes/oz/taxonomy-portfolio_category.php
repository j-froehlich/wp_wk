<?php
get_header();
global $wiloke;
?>
    <section class="wil-section">
        <?php if ( empty($wiloke->aThemeOptions['portfolio_page']) ) : ?>
            <?php echo do_shortcode('[wiloke_design_portfolio source_of_projects="category" terms="'.get_queried_object()->term_id.'" is_real_image="yes" portfolio_category="yes"]'); ?>
        <?php else: ?>
            <?php
            $content = get_post($wiloke->aThemeOptions['portfolio_page']);

            if ( !is_wp_error($content) && !empty($content) )
            {
                $content = $content->post_content;
                echo do_shortcode($content);
            }else{
                WilokeAlert::render_alert( esc_html__('This post does not exist', 'oz') );
            }
            ?>
        <?php endif; ?>
    </section>
<?php
get_footer();