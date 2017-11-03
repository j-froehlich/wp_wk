<?php
get_header();
    global $wiloke;
    $atts['layout'] = $wiloke->aThemeOptions['blog_layout'];
    $isMainQuery = true;
    if ( is_category() ){
        $atts['categories'] = get_queried_object()->cat_ID;
    }elseif ( is_tag() ){
        $atts['tags'] = get_queried_object()->term_id;
    }elseif (is_author()) {
        $atts['authors'] = get_the_author_meta('ID');
    }
    wiloke_shortcode_blog($atts, $isMainQuery);
get_footer();