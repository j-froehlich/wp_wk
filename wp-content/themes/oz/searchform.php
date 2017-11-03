<?php
/**
 * Template for displaying search forms in OZ
 *
 * @package WilokeThemes
 * @subpackage OZ
 * @since 1.0
 */
?>
<form id="wiloke-searchform" class="search-form wiloke-search-form" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <label>
        <input type="search" placeholder="<?php esc_html_e('Search …', 'oz'); ?>" value="" name="s" title="<?php esc_html_e('Search for:', 'oz'); ?>" class="search-field">
    </label>
    <button type="submit" value="" id="wiloke-search-submittion" class="search-submit"><i class="flaticon flaticon-magnifying-glass"></i></button>
</form>
<div id="wiloke-search-ajax-status">
    <p class="processing hidden"><?php esc_html_e('Processing...', 'oz'); ?></p>
    <p class="not-found hidden"><?php esc_html_e('Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'oz'); ?></p>
</div>