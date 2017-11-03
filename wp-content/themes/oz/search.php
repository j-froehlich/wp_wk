<?php
get_header();
global $wiloke;
$atts['layout'] = $wiloke->aThemeOptions['blog_layout'];
$atts['search'] = get_search_query();
$isMainQuery = true;
wiloke_shortcode_blog($atts, $isMainQuery);
get_footer();