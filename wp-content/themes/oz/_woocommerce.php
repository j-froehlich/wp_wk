<?php
/**
 * This template helps to show the default page
 *
 * @package WilokeThemes
 * @subpackage OZ
 * @since 1.0
 */

get_header();
if ( have_posts() ) : while ( have_posts() ) : the_post();
    ?>
    <section class="wil-section pt-0">
        <div class="wil-blog-detail wil-animation">
            <div class="container">
                <div class="row">
                    <div class="<?php echo esc_attr(apply_filters('wiloke/oz/page/main_content_wrapper', 'col-xs-12 col-md-8')); ?>">
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
                                <?php if ( !function_exists('is_woocommerce') || (function_exists('is_woocommerce') && ( !is_woocommerce() && !is_cart() && !is_checkout() && !is_account_page() ) ) ) : ?>
                                    <h1 class="post__title"><?php the_title(); ?></h1>
                                    <?php
                                    if ( class_exists('WilokeSharingPost') ) {
                                        echo do_shortcode('[wiloke_sharing_post]');
                                    }
                                    ?>
                                <?php endif; ?>

                                <div class="post__content">
                                    <?php the_content(); ?>
                                    <?php wp_link_pages(); ?>
                                </div>

                            </div>
                        </article>
                        <?php comments_template(); ?>
                    </div>
                    <?php get_sidebar(); ?>
                </div>
            </div>
        </div>
    </section>
    <?php
endwhile; endif; wp_reset_postdata();
get_footer();