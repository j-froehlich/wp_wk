<?php
/**
 * This template is using with Visual Composer plugin, It helps to build a page template
 *
 * Template name: Page Builder
 *
 * @package WilokeThemes
 * @subpackage OZ
 * @since 1.0
 */

get_header();
if ( have_posts() ) : while ( have_posts() ) : the_post();
	$aPageSettings  = Wiloke::getPostMetaCaching($post->ID, 'single_page_settings');
	$isContainer = false;
	if ( isset($aPageSettings['page_container']) && $aPageSettings['page_container'] === 'enable' ){
		$isContainer = true;
		echo '<div class="container">';
	}
        the_content();
	if ( $isContainer ){
		echo '</div>';
	}
endwhile; endif; wp_reset_postdata();

get_footer();

