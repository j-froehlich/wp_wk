<?php
get_header();
global $wiloke;
wiloke_shortcode_blog(array('layout'=>$wiloke->aThemeOptions['blog_layout']), true);
get_footer();
