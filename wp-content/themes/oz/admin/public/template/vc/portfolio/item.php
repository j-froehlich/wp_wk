<?php
if ( !isset($the_post) )
{
    global $post;
}else{
    $post = $the_post;
}

$oTerms = get_the_terms($post->ID, 'portfolio_category');

if ( isset($atts['style_project_overlay_color']) && !empty($atts['style_project_overlay_color']) ){
    $overLayoutColor = $atts['style_project_overlay_color'];
}else{
    $overLayoutColor = $wiloke->aThemeOptions['portfolio_hover_overlay']['rgba'];
}

if ( $atts['is_real_image'] != 'yes')
{
    $size = 'wiloke_925_925';
    if ( !isset($aPortfolioData['items_size'][$wilokeI]) || empty($aPortfolioData['items_size'][$wilokeI]) ) {
        $numberOfItems = count($aPortfolioData['items_size']);
        if ( $wilokeI/$numberOfItems > 0 ) {
            $quotient = floor($wilokeI/$numberOfItems);
            $wilokeI = $wilokeI - ($quotient*$numberOfItems + 1);
        }
    }

    if ( wp_is_mobile() ){
        $size = 'wiloke_460_460';
    }else{
        if ( ($aPortfolioData['items_size'][$wilokeI] == 'cube') && $isCropImage ){
            $size = 'wiloke_460_460';
        }
    }

    $class = Wiloke::wiloke_terms_slug($post->ID, 'portfolio_category', ' ', $oTerms) . ' ' . $aPortfolioData['items_size'][$wilokeI];
}else{
    $size = 'medium';
    $class = Wiloke::wiloke_terms_slug($post->ID, 'portfolio_category', ' ', $oTerms);
}

if ( has_post_thumbnail($post->ID) )
{
    $thumbnail = get_the_post_thumbnail_url($post, $size);
}else{
    $thumbnail = get_template_directory_uri() . '/img/portfolio-item.jpg';
}

$portfolioPopupUrl = add_query_arg('wiloke_portfolio', $post->ID, get_permalink());
$postFormat = get_post_format($post->ID);

$portfolioPopupUrl = add_query_arg('wiloke_portfolio', $post->ID, get_permalink());
$postFormat = get_post_format($post->ID);

?>
<div class="grid-item <?php echo esc_attr($class); ?>" data-postids="<?php echo esc_attr($post->ID); ?>">

    <div class="grid-item__inner">
        <div class="grid-item__content-wrapper">
            <div class="wil-work-item <?php echo esc_attr($atts['hover_effect']); ?>">
                <div class="wil-work-item__anim">
                    <div class="wil-work-item__inner">
                        <div style="background-image: url(<?php echo esc_url($thumbnail); ?>);" class="wil-work-item__media">
                            <a href="<?php the_permalink(); ?>" data-hovercolor="<?php echo esc_attr($overLayoutColor); ?>">
                                <?php the_post_thumbnail($size); ?>
                            </a>
                        </div>
                        <div class="wil-work-item__content" data-hovercolor="<?php echo esc_attr($overLayoutColor); ?>">
                            <a href="<?php the_permalink(); ?>" class="wil-work-item__link">
                                <div class="wil-tb">
                                    <div class="wil-tb__cell">
                                        <h2 class="wil-work-item__title" style="color: <?php echo esc_attr($atts['style_project_title_color']); ?>;"><?php the_title(); ?></h2>
                                        <div class="wil-work-item__cat"><span style="color: <?php echo esc_attr($atts['style_project_category_color']); ?>"><?php echo Wiloke::wiloke_terms_name($post->ID, 'portfolio_category', ', ', $oTerms); ?></span></div>
                                    </div>
                                </div>
                            </a>
                            <?php if ( $atts['hover_effect'] !== 'wil-work-item--grayscale' ) : ?>
                            <a href="<?php echo esc_url($portfolioPopupUrl); ?>" data-title="<?php the_title(); ?>" data-thumbnail="<?php echo esc_url(get_the_post_thumbnail_url($post->ID, 'large')) ?>" class="wil-work-item__quickview" data-type="ajax" data-imgs="<?php echo esc_attr(get_the_post_thumbnail_url($post->ID), 'large'); ?>" data-link="<?php the_permalink(); ?>" data-titlecolor="<?php echo esc_attr($atts['style_project_title_color']); ?>" data-buttoncolor="<?php echo esc_attr($atts['style_button_color']); ?>" data-hovercolor="<?php echo esc_attr($overLayoutColor); ?>">
                                <i class="flaticon flaticon-magnifying-glass"></i>
                                <svg height="54" width="54" viewBox="0 0 54 54" class="wil-svg">
                                    <circle cx="27" cy="27" r="26" fill="transparent" class="wil-svg__circle"></circle>
                                </svg>
                            </a>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>