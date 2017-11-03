<?php get_header(); ?>
<div id="wiloke-no-results" class="no-results not-found wil-ef">

	<header class="page-header">
		<h1 class="page-title"><?php echo esc_html__('Nothing Found', 'oz') ?></h1>
	</header>

	<div class="page-content">

		<p><?php echo esc_html__('Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'oz') ?></p>

		<form class="search-form wiloke-search-form" method="get" action="<?php echo esc_url(home_url('/')); ?>">
			<label>
			<input type="search" placeholder="<?php echo esc_html__('Search …', 'oz') ?>" value="" name="s" title="<?php echo esc_html__('Search for:', 'oz') ?>" class="search-field">
			</label>
			<button type="submit" value="" id="wiloke-search-submittion" class="search-submit"><i class="flaticon flaticon-magnifying-glass"></i></button>
		</form>

		<div id="wiloke-search-ajax-status">
			<p class="processing hidden"><?php esc_html_e('Processing...', 'oz') ?></p>
			<p class="not-found hidden"><?php esc_html_e('Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'oz') ?></p>
		</div>

	</div>
</div>
<?php get_footer(); ?>
