<?php
$aHeaderImage = '';
if ( $atts['layout'] == 'creative' ) {
    $class = '';
    $thumbnailSize = 'large';
}else{
    if ( $order <= 2 && ($atts['layout'] == 'style2') ) {
        $class = 'col-xs-12 col-sm-6 col-md-6';
        $thumbnailSize = 'wiloke_925_925';
    }else{
        $class = 'col-xs-12 col-md-6 col-lg-4';
        if ( $order == 5 ) {
            $order = 0;
        }
        $thumbnailSize = 'wiloke_925_925';
    }
}

$src = '';
$size = '';

if(has_post_thumbnail($post->ID)){
    $aHeaderImage = Wiloke::generateSrcsetImg(get_post_thumbnail_id($post->ID), $thumbnailSize);
}

$link = get_permalink($post->ID);
$title = get_the_title();

if ( is_sticky($post->ID) ){
    $postClass = 'post sticky-post';
}else{
    $postClass = 'post';
}
?>
<?php if ( $atts['layout'] != 'creative' ) : ?>
<div class="<?php echo esc_attr($class); ?>">
<?php endif; ?>
    <div class="<?php echo esc_attr($postClass); ?>">
        <?php if ( !empty($aHeaderImage) ) : ?>
        <div data-original="<?php echo esc_url($aHeaderImage['main']['src']); ?>" class="post__media lazy">
            <a href="<?php echo esc_url($link) ?>">
                <img src="<?php echo esc_url($aHeaderImage['main']['src']); ?>" srcset="<?php echo esc_attr($aHeaderImage['srcset']); ?>" alt="<?php echo esc_attr($title); ?>" width="<?php echo esc_attr($aHeaderImage['main']['width']); ?>" height="<?php echo esc_attr($aHeaderImage['main']['height']); ?>" sizes="<?php echo esc_attr($aHeaderImage['sizes']); ?>" />
            </a>
        </div>
        <?php endif; ?>

        <div class="post__body">
            <h2 class="post__title"><a href="<?php echo esc_url($link); ?>"><?php Wiloke::wiloke_kses_simple_html($title); ?></a></h2>
            <?php WilokePublic::render_post_meta(); ?>
        </div>
    </div>
<?php if ( $atts['layout'] != 'creative' ) : ?>
</div>
<?php endif; ?>