<?php
/**
 * This template is using with Visual Composer plugin, It helps to build a page template
 *
 * @package WilokeThemes
 * @subpackage OZ
 * @since 1.0
 */

if ( $projectID = get_query_var('wiloke_portfolio') ) {
    /**
     * @hooked render_popup_project
     */
    do_action('wiloke/oz/single-portfolio/ajax', $projectID);
}else {
    get_header();
    if ( have_posts() ) : while ( have_posts() ) : the_post();
        global $wiloke;
        $aPostMeta = Wiloke::getPostMetaCaching($post->ID, 'project_general_settings');
        if ( $aPostMeta['project_style'] == 'inherit' ) {
            $aPostMeta['project_style'] = $wiloke->aThemeOptions['project_style'];
        }
        $gSC = $gFC = '';

        if ( isset($aPostMeta['project_gcfirst_title_f']) && !empty($aPostMeta['project_gcfirst_title_f']) ) {
            $gFC = $aPostMeta['project_gcfirst_title_f'];
            $gSC = $aPostMeta['project_gcfirst_title_s'];
        }elseif ( isset($wiloke->aThemeOptions['project_gcfirst_title_f']['rgba']) ) {
            $gFC = $wiloke->aThemeOptions['project_gcfirst_title_f']['rgba'];
            $gSC = $wiloke->aThemeOptions['project_gcfirst_title_s']['rgba'];
        }

        ?>
        <section class="wil-section">

            <div class="wil-work-detail__header" data-gradientfc="<?php echo esc_attr($gFC); ?>" data-gradientsc="<?php echo esc_attr($gSC); ?>">
                <div class="wil-tb">
                    <div class="wil-tb__cell">
                        <?php
                        /**
                         * Render the header section if it's style1
                         * @since 1.0
                         *
                         * @hooked render_project_title 10
                         * @hooked render_project_info_open 15
                         * @hooked render_project_info 15
                         * @hooked render_project_info_close 15
                         */
                        if ( $aPostMeta['project_style'] == 'style1' ){
                           do_action('wiloke/oz/single-portfolio/project-header', $post, $aPostMeta, $wiloke);
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php
            do_action('wiloke/oz/single-portfolio/before-body', $post, $aPostMeta, $wiloke);
            ?>
            <div class="wil-work-detail__body">
                <div class="container">
                    <?php
                    /**
                     * @since 1.0
                     *
                     * @hooked render_project_info_before_content_open 10
                     * @hooked render_project_intro 15
                     * @hooked render_project_info_before_content_close 20
                     * @hooked render_project_main_content_open 20
                     */
                    do_action('wiloke/oz/single-portfolio/before-main-content', $post, $aPostMeta, $wiloke);
                    ?>
                    <div class="wil-work-detail__content">
                        <?php the_content(); ?>
                    </div>
                    <?php
                    /**
                     * @since 1.0
                     * @hooked render_sharing_box
                     * @hooked render_project_main_content_close
                     */
                    do_action('wiloke/oz/single-portfolio/after-main-content', $post, $aPostMeta, $wiloke);
                    ?>
                </div>
            </div>

            <?php
            /**
             * @hooked render_project_pagination 10
             */
            do_action('wiloke/oz/single-portfolio/after-body', $post, $aPostMeta, $wiloke);
            ?>

        </section>
    <?php
        endwhile;
    else :
        get_template_part('content', 'none');
    endif;
    wp_reset_postdata();
    get_footer();
}