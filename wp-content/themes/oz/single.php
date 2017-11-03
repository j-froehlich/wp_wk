<?php
get_header();
    if ( have_posts() ) : while ( have_posts() ) : the_post();
?>
    <section class="wil-section">
        <div class="wil-blog-detail wil-animation">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-md-8">

                        <?php
                        do_action('wiloke/oz/article/before_main_content');
                        ?>

                        <article <?php post_class(); ?>>
                            <?php
                            if ( has_post_thumbnail() ) :
                                $url = get_the_post_thumbnail_url($post->ID, 'large');
                            ?>
                            <div style="background-image: url(<?php echo esc_url($url); ?>)" class="post__media">
                                <img src="<?php echo esc_url($url); ?>" alt="<?php the_title(); ?>" />
                            </div>
                            <?php endif; ?>

                            <div class="post__body">
                                <h1 class="post__title"><?php the_title(); ?></h1>
                                <?php WilokePublic::render_post_meta(); ?>
                                <?php
                                if ( class_exists('WilokeSharingPost') ) {
                                    echo do_shortcode('[wiloke_sharing_post]');
                                }
                                ?>
                                <div class="post__content">
                                    <?php the_content(); ?>
                                    <?php wp_link_pages(); ?>
                                </div>

                                <?php if ( has_tag() ) : ?>
                                <div class="wil-tag"><?php the_tags(esc_html__('Tags: ', 'oz'), '', ''); ?></div>
                                <?php endif; ?>

                            </div>
                        </article>

                        <?php
                        /**
                         * @hooked render_related_articles
                         */
                        do_action('wiloke/oz/article/after_main_content');
                        ?>

                        <?php
                        comments_template();
                        ?>

                    </div>
                    <?php get_sidebar(); ?>
                </div>
            </div>
        </div>
    </section>
<?php
    endwhile; endif; wp_reset_postdata();
get_footer();