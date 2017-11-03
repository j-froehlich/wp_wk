<?php
/**
 * The class handle everything related to front-end
 *
 * @since       1.0
 * @link        http://wiloke.com
 * @author      Wiloke Team
 * @package     WilokeFramework
 * @subpackpage WilokeFramework/admin/front-end
 */


/**
 * Deny directly access
 * @since 1.0
 */
if ( !defined('ABSPATH') )
{
    wp_die();
}

if ( !class_exists('WilokePublic') )
{
    return;
}

class WilokePublic
{
    /**
     * Prefix
     * @since 1.0
     */
    public static $prefix = 'wiloke_';

    /**
     * Current Layout Key
     * @since 1.0
     */
    public static $current_layout = 'wiloke_current_layout';

    /**
     * The key of slider settings (in single post)
     * @since 1.0
     */
    public static $single_post_settings = 'single_post_settings';
    
    public function __construct() {
        add_action('wp_ajax_nopriv_wiloke_pagination_with_ajax', array($this, 'wiloke_pagination_with_ajax'));
        add_action('wp_ajax_nopriv_wiloke_pagination_with_fajax', array($this, 'wiloke_pagination_with_fajax'));
        add_action('wp_ajax_wiloke_pagination_with_ajax', array($this, 'wiloke_pagination_with_ajax'));
        add_action('wp_ajax_wiloke_pagination_with_fajax', array($this, 'wiloke_pagination_with_fajax'));
        add_action('wp_ajax_wiloke_woocommerce_loadmore', array($this, 'wiloke_woocommerce_loadmore_products'));
        add_action('wp_ajax_nopriv_wiloke_woocommerce_loadmore', array($this, 'wiloke_woocommerce_loadmore_products'));
        add_action('wp_ajax_wiloke_post_counter', array($this, 'wiloke_post_counter'));
        add_action('wp_ajax_nopriv_wiloke_post_counter', array($this, 'wiloke_post_counter'));
        add_action('wp_ajax_nopriv_wiloke_render_search_suggestion', array($this, 'wiloke_render_search_suggestion'));
        add_action('wp_ajax_wiloke_render_search_suggestion', array($this, 'wiloke_render_search_suggestion'));
    }

    /**
     * Add Meta Keyword into head tag
     * @since 1.0
     */
    public function wp_head(){
        global $wiloke, $post;

        if ( empty($wiloke->aThemeOptions) ) {
            return;
        }

        if ( !function_exists('wp_site_icon') || ( function_exists('has_site_icon') && !has_site_icon() ) ) {
            if ( isset($wiloke->aThemeOptions['general_favicon']['thumbnail']) && !empty($wiloke->aThemeOptions['general_favicon']['thumbnail']) ){
                ?>
                <link rel="shortcut icon" href="<?php echo esc_url($wiloke->aThemeOptions['general_favicon']['thumbnail']); ?>" />
                <?php
            }
        }

        if ( isset($wiloke->aThemeOptions['general_apple_touch_icon']['url']) && !empty($wiloke->aThemeOptions['general_favicon']['url']) ) {
            ?>
            <link rel="apple-touch-icon image_src" href="<?php echo esc_url($wiloke->aThemeOptions['general_apple_touch_icon']['url']); ?>" />
            <?php
        }

        if( is_home() || is_front_page() ) :
        ?>
            <meta name="description" content="<?php echo esc_attr( $wiloke->aThemeOptions['seo_home_meta_description'] ); ?>" />
            <meta name="keywords" content="<?php echo esc_attr( $wiloke->aThemeOptions['seo_home_meta_keywords'] ); ?>" />
        <?php else: ?>
            <meta name="description" content="<?php echo esc_attr( $wiloke->aThemeOptions['seo_other_meta_description'] ); ?>" />
            <meta name="keywords" content="<?php echo esc_attr( $wiloke->aThemeOptions['seo_other_meta_keywords'] ); ?>" />
        <?php
        endif;
        if( $wiloke->aThemeOptions['seo_open_graph_meta'] === 'enable' ) :
		?>
            <meta property="og:type" content="website" />
            <?php
                $oQuery = get_queried_object();
                if ( is_home() || is_front_page() ){
                    $title = get_option('blogname');
                    $url = get_option('home_url');
                    $image = isset($wiloke->aThemeOptions['general_logo']['url']) ? $wiloke->aThemeOptions['general_logo']['url'] : '';
                }elseif( isset($oQuery->term_id) && !empty($oQuery->term_id) ){
                    $title = $oQuery->name;
                    $url = get_term_link($oQuery->term_id);
                    $desc = $oQuery->description;
                    $aOptions = get_option('_wiloke_cat_settings_'.$oQuery->term_id);
                    if ( isset($aOptions['featured_image']) && !empty($aOptions['featured_image']) ){
                        $image = wp_get_attachment_image_url($aOptions['featured_image'], 'large');
                    }
                }elseif( function_exists('is_woocommerce') && is_woocommerce() ){
                    if ( function_exists('is_shop') ){
                        $postID = get_option('woocommerce_shop_page_id');
                        if ( has_post_thumbnail($postID) ){
                            $image = get_the_post_thumbnail_url($postID, 'large');
                        }
                        $title = get_the_title($postID);
                        $url = get_permalink($postID);
                        $aPageSettings = Wiloke::getPostMetaCaching($postID, 'single_page_settings');
                        if( isset($aPageSettings['page_description']) ){
                            $desc = $aPageSettings['page_description'];
                        }else{
                            $desc = $title;
                        }
                    }
                }elseif( is_singular() ){
                    $title = $post->post_title;
                    $url = get_permalink($post->ID);
                    $aPageSettings = Wiloke::getPostMetaCaching($post->ID, 'single_page_settings');

                    if( isset($aPageSettings['page_description']) ){
                        $desc = ( $aPageSettings['page_description'] );
                    }else if( !empty( $post->post_excerpt ) ){
                        $desc = wp_trim_words($post->post_excerpt, 50);
                    }else if( strpos($post->post_content, '[vc_row') === false ){
                        $desc = wp_trim_words($post->post_content, 50);
                    }else{
                        $desc = $post->post_title;
                    }

                    if ( has_post_thumbnail() ){
                        $image = get_the_post_thumbnail_url($post->ID, 'large');
                    }
                }
            ?>

            <?php if ( isset($url) && !empty($url) ) : ?>
            <meta property="og:url" content="<?php echo esc_url($url); ?>" />
            <?php endif; ?>

            <?php if ( isset($title) && !empty($title) ) : ?>
            <meta property="og:title" content="<?php echo esc_attr($title); ?>" />
            <?php endif; ?>
            <?php if ( isset($desc) && !empty($desc) ) : ?>
            <meta property="og:description" content="<?php echo esc_attr($desc); ?>">
            <?php endif; ?>

            <?php if ( isset($image) && !empty($image) ) : ?>
            <meta property="og:image" content="<?php echo esc_url( $image ); ?>" />
            <?php endif; ?>

            <?php if ( isset($url) && !empty($url) ) : ?>
            <link rel="canonical" href="<?php echo esc_url($url); ?>" />
            <?php endif; ?>

        <?php
	    endif;
    }

    /**
     * Re-structure WordPress Title
     * @since 1.0
     */
    function wp_title( $title ){

        global $wiloke, $paged, $page;

        $title = trim( str_replace( array( '&raquo;', get_bloginfo( 'name' ), '|' ),array( '', '', ''), $title ) );
        $seprated = '&raquo;';

        ob_start();

        if( is_home() || is_front_page() )
        {
            if( !empty( $wiloke->aThemeOptions['seo_home_title_format'] ) )
            {
                echo esc_html( str_replace( array('%Site Title%', '%Tagline%' ), array( get_bloginfo( 'name' ), get_bloginfo( 'description', 'display' ) ),$wiloke->aThemeOptions['seo_home_title_format'] ) );
            }else{
                $site_description = get_bloginfo( 'description', 'display' );
                if( $wiloke->aThemeOptions['seo_home_title_format'] == 'blogname_blogdescription' )
                {
                    bloginfo( 'name' );
                    if ( $site_description ){
                        echo ' '.$seprated." $site_description";
                    }

                }else if( $wiloke->aThemeOptions['seo_home_title_format'] == 'blogdescription_blogname' ){
                    if ( $site_description ){
                        echo esc_html( $seprated ). Wiloke::wiloke_kses_simple_html($site_description, true);
                    }
                    bloginfo( 'name' );
                }else{
                    bloginfo( 'name' );
                }
            }

        }else if( is_page() || is_single() )
        {
            if( $wiloke->aThemeOptions['seo_single_post_page_title_format'] == 'posttitle_blogname' )
            {
                echo esc_html( $title.' '.$seprated.' ' );
                bloginfo( 'name' );

            }else if( $wiloke->aThemeOptions['seo_single_post_page_title_format'] == 'blogname_posttitle' ){
                bloginfo( 'name' );
                echo esc_html( ' '.$seprated.' '.$title );
            }else{
                echo esc_html( $title );
            }

        }else{
            if( $wiloke->aThemeOptions['seo_archive_title_format'] == 1 )
            {
                echo esc_html( $title.' '.$seprated.' ' );
                bloginfo( 'name' );

            }else if( $wiloke->aThemeOptions['seo_archive_title_format'] == 2 ){
                bloginfo( 'name' );
                echo esc_html( ' '.$seprated.' '.$title );
            }else{
                echo esc_html( $title );
            }
        }
        if ( $paged >= 2 || $page >= 2 ){
            echo esc_html( ' '.$seprated.' ' . 'Page '. max( $paged, $page ) );
        }

        $out = ob_get_contents();
        ob_end_clean();

        return $out;
    }

    /**
     * render logo
     * @since 1.0
     */
    public function render_logo($aProjectSettings, $aPageSettings){
        global $wiloke, $post;
        if ( is_singular('portfolio') ){
            if ( isset($aProjectSettings['project_logo']) && !empty($aProjectSettings['project_logo']) ){
                $logo = $aProjectSettings['project_logo'];
            }elseif ( isset($wiloke->aThemeOptions['project_logo']['url']) && !empty($wiloke->aThemeOptions['project_logo']['url']) ){
                $logo = $wiloke->aThemeOptions['project_logo']['url'];
            }
        }elseif ( !empty($post) && is_page($post->ID) && isset($aPageSettings['logo']) && !empty($aPageSettings['logo']) ){
            $logo = $aPageSettings['logo'];
        }

        $logo = isset($logo) ? $logo : $wiloke->aThemeOptions['general_logo']['url'];
        ?>
        <a href="<?php echo esc_url(home_url('/')); ?>">
            <?php if ( !empty($wiloke->aThemeOptions['general_logo']) ) : ?>
                <img src="<?php echo esc_url($logo); ?>" alt="<?php echo esc_attr(get_option('blogname')); ?>" />
            <?php else : ?>
                <h1><?php echo esc_html(get_option('blogname')); ?></h1>
            <?php endif; ?>
        </a>
        <?php
    }

    /**
     * Add Search and Mini-cart into menu
     * @since 1.0
     */
    public function append_items_to_menu($nav, $oArgs){
        global $wiloke, $post;

        if ( $oArgs->theme_location != 'oz_menu' || ( isset($oArgs->device) && ($oArgs->device == 'mobile') ) ) {
            return $nav;
        }

        ob_start();

        if ( isset($wiloke->aThemeOptions['general_toggle_add_search_to_menu']) && $wiloke->aThemeOptions['general_toggle_add_search_to_menu'] == 'enable' ) {
            ?>
            <a href="#wil-search" class="wil-toggle-search"><i class="flaticon flaticon-magnifying-glass"></i></a>
            <?php
        }

        if ( !$this->is_allow_breadcrumb() ) {
            $this->product_mini_cart(true);
        }
        ?>
        <a href="#wil-menu-mobile" class="wil-toggle-menu"><span class="wil-toggle-menu__item"></span><span class="wil-toggle-menu__item"></span><span class="wil-toggle-menu__item"></span></a>
        <?php
        $nav = str_replace('</nav>', '', $nav);
        $nav .= ob_get_clean() . '</nav>';

        return $nav;
    }

    /**
     * A Cool feature.
     * @since 1.0
     */
    public function wiloke_render_search_suggestion($isNotAjax=false, $aOptions = array()){
        $aOptions = !empty($aOptions) ? $aOptions : get_option('wiloke_themeoptions');

        if ( empty($isNotAjax) ){
            if ( check_ajax_referer('security', 'wiloke-nonce', false) ){
                wp_send_json_error();
            }

            if ( !empty($_POST['expired']) ) {
                $aData = get_transient('wiloke_search_suggesstion');

                if ( $aData ){
                    wp_send_json_success($aData);
                }
            }
            if ( $aOptions['general_toggle_add_search_to_menu'] == 'disable' ) {
                wp_send_json_error();
            }

        }else{
            $aData = get_transient('wiloke_search_suggesstion');
            if ( !empty($aData) && !empty($aOptions['general_search_suggestion_update']) ) {
                return $aData;
            }
        }

        $args = array(
            'post_type' => $aOptions['general_search_suggestion'],
            'posts_per_page' => absint($aOptions['general_search_suggestion_limited']),
            'post_status' => 'publish',
            'orderby' => 'meta_value_num',
            'meta_key' => 'wiloke_post_counter',
            'order' => 'DESC'
        );

        $wilokeQuery = new WP_Query($args);

        if ( $wilokeQuery->have_posts() ) {
            $aData = array();
            while( $wilokeQuery->have_posts() ) {
                $wilokeQuery->the_post();
                $aData[$wilokeQuery->post->ID]['title'] = get_the_title($wilokeQuery->post->ID);
                $aData[$wilokeQuery->post->ID]['link'] = esc_url(get_permalink($wilokeQuery->post->ID));
                $aData[$wilokeQuery->post->ID]['ID'] = $wilokeQuery->post->ID;
            }

            if ( empty($isNotAjax) && empty($aData) ) {
                wp_send_json_error();
            }
            $aOptions['general_search_suggestion_update'] = !empty($aOptions['general_search_suggestion_update']) ? absint($aOptions['general_search_suggestion_update']) : 1800;

            set_transient('wiloke_search_suggesstion', $aData, absint($aOptions['general_search_suggestion_update']));
            wp_reset_postdata();

            if ( empty($isNotAjax) ) {
                wp_send_json_success($aData);
            }else{
                return $aData;
            }
        }else{
            delete_transient('wiloke_search_suggesstion');
            if ( empty($isNotAjax) ) {
                wp_send_json_error();
            }else{
                return false;
            }
        }
    }

    /**
     * Post Counter
     * It's very helpful to order post
     * @since 1.0
     */
    public function wiloke_post_counter(){
        if ( check_ajax_referer('security', 'wiloke-nonce', false) ){
            wp_send_json_error();
        }

        if ( !post_type_exists($_POST['post_type']) ) {
            wp_send_json_error();
        }

        if ( !get_the_title($_POST['post_id']) ){
            wp_send_json_error();
        }

        $current = Wiloke::getPostMetaCaching($_POST['post_id'], 'wiloke_post_counter');
        $current = !$current ? 1 : absint($current) + 1;

        update_post_meta($_POST['post_id'], 'wiloke_post_counter', $current);

        wp_send_json_success($current);
        wp_die();
    }

    /**
     * Ajax Portfolio
     * @since 1.0
     */
    public function render_popup_project($projectID){
        $args = array(
            'post_type' => 'portfolio',
            'post__id'  => $projectID,
            'post_status'=>'publish',
            'posts_per_page'=>1
        );

        $wilokeQuery = new WP_Query($args);

        if ( $wilokeQuery->have_posts() ) :
            global $post;
            while ($wilokeQuery->have_posts()) :
                $wilokeQuery->the_post();
            ?>
                <div class="wil-work-popup">
                    <div class="wil-work-popup__image">
                        <?php echo do_shortcode('[wiloke_post_format_shortcode]'); ?>
                    </div>
                    <div class="wil-work-popup__caption">

                    </div>
                </div>
            <?php
            endwhile;
            wp_reset_postdata();
        else:
            WilokeAlert::render_alert( esc_html__('It seems we could not found what you are looking for!', 'oz') );
        endif;
        wp_die();
    }

    /**
     * WooCommerce Loadmore with ajax
     * @since 1.0
     */
    public function wiloke_woocommerce_loadmore_products(){
        // check_ajax_referer('wiloke-nonce', '_nonce');
        if ( !isset($_POST['url']) || empty($_POST['url'])  )
        {
            wp_send_json_error();
        }

        $currentPage = preg_replace_callback('/(.*)(paged=|page\/)/', function(){
            return '';
        }, $_POST['url']);

        $currentPage = (int)$currentPage;

        if ( empty($currentPage) )
        {
            wp_send_json_error();
        }

        $aSettings = isset($_POST['settings']) ? $_POST['settings'] : array();
        $aSettings['paged'] = absint($currentPage);
        $aSettings['ignore_sticky_posts'] = false;

        $postsPerPage = isset($_POST['other_data']['postsperpage']) && !empty($_POST['other_data']['postsperpage']) ? $_POST['other_data']['postsperpage'] : $aSettings['current_device_posts_per_page'];
        $postsPerPage = absint($postsPerPage);

        if ( empty($postsPerPage) || $postsPerPage > 30 ) {
            wp_send_json_error();
        }

        $query_args = array(
            'paged'               => $currentPage,
            'post_type'           => 'product',
            'post_status'         => 'publish',
            'ignore_sticky_posts' => 1,
            'orderby'             => $aSettings['orderby'],
            'order'               => $aSettings['order'],
            'posts_per_page'      => $postsPerPage,
            'meta_query'          => WC()->query->get_meta_query()
        );

        $newQuery = new WP_Query($query_args);

        if ( !$newQuery->have_posts() ) {
            wp_send_json_error();
        }

        if ( $newQuery->have_posts() ) {
            while ( $newQuery->have_posts() ) {
                $newQuery->the_post();
                $aProducts[] = $newQuery->post->ID;
            }

            $aSettings['ids'] = $aProducts;

            $content = wiloke_shortcode_design_shop($aSettings);
        }

        ob_start();
        self::render_pagination($newQuery, array('pagination_type'=>'ajax', 'paged'=>$currentPage), true, $aSettings, $postsPerPage);
        $pagination = ob_get_clean();

        wp_send_json_success(array('html'=>$content, 'pagination'=>$pagination));
    }

    /**
     * Pagination With Ajax
     * @since 1.0
     */
    public function wiloke_pagination_with_fajax(){
        $this->wiloke_pagination_with_ajax();
    }

    public function wiloke_pagination_with_ajax(){

        if ( !isset($_POST['url']) || empty($_POST['url']) || !isset($_POST['settings']) || empty($_POST['settings']) )
        {
            wp_send_json_error();
        }

        $currentPage = preg_replace_callback('/(.*)(paged=|page\/)/', function(){
            return '';
        }, $_POST['url']);
        $currentPage = trim($currentPage, '/');

        $currentPage = (int)$currentPage;

        if ( empty($currentPage) )
        {
            wp_send_json_error();
        }

        $aSettings = $_POST['settings'];
        $aSettings['paged'] = $currentPage;
        $aSettings['ignore_sticky_posts'] = false;
        $content = wiloke_shortcode_blog($aSettings);
        wp_send_json_success(array('html'=>$content));
    }

    /**
     * Render Post Meta On blog
     * @since 1.0
     */
    public static function render_post_meta(){
        global $post;
        ?>
        <div class="post__meta">
            <div class="post__meta__item post__author"><?php esc_html_e('By', 'oz'); ?> <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ); ?>"><?php the_author(); ?></a></div>
            <?php if ( has_category() ) : ?>
            <div class="post__meta__item post__categories">
                <?php the_category(', '); ?>
            </div>
            <?php endif; ?>
            <div class="post__meta__item post__date"><a href="<?php the_permalink(); ?>"><?php Wiloke::wiloke_render_date($post->ID); ?></a></div>
        </div>
        <?php if ( !is_single() ) : ?>
        <a href="<?php the_permalink(); ?>" title="<?php esc_html_e('Read more', 'oz'); ?>" class="post__more"><span class="post__more-icon"></span><span class="post__more-icon"></span><span class="post__more-icon"></span></a>
        <?php
        endif;
    }

    /**
     * Set header animation
     * @since 1.0
     */
    public function set_header_animation($class){
        global $wiloke;
        $aToggle = array(
            'general_toggle_menu_animation' => '.wil-menu-list &gt; li,',
            'general_toggle_logo_animation' => '.wil-logo,',
            'general_toggle_header_information_box' => '.wil-text-box',
            'general_toggle_search_feature_animation' => '.wil-toggle-search,',
        );

        foreach ( $aToggle as $key => $pattern ) {
            if ( $wiloke->aThemeOptions[$key] == 'disable' ) {
                $class = str_replace($pattern, '', $class);
            }
        }

        return trim($class);
    }

    /**
     * Render Header Information Box
     * @since 1.0
     */
    public function render_header_information_box()
    {
        global $wiloke;

        if ( !empty($wiloke->aThemeOptions['general_header_information_box_other_information']) ) :
            foreach ( $wiloke->aThemeOptions['general_header_information_box_other_information'] as $info ) :
                ?>
                <div class="wil-text-box"><?php Wiloke::wiloke_kses_simple_html(str_replace('wilokestyle', 'class="wil-text-overline-anim"', $info)); ?></div>
                <?php
            endforeach;
        endif;
    }

    /**
     * Render Attributes
     * @since 1.0.1
     */
    public static function render_attributes($aAttributes){
        $atts = '';
        foreach ( $aAttributes as $key => $val ) {
            $atts .= esc_attr($key) . '="' . esc_attr($val) . '" ';
        }

        echo trim($atts);
    }

    /**
     * MailChimp Subscribe
     * @since 1.0
     */
    public function handle_subscribe()
    {
        if( !check_ajax_referer( 'pi_subscribe_nonce', 'security' ) )
        {
            wp_send_json_error( array('message'=>esc_html__('Something went wrong', 'oz')) );
            die();
        }

        if(isset($_POST['email']))
        {
            if ( filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) )
            {
                $api = get_option('pi_mailchimp_api_key');

                if ( !$api || empty($api) )
                {
                    if ( current_user_can('edit_theme_options') )
                    {
                        wp_send_json_error(esc_html__('You have not configure MailChimp yet.', 'oz'));
                    }
                }else{
                    if ( is_file( WP_PLUGIN_DIR . '/wiloke-widgets/modules/pimailchimp/mailchimp/Mailchimp.php' ) ) {
                        require_once WP_PLUGIN_DIR . '/wiloke-widgets/modules/pimailchimp/mailchimp/Mailchimp.php';
                        $MailChimp = new MailChimp($api);
                        $result = $MailChimp->call('lists/subscribe', array(
                            'id'                => get_option('pi_mailchimp_listid'),
                            'email'             => array( 'email' => sanitize_email($_POST['email']) ),
                            'double_optin'      => true,
                            'status'            => 'subscribed',
                            'update_existing'   => true,
                            'replace_interests' => false,
                            'send_welcome'      => true,
                        ));

                        if(isset($result['status']) && $result['status']=='error')
                        {
                            wp_send_json_error($result['error']);
                        }else{
                            wp_send_json_success($_POST['welcome']);
                        }
                    }else{
                        wp_send_json_error( esc_html__('Wiloke Widget plugin is reqired', 'oz') );
                    }
                }
            }else{
                wp_send_json_error(esc_html__('Invalid email!', 'oz'));
            }
        }else{
            wp_send_json_error(esc_html__('An email is required', 'oz'));
        }

        die();
    }

    /**
     * Modify Args before Render Load more
     * @since 1.0
     */
    public function modifying_design_order_args($args, $i)
    {
        if ( $args['additional']['is_using_custom_for_loadmore'] == 'yes' && $args['additional']['default_layout'] == 'custom' )
        {
            if ( !empty($args['totalAjaxLoaded']) )
            {
                $i = intval($args['totalAjaxLoaded']);
                if ( $i >= count($args['additional']['sizes']) )
                {
                    $i = 0;
                }
            }
        }

        return intval($i);
    }

    /**
     * Filter render social networks
     * @since 1.0
     */
    public function wiloke_filter_social_network($link, $social, $separated){
        if ( $social == 'fa fa-google-plus' )
        {
            $social = esc_html__('Google+', 'oz');
        }else{
            $social = ucfirst(str_replace('fa fa-', '', $social));
        }
        ?>
        <a href="<?php echo esc_url($link); ?>" class="wil-text-overline-anim" target="_blank"><?php echo esc_html($social); ?></a><?php echo esc_html($separated); ?>
        <?php
    }

    /**
     * Edit photo structure in ajax request
     * @since 1.0
     */
    public function render_photo_item_in_ajax($post, $args, $wilokeI)
    {
        if ( $args['additional']['default_layout'] == 'custom' && $args['additional']['is_using_custom_for_loadmore'] != 'yes' )
        {
            $layout = 'grid';
        }else{
            $layout = $args['additional']['default_layout'];
        }

        $atts = $args['additional'];

        ob_start();
        switch ($layout)
        {
            case 'masonry':
                include Wiloke::$public_path . 'template/vc/photo/masonry.php';
                break;
            case 'creative-layout':
                include Wiloke::$public_path . 'template/vc/photo/creative-layout.php';
                break;
            default:
                include Wiloke::$public_path . 'template/vc/photo/grid.php';
                break;
        }
        $html = ob_get_contents();
        ob_end_clean();

        return $html;
    }

    /**
     * Render Menu
     * @since 1.0
     */
    public function render_menu($device){
        global $wiloke;
        $key = $wiloke->aConfigs['frontend']['register_nav_menu']['menu'][0]['key'];
        if ( has_nav_menu($key) ) {
            wp_nav_menu(array_merge(array('device'=>$device), $wiloke->aConfigs['frontend']['register_nav_menu']['config'][$key]));
        }
    }

    /**
     * Render Post Share
     * @since 1.0
     */
    public function render_post_tags()
    {
        the_tags('<div class="post__tags"><span>'.esc_html__('Tags: ', 'oz').'</span>', '', '</div>');
    }

    /**
     * Render Comment
     * @since 1.0
     */
    public function render_comment()
    {
        comments_template();
    }

    /**
     * Render Sharing
     * @since 1.0
     */
    public function render_sharing_wrapper($class)
    {
        $class .= ' single__share';
        return $class;
    }

    public function render_sharing_box($post, $aPostMeta, $wiloke)
    {
        if ( ($aPostMeta['project_style'] == 'style1') || ( ($aPostMeta['project_style'] == 'inherit') && ($wiloke->aThemeOptions['project_style'] == 'style1') ) ){
            if ( class_exists('WilokeSharingPost') )
            {
                echo do_shortcode('[wiloke_sharing_post]');
            }
        }
    }

    /**
     * the name of photo cats
     * @since 1.0
     */
    public static $photo_cat = 'photo_category';

    /**
     * Get term information
     * @since 1.0
     */
    public static function term_information($termsIDs, $taxonomy)
    {
        if ( is_string($termsIDs) )
        {
            $aTermIDs = explode(',', $termsIDs);
        }else{
            $aTermIDs = $termsIDs;
        }

        $oTerms = get_terms( $taxonomy, array(
            'include' => $aTermIDs
        ) );

        $aTermInfo = array();

        foreach ( $oTerms as $oTerm )
        {
            $aTermInfo[$oTerm->term_id]['title']        = $oTerm->name;
            $aTermInfo[$oTerm->term_id]['url']          = get_term_link($oTerm);
            $aTermInfo[$oTerm->term_id]['description']  = $oTerm->description;
        }

        return $aTermInfo;
    }

    /**
     * Get Instagram Information
     *
     * @params aInstance contains Instagram information username/userid
     * @since 1.0
     * @return object
     */
    public static function instagram($aInstance)
    {
        $aInstagramSettings         = get_option('_pi_instagram_settings');
        $aInstance['access_token']  = isset($aInstagramSettings['access_token']) ? $aInstagramSettings['access_token'] : '';
        $cacheInstagram = null;

        if ( empty($aInstance['access_token']) )
        {
            if ( current_user_can('edit_theme_options') )
            {
                WilokeAlert::render_alert( esc_html__('Please config your Instagram', 'oz'), false );
            }
        }else{
            if ( !empty($aInstance['username']) )
            {
                $type = 'username';
                $info = $aInstance['username'];
            }else{
                $type = 'self';
                $info = $aInstagramSettings['userid'];
            }

            if ( !empty($aInstance['cache_interval']) )
            {
                $cacheInstagram = get_transient('wiloke_cache_instagram_'.$info);
            }

            if ( !empty($cacheInstagram) )
            {
                print $cacheInstagram;
            }else{
                $content = self::handle_instagram_feed($info, $aInstance['access_token'], $aInstance['number_of_photos'], $type);

                if ( !empty($aInstance['cache_interval']) )
                {
                    set_transient('wiloke_cache_instagram_'.$info, $content, absint($aInstance['cache_interval']));
                }

                return $content;
            }
        }
    }

    /**
     * Render Project Background
     * @since 1.0
     */
    public function render_project_background(){
        if( !is_singular('portfolio') ) {
            return;
        }

        global $post, $wiloke;

        $aProject        = Wiloke::getPostMetaCaching($post->ID, 'project_header_settings');
        $aProjectGeneral = Wiloke::getPostMetaCaching($post->ID, 'project_general_settings');
        $headerBg = '';

        if ( !isset($aProject['background_id']) || empty($aProject['background_id']) ) {
            if ( isset($aProject['background']) && empty($aProject['background_id']) ){
                $headerBg = $aProject['background'];
            }else{
                if ( isset($wiloke->aThemeOptions['project_header_background']['id']) ){
                    $headerBg = !wp_is_mobile() ? wp_get_attachment_image_url($wiloke->aThemeOptions['project_header_background']['id'], 'wiloke_1600_auto') : wp_get_attachment_image_url($wiloke->aThemeOptions['project_header_background']['id'], 'medium');
                }
            }
        }else{
            $headerBg = wp_is_mobile() ? wp_get_attachment_image_url($aProject['background_id'], 'medium') : wp_get_attachment_image_url($aProject['background_id'], 'wiloke_1600_auto');
        }

        if ( !empty($headerBg) ) :
        ?>
        <div class="wil-work-detail__media">
            <div class="wil-work-detail__media-inner" style="background-image: url(<?php echo esc_url($headerBg); ?>)"></div>
        </div>
        <?php endif; ?>

        <?php 
            $bgType = !isset($aProjectGeneral['project_bg_type']) || $aProjectGeneral['project_bg_type'] == 'inherit'  ? $wiloke->aThemeOptions['project_bg_type'] : $aProjectGeneral['project_bg_type'];
            $bgContentImg = '';

            if ( isset($aProjectGeneral['project_content_bg_img_id']) && !empty($aProjectGeneral['project_content_bg_img_id']) ) {
                if ( !wp_is_mobile() ){
                    $bgContentImg = wp_get_attachment_image_url($aProjectGeneral['project_content_bg_img_id'], 'large');
                }else{
                    $bgContentImg = wp_get_attachment_image_url($aProjectGeneral['project_content_bg_img_id'], 'wiloke_460_460');
                }

            }else{
                if ( isset($aProjectGeneral['project_content_bg_img']) && !empty($aProjectGeneral['project_content_bg_img']) ) {
                    $bgContentImg = $aProjectGeneral['project_content_bg_img'];
                }elseif( isset($wiloke->aThemeOptions['project_content_bg_img']['id']) ) {
                    if ( !wp_is_mobile() ){
                        $bgContentImg = wp_get_attachment_image_url($wiloke->aThemeOptions['project_content_bg_img']['id'], 'large');
                    }else{
                        $bgContentImg = wp_get_attachment_image_url($wiloke->aThemeOptions['project_content_bg_img']['id'], 'wiloke_460_460');
                    }

                }
            }
        ?>

        <div class="wil-work-detail-bg-content" style="background-image:url(<?php echo esc_url($bgContentImg); ?>)"></div>
        
        <?php 
        if( $bgType == 'gradient' ) : 
            if ( !empty($aProjectGeneral['project_bg_gradient_f']) || !empty($aProjectGeneral['project_bg_gradient_s']) ) {
                $fColor = $aProjectGeneral['project_bg_gradient_f'];
                $sColor = $aProjectGeneral['project_bg_gradient_s'];
            }else{
                $fColor = $wiloke->aThemeOptions['project_bg_gradient_f']['rgba'];
                $sColor = $wiloke->aThemeOptions['project_bg_gradient_s']['rgba'];
            }
        ?>
        <div class="wil-work-detail-bg-color" style="background-image:linear-gradient(to top right, <?php echo esc_attr($fColor); ?> 40%, <?php echo esc_attr($sColor); ?> 80%); background-color: <?php echo esc_attr($fColor); ?>"></div>
        <?php 
        else :
            $bgColor = '';
            if ( isset($aProjectGeneral['project_bg_color']) && !empty($aProjectGeneral['project_bg_color']) && ($aProjectGeneral['project_bg_type'] != 'inherit') ) {
                $bgColor = $aProjectGeneral['project_bg_color'];
            }elseif ( isset($wiloke->aThemeOptions['project_bg_color']['rgba']) ) {
                $bgColor = $wiloke->aThemeOptions['project_bg_color']['rgba'];
            }
        ?>
        <div class="wil-work-detail-bg-color" style="background-color:<?php echo esc_attr($bgColor); ?>"></div>
        <?php endif; 
    }

    public function render_project_info_before_content_open($post, $aPostMeta, $wiloke){
        if ( $aPostMeta['project_style'] == 'inherit' ){
            $aPostMeta['project_style'] = $wiloke->aThemeOptions['project_style'];
        }

        if ( $aPostMeta['project_style'] == 'style1' ) {
            return;
        }

        $class = $aPostMeta['project_style'] == 'style2' ? 'col-md-4' : 'col-md-4 col-md-push-8';

        echo '<div class="row"><div class="'.esc_attr($class).'">';
    }

    public function render_project_info_before_content_close($post, $aPostMeta, $wiloke){
        if ( ($aPostMeta['project_style'] == 'style1') || ( ($aPostMeta['project_style'] == 'inherit') && ($wiloke->aThemeOptions['project_style'] == 'style1') ) ){
            return;
        }

        echo '</div><!-- END / Project description style2/style3 -->';
    }

    public function render_project_info_before_content($post, $aPostMeta, $wiloke){
        if ( ($aPostMeta['project_style'] == 'style1') || ( ($aPostMeta['project_style'] == 'inherit') && ($wiloke->aThemeOptions['project_style'] == 'style1') ) ){
            return;
        }

        echo '<div class="col-md-4">';
    }

    public function render_project_intro($post, $aPostMeta, $wiloke){
        $aProject = Wiloke::getPostMetaCaching($post->ID, 'single_portfolio_intro');
        global $wiloke;

        if ( $aPostMeta['project_style'] == 'inherit' ) {
            $aPostMeta['project_style'] = $wiloke->aThemeOptions['project_style'];
        }
        
        ?>
        <div class="wil-work-detail__description">
            <?php
            if ( $aPostMeta['project_style'] !== 'style1' ){
                $this->render_project_title($post, $aPostMeta);
                $this->render_project_info_open();
                $this->render_project_info($post, $aPostMeta);
                $this->render_project_info_close();
            }
            ?>

            <?php
            if ( !empty($aProject['project_launch_btn']) ) {
                $btnName = $aProject['project_launch_btn'];
            }else{
                $btnName = $wiloke->aThemeOptions['project_launch_btn'];
            }

            if ( !empty($aProject['project_launch_type']) && $aProject['project_launch_type'] != 'inherit' ) {
                $target = $aProject['project_launch_type'];
            }else{
                $target = $wiloke->aThemeOptions['project_launch_type'];
            }

            if ( !empty($aPostMeta['project_launch_btn_style']) && $aPostMeta['project_launch_btn_style'] != 'inherit' ) {
                $style = $aPostMeta['project_launch_btn_style'];
            }else{
                $style = $wiloke->aThemeOptions['project_launch_btn_style'];
            }

            if ( !empty($aPostMeta['project_launch_btn_bg']) && $aPostMeta['project_launch_btn_bg'] != 'inherit' ) {
                $style .= ' '  . $aPostMeta['project_launch_btn_bg'];
            }else{
                $style .= ' ' . $wiloke->aThemeOptions['project_launch_btn_bg'];
            }

             if ( !empty($aPostMeta['project_launch_btn_size']) && $aPostMeta['project_launch_btn_size'] != 'inherit' ) {
                $style .= ' '  . $aPostMeta['project_launch_btn_size'];
            }else{
                $style .= ' ' . $wiloke->aThemeOptions['project_launch_btn_size'];
            }

            if ( $aProject && !empty($aProject['project_intro']) ) :
                if ( !empty($aPostMeta['project_text_intro_color']) && !empty($aPostMeta['project_text_intro_color']) ) {
                    $textIntroColor = $aPostMeta['project_text_intro_color'];
                }else{
                    $textIntroColor = isset($wiloke->aThemeOptions['project_text_intro_color']['rgba']) ? $wiloke->aThemeOptions['project_text_intro_color']['rgba'] : '';
                }
            ?>
            <p style="color: <?php echo esc_attr($textIntroColor); ?>"><?php Wiloke::wiloke_kses_simple_html($aProject['project_intro']); ?></p>
            <?php endif; ?>
            <?php if ( $aProject && !empty($aProject['project_link']) ) : ?>
            <a href="<?php echo esc_url($aProject['project_link']); ?>" class="<?php echo esc_attr($style); ?> wil-btn" target="<?php echo esc_attr($target); ?>"><?php Wiloke::wiloke_kses_simple_html($btnName); ?></a>
            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * Project Pagination
     * @since 1.0
     */
    public function render_project_pagination($thePost){
        global $wiloke, $post;

        $oPrevPost = get_next_post();
        $oNextPost = get_previous_post();

        $prevClass = 'wil-work-nav-page__item wil-work-nav-page__prev';
        $prevLink = '#';
        if ( empty($oPrevPost) ) {
            $prevClass .= ' disable';
        }else{
            $prevLink = get_permalink($oPrevPost->ID);
        }

        $nextClass = 'wil-work-nav-page__item wil-work-nav-page__next';
        $nextLink = '#';
        if ( empty($oNextPost) ) {
            $nextClass .= ' disable';
        }else{
            $nextLink = get_permalink($oNextPost->ID);
        }

        $pageClass = 'wil-work-nav-page__out';
        $pageLink = '#';
        if ( empty($wiloke->aThemeOptions['portfolio_page']) ) {
            $pageClass .= ' disable';
        }else{
            $pageLink = get_permalink($wiloke->aThemeOptions['portfolio_page']);
        }

    ?>
    <div class="wil-work-nav-page">
        <div class="container">
            <div class="row">

                <div class="col-xs-4 text-left">
                    <a href="<?php echo esc_url($prevLink); ?>" class="<?php echo esc_attr($prevClass); ?>">
                        <span><?php esc_html_e('Prev', 'oz'); ?></span>
                        <span class="wil-work-nav-page__hover">
                            <span class="wil-work-nav-page__hover-inner"><?php esc_html_e('Prev', 'oz'); ?></span>
                        </span>
                        <span class="wil-work-nav-page__line"></span>
                    </a>
                </div>

                <div class="col-xs-4 text-center">
                    <a href="<?php echo esc_url($pageLink); ?>" class="<?php echo esc_attr($pageClass); ?>">
                        <span class="wil-work-nav-page__out-icon-wrap"><span class="wil-work-nav-page__out-icon-border">
                                <span class="wil-work-nav-page__out-icon-1"></span>
                                <span class="wil-work-nav-page__out-icon-2"></span>
                                <span class="wil-work-nav-page__out-icon-3"></span>
                            </span>
                        </span>
                        <span class="wil-work-nav-page__out-icon-wrap-hover">
                            <span class="wil-work-nav-page__out-icon-border">
                                <span class="wil-work-nav-page__out-icon-1"></span>
                                <span class="wil-work-nav-page__out-icon-2"></span>
                                <span class="wil-work-nav-page__out-icon-3"></span>
                            </span>
                        </span>
                    </a>
                </div>
                <div class="col-xs-4 text-right">
                    <a href="<?php echo esc_url($nextLink); ?>" class="<?php echo esc_attr($nextClass); ?>">
                        <span><?php esc_html_e('Next', 'oz'); ?></span>
                        <span class="wil-work-nav-page__hover">
                            <span class="wil-work-nav-page__hover-inner"><?php esc_html_e('Next', 'oz'); ?></span>
                        </span>
                        <span class="wil-work-nav-page__line"></span>
                    </a>
                </div>

            </div>
        </div>
    </div>
    <?php
    }

    /**
     * Related projects
     * @since 1.0
     */
    public function render_related_projects($wiloke, $post)
     {
        $args = array(
            'post_type'         => 'portfolio',
            'posts_per_page'    => $wiloke->aThemeOptions['single_portfolio_related_projects_number_of_posts'],
            'post_status'       => 'publish',
            'post__not_in'      => array($post->ID),
            'tax_query'         => array()
        );

        switch ( $wiloke->aThemeOptions['single_portfolio_related_projects_in'] )
        {
            case 'tags':
                $oTags      = wp_get_object_terms($post->ID, 'post_tag');
                $aTermIDs   = array();
                if ( !empty($oTags) && !is_wp_error($oTags) )
                {
                    foreach ( $oTags as $oTag )
                    {
                        $aTermIDs[] = $oTag->term_id;
                    }

                    $args['tax_query'][] = array(
                        'taxonomy' => 'post_tag',
                        'field'    => 'term_id',
                        'terms'    => $aTermIDs
                    );

                }
                break;
            case 'categories':
                $oTerms      = wp_get_object_terms($post->ID, 'portfolio_category');
                $aTermIDs   = array();
                if ( !empty($oTerms) && !is_wp_error($oTerms) )
                {
                    foreach ( $oTerms as $oTerm )
                    {
                        $aTermIDs[] = $oTerm->term_id;
                    }

                    $args['tax_query'][] = array(
                        'taxonomy' => 'portfolio_category',
                        'field'    => 'term_id',
                        'terms'    => $aTermIDs
                    );
                }
                break;
            default:
                $oTags      = wp_get_object_terms($post->ID, 'post_tag');
                $aTermIDs   = array();

                if ( !empty($oTags) && !is_wp_error($oTags) )
                {
                    foreach ( $oTags as $oTag )
                    {
                        $aTermIDs[] = $oTag->term_id;
                    }

                    $args['tax_query'][] = array(
                        'taxonomy' => 'post_tag',
                        'field'    => 'term_id',
                        'terms'    => $aTermIDs
                    );
                }

                $oTerms      = wp_get_object_terms($post->ID, 'portfolio_category');
                $aTermIDs   = array();
                if ( !empty($oTerms) && !is_wp_error($oTerms) )
                {
                    foreach ( $oTerms as $oTerm )
                    {
                        $aTermIDs[] = $oTerm->term_id;
                    }

                    $args['tax_query'][] = array(
                        'taxonomy' => 'portfolio_category',
                        'field'    => 'term_id',
                        'terms'    => $aTermIDs
                    );
                }

                if ( !empty($args['tax_query']) )
                {
                    $args['tax_query']['relation'] = 'OR';
                }

                break;
        }

        if ( empty($args['tax_query']) )
        {
            unset($args['tax_query']);
        }

        $wilokeQuery = new WP_Query($args);

        if ( $wilokeQuery->have_posts() ) :
        ?>
        <div class="work-related">
            <h4 class="work-related__title"><?php echo esc_html($wiloke->aThemeOptions['single_portfolio_related_projects_title']); ?></h4>
            <div class="work-related__content">
                <div class="row">
                    <?php
                        while ( $wilokeQuery->have_posts() )
                        {
                            $wilokeQuery->the_post();
                            $link = get_permalink($wilokeQuery->post->ID);
                            ?>
                            <div class="col-xs-6 col-sm-4 col-md-12">
                                <div class="work-related__item">
                                    <?php if ( has_post_thumbnail($wilokeQuery->post->ID) ) : ?>
                                    <a href="<?php echo esc_url($link); ?>">
                                        <?php echo get_the_post_thumbnail($wilokeQuery->post->ID, 'wiloke_215_215') ?>
                                    <?php endif; ?>
                                    <h3><a href="<?php echo esc_url($link); ?>"><?php Wiloke::wiloke_kses_simple_html(get_the_title($wilokeQuery->post->ID)); ?></a></h3>
                                </div>
                            </div>
                            <?php
                        }
                        wp_reset_postdata();
                    ?>
                </div>
            </div>
        </div>
        <?php
        endif;
     }

    /**
     * Entries Meta of the project
     * @since 1.0
     */
    public function render_project_title($post, $aPostMeta){
        global $wiloke;

        $color = '';
        if ( !empty($aPostMeta['project_title_color']) ) {
            $color = $aPostMeta['project_title_color'];
        }elseif ( isset($wiloke->aThemeOptions['project_title_color']['rgba']) ){
            $color = $wiloke->aThemeOptions['project_title_color']['rgba'];
        }
        ?>
        <div class="wil-work-detail__title">
            <h1 style="color: <?php echo esc_attr($color); ?>"><?php the_title(); ?></h1>
        </div>
        <?php
    }

    public function render_project_info_open(){
        ?>
        <div class="wil-work-detail__meta">
        <?php
    }

    public function render_project_info($post, $aPostMeta){
        global $wiloke;
        $labelColor = $contentColor = '';
        if ( !empty($aPostMeta['project_intro_label_color']) ) {
            $labelColor = $aPostMeta['project_intro_label_color'];
        }elseif ( isset($wiloke->aThemeOptions['project_intro_label_color']['rgba']) && !empty($wiloke->aThemeOptions['project_intro_label_color']['rgba']) ){
            $labelColor = $wiloke->aThemeOptions['project_intro_label_color']['rgba'];
        }

        if ( !empty($aPostMeta['project_intro_content_color']) ) {
            $contentColor = $aPostMeta['project_intro_content_color'];
        }elseif ( isset($wiloke->aThemeOptions['project_intro_content_color']['rgba']) && !empty($wiloke->aThemeOptions['project_intro_content_color']['rgba']) ){
            $contentColor = $wiloke->aThemeOptions['project_intro_content_color']['rgba'];
        }

        $this->render_project_author($post, $aPostMeta, $labelColor, $contentColor);
        $this->render_project_category($post, $aPostMeta, $labelColor, $contentColor);
        $this->render_project_created($post, $aPostMeta, $labelColor, $contentColor);
        $this->render_project_custom($post, $aPostMeta, $labelColor, $contentColor);
    }

    public function render_project_custom($post, $aPostMeta, $labelColor, $contentColor){
        $aInfo = get_post_custom($post->ID);
        global $wiloke;

        if ( !empty($aInfo) ){
            foreach ( $aInfo as $key => $aValues )
            {
                if ( strpos($key, 'wiloke_project_info_') === false )
                {
                    continue;
                }
                
                foreach ( $aValues as $info )
                {
                    $label = str_replace( array('wiloke_project_info_', '-'), array('', ' '), $key );
                    ?>
                    <div class="wil-work-detail__meta__item" style="color: <?php echo esc_attr($labelColor); ?>"><?php echo esc_html($label); ?> <span style="color:<?php echo esc_attr($contentColor); ?>"><?php Wiloke::wiloke_kses_simple_html($info); ?></span></div>
                    <?php
                }
            }
        }
    }

    public function render_project_info_close(){
        ?>
        </div><!-- END / Project Info Close -->
        <?php
    }

    public function render_project_main_content_open($post, $aPostMeta, $wiloke){
        if ( $aPostMeta['project_style'] == 'inherit' ){
            $aPostMeta['project_style'] = $wiloke->aThemeOptions['project_style'];
        }

        if ( $aPostMeta['project_style'] == 'style1' ) {
            return;
        }

        $class = $aPostMeta['project_style'] == 'style2' ? 'col-md-8' : 'col-md-8 col-md-pull-4';


            echo '<div class="'.esc_attr($class).'">';
    }

    public function render_project_main_content_close($post, $aPostMeta, $wiloke){
        if ( ($aPostMeta['project_style'] == 'style1') || ( ($aPostMeta['project_style'] == 'inherit') && ($wiloke->aThemeOptions['project_style'] == 'style1') ) ){
            return;
        }
        echo '</div></div><!-- END / Project Main Content Close -->';
        $aPostMeta['project_style'] = 'style1';
        $this->render_sharing_box($post, $aPostMeta, $wiloke);
    }

    public function render_project_author($thePost, $aPostMeta, $labelColor, $contentColor)
    {
        global $post, $wiloke;
        $label = is_admin() && empty($wiloke->aThemeOptions['project_info_created_by_text']) ? 'Author:' : $wiloke->aThemeOptions['project_info_created_by_text'];

        if ( empty($label) ) {
            return;
        }
        ?>
        <div class="wil-work-detail__meta__item wil-work-detail__author" style="color:<?php echo esc_attr($labelColor); ?>"><?php echo esc_html($label); ?> <span style="color:<?php echo esc_attr($contentColor); ?>"><?php echo !is_admin() ? get_the_author() : wp_get_current_user()->display_name; ?></span></div>
        <?php
    }

    public function render_project_category($post, $aPostMeta, $labelColor, $contentColor)
    {
        global $wiloke;
        $label = is_admin() && empty($wiloke->aThemeOptions['project_info_in_categories_text']) ? 'Categories:' : $wiloke->aThemeOptions['project_info_in_categories_text'];

        if ( empty($label) ) {
            return;
        }

        $oTerms = get_the_terms($post->ID, 'portfolio_category');
        if ( !empty($oTerms) && !is_wp_error($oTerms) ) :
        ?>
        <div class="wil-work-detail__meta__item wil-work-detail__categories" style="color: <?php echo esc_attr($labelColor); ?>"><?php echo esc_html($label); ?>
            <ul>
                <?php foreach ( $oTerms as $oTerm ) : ?>
                <li data-termid="<?php echo esc_attr($oTerm->term_id); ?>"><a href="<?php echo esc_url(get_term_link($oTerm->term_id)); ?>" style="color:<?php echo esc_attr($contentColor
                    ); ?>"><?php echo esc_html($oTerm->name); ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php
        elseif ( is_admin() ) :
        ?>
        <div class="wil-work-detail__meta__item wil-work-detail__categories" style="color: <?php echo esc_attr($labelColor); ?>"><?php echo esc_html($label); ?>
            <ul>

            </ul>
        </div>
        <?php
        endif;
    }

    public function render_project_created($post, $aPostMeta, $labelColor, $contentColor)
    {
        global $wiloke;
        $label = empty($wiloke->aThemeOptions['project_info_published_text']) && is_admin() ? 'By:' : $wiloke->aThemeOptions['project_info_published_text'];

        if ( empty($label) ) {
            return;
        }

        if ( !is_admin() ) :
        ?>
        <div class="wil-work-detail__meta__item wil-work-detail__date" style="color: <?php echo esc_attr($labelColor); ?>;"><?php echo esc_html($label); ?> <span style="color: <?php echo esc_attr($contentColor); ?>;"><?php Wiloke::wiloke_render_date($post->ID, true); ?></span></div>
        <?php
        else:
            $published = isset($post->ID) ? Wiloke::wiloke_render_date($post->ID, false) : date(get_option('date_format'));
        ?>
        <div class="wil-work-detail__meta__item wil-work-detail__date" style="color: <?php echo esc_attr($labelColor); ?>;"><?php echo esc_html($label); ?> <span style="color: <?php echo esc_attr($contentColor); ?>;"><?php echo esc_html($published); ?></span></div>
        <?php
        endif;
    }

    /**
     * Get Instagram Info
     * @since 1.0
     */
    public function get_instagram($userName, $accessToken, $type, $count)
    {
    $args = array( 'decompress' => false, 'timeout' => 30, 'sslverify'   => true );

    if ( $type != 'self' )
    {
        $userName = $this->pi_get_instagram_userid($userName, $accessToken, $args);
    }

    $url   = is_ssl() ? 'https' : 'http' . '://api.instagram.com/v1/users/'.$userName.'/media/recent?access_token='.$accessToken.'&count='.$count;

    $oInstagram = wp_remote_get( esc_url_raw( $url ), $args);

    if ( !is_wp_error($oInstagram) )
    {
        $oInstagram = wp_remote_retrieve_body($oInstagram);

        $oInstagram = json_decode($oInstagram);

        if ( !empty($oInstagram) && $oInstagram->meta->code === 200 )
        {
            return $oInstagram;
        }
    }

    return false;
    }

    /**
     * The function is useful in the case of they use another Instagram username
     * @since 1.0
     */
    public static function get_instagram_userid($info, $accessToken, $args)
    {
        $url = 'https://api.instagram.com/v1/users/search?q='.$info.'&access_token='.$accessToken;
        $oSearchProfile = wp_remote_get( esc_url_raw( $url ), $args);
        if ( !empty($oSearchProfile) && !is_wp_error($oSearchProfile) )
        {
            $oSearchProfile = wp_remote_retrieve_body($oSearchProfile);
            $oSearchProfile = json_decode($oSearchProfile);

            if ( $oSearchProfile->meta->code === 200 )
            {
                foreach ( $oSearchProfile->data as $oInfo )
                {
                    if ( $oInfo->username === $info )
                    {
                        return $oInfo->id;
                    }
                }
            }
        }

        return false;
    }

    public static function handle_instagram_feed($info, $accessToken, $count=6, $type='self')
    {
        $args = array( 'decompress' => false, 'timeout' => 30, 'sslverify'   => true );
        if ( $type == 'self' )
        {
            return self::get_instagram_images($info, $accessToken, $count, $args);
        }else{
            $userID = self::get_instagram_userid($info, $accessToken, $args);

            if ( !empty($userID) )
            {
                return self::get_instagram_images($userID, $accessToken, $count, $args);
            }
        }

    }

    /**
     * Using instagram information, which has been supplied, We will get Instagram Images now
     * @since 1.0
     */
    public static function get_instagram_images($info, $accessToken, $count, $args)
    {
        $url   = 'https://api.instagram.com/v1/users/'.$info.'/media/recent?access_token='.$accessToken.'&count='.$count;

        $oInstagram = wp_remote_get( esc_url_raw( $url ), $args);

        if ( !is_wp_error($oInstagram) )
        {
            $oInstagram = wp_remote_retrieve_body($oInstagram);
            $oInstagram = json_decode($oInstagram);

            if ( $oInstagram->meta->code === 200 )
            {
               return $oInstagram->data;
            }
        }

        return null;
    }

    /**
     * Render custom post's category
     * @since 1.0
     */
    public static function the_terms($postID, $taxonomy='', $oTerms=array())
    {
        $split = ',';

        if ( empty($oTerms) )
        {
            $oTerms = wp_get_post_terms($postID, $taxonomy);
        }

        if ( !empty($oTerms) && !is_wp_error($oTerms) )
        {
            $size = count($oTerms);
            $i    = 1;
            foreach ( $oTerms as $oTerm )
            {
                ?>
                <a href="<?php echo esc_url(get_term_link($oTerm)); ?>"><?php echo esc_html($oTerm->name); ?></a>
                <?php
                if ( $size != $i )
                {
                    echo esc_html($split);
                }
                $i++;
            }
        }
    }

    /**
     * Query Portfolio
     * @since 1.0
     * @theme OZ
     */
    public function oz_query_portfolio($query, $aPortfolioData, $atts, $device)
    {
        include get_template_directory() . '/admin/public/template/vc/portfolio/item.php';
    }

    /**
     * Modifying args before query portfolio in ajax
     *
     * @since 1.0
     * @args Array
     */
    public function filter_args_before_query_projects_in_ajax($aArgs)
    {
        if ( isset($aArgs['post_types']) && ( is_array($aArgs) && in_array('portfolio', $aArgs) ) || ( is_string($aArgs['post_types']) && $aArgs['post_types'] == 'portfolio' ) ){
            $aArgs['taxonomy']  = 'portfolio_category';
        }

        if ( $aArgs['windowWidth'] <= 480 ) {
            $device = 'extra_small';
        }elseif ( $aArgs['windowWidth'] <= 768  ){
            $device = 'small';
        }elseif ( $aArgs['windowWidth'] <= 1024 ){
            $device = 'medium';
        }else{
            $device = 'large';
        }

        if ( isset($aArgs['additional']['parse_data']) && isset($aArgs['additional']['parse_data']) ) {

            if ( !empty($aArgs['additional']['parse_data']['devices_settings'][$device]['amount_of_loadmore']) )
            {
                $aArgs['posts_per_page'] = absint($aArgs['additional']['parse_data']['devices_settings'][$device]['amount_of_loadmore']);
            }
        }

        $aArgs['shortcode'] = isset($aArgs['shortcode']) && !empty($aArgs['shortcode']) ? $aArgs['shortcode'] : 'wiloke_design_portfolio';
        $aArgs['taxonomy'] = 'portfolio_category';

        return $aArgs;
    }

    /**
     * Modify the structure of item in loadmore action
     * @since 1.0
     * @$post Object
     * @$aArgs The Settings of the shortcode
     * @$wilokeI  Order of item
     */
    public function loadmore_project($html, $post, $aArgs, $wilokeI)
    {
        if ( $aArgs['shortcode'] == 'wiloke_design_portfolio' )
        {
            $atts           = $aArgs['additional'];
            $the_post       = $post;
            $layout = $aArgs['additional']['parse_data']['layout'];

            $aPortfolioData = $aArgs['additional']['parse_data'][$layout];
            if ( $aArgs['additional']['is_used_design_in_loadmore'] != 'yes' )
            {
                $aPortfolioData['items_size'][$wilokeI] = 'cube';
            }

            $aPortfolioData = wiloke_parse_item_size($aPortfolioData);

            ob_start();
            include Wiloke::$public_path.'template/vc/portfolio/item.php';
            $html = ob_get_contents();
            ob_end_clean();
        }

        return $html;
    }

    /**
     * Creating Load more btn
     * @since 1.0
     */
    public function render_loadmore_btn($query, $aParseData, $atts)
    {
        if ( ($atts['loadmore_type'] == 'none') ) {
            return;
        }

        if ( isset($aParseData['general_settings']) ){
            if ( absint($aParseData['general_settings']['number_of_posts']) >= absint($query->found_posts) ){
                return;
            }
        }

        $btnClass       = 'wil-btn wiloke-loadmore work__loadmore-button wiloke-btn-infinite-scroll is_now_allow';
        $onlyOneTime    = 'no';
        $wrapper = 'text-center work__loadmore-wrapper ';
        if ( $atts['loadmore_type'] == 'infinite_scroll'  )
        {
            $btnClass .= ' is_infinite_scroll';
            $wrapper .= 'work__loadmore--1';
        }elseif ( $atts['loadmore_type'] == 'btn' )
        {
            $btnClass .= ' btn-loadmore';
            $wrapper .= 'work__loadmore--2';
        }elseif ( $atts['loadmore_type'] == 'mixed_loadmore_infinite_scroll' ){
            $btnClass .= ' mixed-loadmore-and-infinite-scroll';
            $wrapper .= 'work__loadmore--3';
        }

        $atts['terms']          = !empty($atts['terms']) ? implode(',', $atts['terms']) : '';
        $atts['parse_data']     = $aParseData;
        $atts['post__not_in']   = implode(',', $atts['post__not_in']);
        ?>
        <div class="<?php echo esc_attr($wrapper); ?>">

            <div class="wil-work-loading">
                <div class="work-loadmore__btn">
                    <div class="wil-loader"></div>
                </div>
            </div>

            <button
                <?php
                    WilokePublic::render_attributes(
                      array(
                          'class'              => $btnClass,
                          'data-id'            => $atts['isotop_id'],
                          'data-postids'       => $atts['post__not_in'],
                          'data-termids'       => $atts['terms'],
                          'data-nonce'         => wp_create_nonce('wiloke_ajax_nonce'),
                          'data-max_posts'     => absint($query->found_posts),
                          'data-additional'    => json_encode($atts),
                          'data-only-one-time' => $onlyOneTime
                      )
                    );
                ?>
                ><?php echo esc_html($atts['loadmore_btn_name']); ?></button>
        </div>
        <?php
    }

    /**
     * Modify Comment Structure
     * @since 1.0
     */
    public function solve_stupid_idea_of_wordpressdotcom($aFields)
    {
        if ( is_user_logged_in() )
        {
            return $aFields;
        }

        $aComments = $aFields['comment'];
        unset($aFields['comment']);

        return (array)$aFields + (array)$aComments;
    }

    /**
     * Render pagination
     * @since 1.0
     */
    public static function render_pagination($wp_query=null, $atts=null, $isShop=false, $aSettings=array(), $postPerPage=null)
    {
        if ( isset($atts['pagination_type']) && $atts['pagination_type'] == 'none' )
        {
            return false;
        }

        if ( empty($wp_query) )
        {
            global $wp_query;
        }
        
        if ( empty($wp_query) || ( isset($wp_query->max_num_pages) && $wp_query->max_num_pages < 1 ) ) {
            return false;
        }

        $cssClass = 'wil-pagination';
        $isWooLoadMore = false;

        if ( function_exists('is_shop') && is_shop() ) {
            global $wilokeTweakWoocommercePagination, $wilokeWoocommerceDesignedShop;

            if ( !empty($wilokeTweakWoocommercePagination) ) {
                $maxPaged = ceil($wp_query->found_posts/$wilokeTweakWoocommercePagination);
            }

            if ( !empty($wilokeWoocommerceDesignedShop) && $wilokeWoocommerceDesignedShop['pagination_type'] === 'ajax' ) {
                $cssClass .= ' ajax wil-woocommerce-nav hidden';
                $aSettings = $wilokeWoocommerceDesignedShop;
                $isWooLoadMore = true;
            }
            $atts['is_no_need_render_preloader'] = true;
        }elseif ( $isShop ){
            $wilokeTweakWoocommercePagination = $postPerPage;
            $maxPaged = ceil($wp_query->found_posts/$wilokeTweakWoocommercePagination);
            $atts['is_no_need_render_preloader'] = true;
        }else{
            $wilokeTweakWoocommercePagination = null;
        }

        if ( isset($atts['kind_of_pagination']) && $atts['kind_of_pagination'] == 'infinite_scroll' )
        {
            $cssClass .= ' ' . 'infinite_scroll visibility_hidden';
        }

        $paged = isset($atts['paged']) && !empty($atts['paged']) ? $atts['paged'] : get_query_var('paged', 1);
        $cssClass = isset($atts['not_reload_page']) ? $cssClass . ' ' . $atts['not_reload_page'] : $cssClass;

        ?>
        <?php  if ( $isWooLoadMore ) : ?>
        <div id="wil-shop-loadmore" class="text-center work__loadmore-wrapper work__loadmore--2">
            <div class="wil-work-loading">
                <div class="work-loadmore__btn">
                    <div class="wil-loader"></div>
                </div>
            </div>
            <button class="wil-btn wiloke-loadmore"><?php esc_html_e('Load more', 'oz'); ?></button>
        </div>
        <?php endif; ?>

        <!-- Navigation -->
        <div class="<?php echo esc_attr($cssClass); ?>" data-settings="<?php echo esc_attr(json_encode($aSettings)); ?>" data-query="<?php echo esc_attr(json_encode($wp_query->query_vars)); ?>" data-postsperpage="<?php echo esc_attr($wilokeTweakWoocommercePagination); ?>" data-nonce="<?php echo esc_attr(wp_create_nonce('wiloke-blog-nonce')); ?>">
            <?php
            $big = 999999999; // need an unlikely integer
            echo paginate_links( array(
                'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
                'format'    => '?paged=%#%',
                'show_all'  => isset($atts['is_ajax_loading']) && $atts['is_ajax_loading'] ? true : false,
                'prev_next' => isset($atts['is_ajax_loading']) && $atts['is_ajax_loading'] ? false : true,
                'current'   => max( 1, $paged ),
                'prev_text' => '<i class="fa fa-angle-left"></i>',
                'next_text' => '<i class="fa fa-angle-right"></i>',
                'total'     => isset($maxPaged) ? $maxPaged : $wp_query->max_num_pages
            ) );
            ?>
        </div>

        <?php if ( !isset($atts['is_no_need_render_preloader']) || !$atts['is_no_need_render_preloader'] ) : ?>
        <div class="wil-loader"></div>
        <?php endif; ?>
        <!-- End / Navigation -->
        <?php
    }

    /**
     * Set Products page page
     * @since 1.0
     */
    public function products_per_page($query)
    {
        global $wiloke;

        if ( !function_exists('is_shop') || ( !is_shop() && !is_product_category() ) || !$query->is_main_query() )
        {
            return;
        }

        $query->set( 'posts_per_page', $wiloke->aThemeOptions['woocommerce_posts_page_page'] );
        return;
    }

    public function product_filter_posts_per_page($cols){
        if ( is_shop() ) {
            $shopID = get_option('woocommerce_shop_page_id');
            $checkStatus = Wiloke::getPostMetaCaching($shopID, 'woocommerce_settings');

            if ( !isset($checkStatus['is_wiloke_design_shop']) && ($checkStatus['is_wiloke_design_shop'] == 'no')  ) {
                global $wiloke;
                if ( isset($wiloke->aThemeOptions['woocommerce_posts_page_page']) && !empty($wiloke->aThemeOptions['woocommerce_posts_page_page']) ) {
                    return $wiloke->aThemeOptions['woocommerce_posts_page_page'];
                }
            }
        }

        return $cols;
    }

    /**
     * Remove action
     * @since 1.0
     */
    public function remove_actions()
    {
        if ( !$this->is_allow_breadcrumb() )
        {
            remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
        }
    }

    /**
     * Modifying wrapper class on the single page
     * @since 1.0
     */
    public function modify_wrapper_class_on_single($css){
        global $post, $wiloke;
        if ( is_singular('portfolio') ) {
            $aProject = Wiloke::getPostMetaCaching($post->ID, 'project_general_settings');

            if ( $aProject['project_style'] == 'inherit' ){
                $aProject['project_style'] = $wiloke->aThemeOptions['project_style'];
            }

            if ( $aProject['project_boxed'] == 'inherit' ){
                $aProject['project_boxed'] = $wiloke->aThemeOptions['project_boxed'];
            }

            if ( $aProject['project_style'] == 'style1' ) {
                $css = 'wil-wrapper wil-work-detail';
            }else{
                $css = 'wil-wrapper wil-work-detail wil-work-detail--2col';
            }

            if ( $aProject['project_boxed'] == 'enable' ){
                $css .= ' wil-wrapper--boxed';
            }

        }elseif( is_single() ) {
            $css = 'wil-wrapper wil-wrapper--boxed wil-blog-detail';
        }

        return $css;
    }

    /**
     * Bar info
     * @since 1.0
     */
    public static function barinfo(){
        global $wiloke, $post;
        $breadCrumb = null;

        if ( is_category()  )
        {
            $category = get_category( get_query_var( 'cat' ) );

            if ( !empty($category->category_parent) )
            {
                $breadCrumb .= '<li><a href="'.get_category_link($category->category_parent).'">'.get_cat_name($category->category_parent).'</a></li>';
            }

            $breadCrumb .= '<li class="active"><span>'.$category->name.'</span></li>';

        }elseif( is_tag() )
        {
            $breadCrumb   .= '<li class="active"><span>'.esc_html__('Tag:', 'oz').single_tag_title('', false).'</span></li>';
        }elseif( is_search() )
        {
            $breadCrumb   .= '<li class="active"><span>'.esc_html__('Search: ', 'oz') .get_search_query().'</span></li>';
        }elseif( is_author() )
        {
            $breadCrumb   .= '<li class="active"><span>'.esc_html__('Author: ', 'oz') . get_the_author() . '</span></li>';

        }elseif ( is_tax() ){
            $term = get_queried_object();
            $breadCrumb .= '<li class="active"><span>'.$term->name.'</span></li>';

        }elseif ( is_archive() )
        {
            if ( is_year() ) {
                $title = sprintf( esc_html__('Year: %s', 'oz'), get_the_date( _x( 'Y', 'yearly archives date format', 'oz' ) ) );
            } elseif ( is_month() ) {
                $title = sprintf( esc_html__('Month: %s', 'oz'), get_the_date( _x( 'F Y', 'monthly archives date format', 'oz' ) ) );
            } elseif ( is_day() ) {
                $title = sprintf( esc_html__('Day: %s', 'oz'), get_the_date( _x( 'F j, Y', 'daily archives date format', 'oz' ) ) );
            }else{
                $title  = post_type_archive_title( '', false );
            }

            $breadCrumb .= '<li class="active"><span>'.$title.'</span></li>';

        }elseif( is_single() || is_page() )
        {
            if ( has_category() )
            {
                $aParentCats = get_the_category($post->ID);
                if ( !empty($aParentCats) )
                {
                    $cats = '';
                    foreach ( $aParentCats as $oCat )
                    {
                        $cats .= '<a href="'.get_term_link($oCat->term_id).'">'.$oCat->name.'</a>, ';
                    }
                    $cats = rtrim($cats, ', ');
                    $breadCrumb   .= '<li>'.$cats.'</li>';
                }
            }

            if ( is_page() ){
	            $breadCrumb .= '<li class="active"><span>'.get_the_title($post->ID).'</span></li>';
            }

        }elseif ( (is_home() && ($post->ID = get_option('page_for_posts'))) ){
		    $breadCrumb .= '<li class="active"><span>'.get_the_title($post->ID).'</span></li>';
        }

        if ( get_option('show_on_front') === 'page'  )
        {
            $homeURL = get_option('page_on_front');
            $homeURL = get_permalink($homeURL);
        }else{
            $homeURL = get_option('siteurl');
        }

        if ( empty($breadCrumb) && empty($homeURL) ) {
            return false;
        }
        ?>
            <!-- End / Header-->
            <div class="wil-section-top">
                <div class="wil-tb">
                    <div class="wil-tb__cell">
                        <nav class="wil-breadcrumb wil-animation">
                                <ol class="breadcrumb">
                                    <?php if ( is_tax('portfolio_category') && !empty($wiloke->aThemeOptions['portfolio_page']) ) : ?>
                                        <li><a href="<?php echo esc_url(get_permalink($wiloke->aThemeOptions['portfolio_page'])); ?>"><?php esc_html_e('Home', 'oz'); ?></a></li>
                                    <?php else : ?>
                                        <li><a href="<?php echo esc_url($homeURL); ?>"><?php esc_html_e('Home', 'oz'); ?></a></li>
                                    <?php endif; ?>
                                    <?php if ( !empty($breadCrumb) ) : ?>
                                        <?php Wiloke::wiloke_kses_simple_html($breadCrumb); ?>
                                    <?php else : ?>
                                        <li class="active"><span><?php echo esc_html(get_option('blogname')); ?></span></li>
                                    <?php endif; ?>
                                </ol>
                            </nav>
                    </div>
                    <?php do_action('wiloke/oz/inside_breadcrumb');  ?>
                </div>
            </div>
            <!-- Section-->
        <?php
    }

    public function is_allow_breadcrumb(){
        global $wiloke, $post;

        if ( empty($post) || is_singular('portfolio') ) {
            return false;
        }

        if ( function_exists('is_shop') && is_shop() ){
            $postID = get_option('woocommerce_shop_page_id');
        }else{
            $postID = $post->ID;
        }

        $aPageSettings = Wiloke::getPostMetaCaching($postID, 'single_page_settings');

        if ( !isset($aPageSettings['toggle_breadcrumb']) || ($aPageSettings['toggle_breadcrumb'] != 'disable') ) {
            if ( !empty($wiloke->aThemeOptions['general_breadcrumb']) ) {
                foreach ( $wiloke->aThemeOptions['general_breadcrumb'] as $target ) {
                    if ( is_singular($target) || is_tax($target) ) {
                        return false;
                    }elseif ( $target == 'author' && is_author() ) {
                        return false;
                    }
                }
            }

            return true;
        }elseif ( $aPageSettings['toggle_breadcrumb'] == 'enable' ) {
            return true;
        }

        return false;
    }

    public function breadcrumb(){
        if ( $this->is_allow_breadcrumb() ){
            self::barinfo();
        }
    }

    /**
     * Replace the default social network
     * @since 1.0
     */
    public function replace_the_default_sharing_wrapper_class($atts){
        return $atts . ' wil-share';
    }

    /**
     * Re-structure Social Item
     * @since 1.0
     */
    public function restructure_sharing_social_item($beforeSocial, $socialIcon, $title, $afterSocial){
        Wiloke::wiloke_kses_simple_html($beforeSocial);
        ?>
            <i class="<?php echo esc_attr($socialIcon); ?>"></i><span><?php echo esc_html($title); ?></span>
        <?php
        Wiloke::wiloke_kses_simple_html($afterSocial);
    }

    /**
     * Render Footer
     * @since 1.0
     */
    public function render_before_footer_widget(){
        global $wiloke;
       
        if ( !isset($wiloke->aThemeOptions['footer_toggle_before_widget']) || ($wiloke->aThemeOptions['footer_toggle_before_widget'] === 'disable') || !isset($wiloke->aThemeOptions['footer_boxes_content']) || (count($wiloke->aThemeOptions['footer_boxes_content']) < 1) ) {
            return;
        }
        ?>
        <div class="row">
            <?php
            if ( empty($wiloke->aThemeOptions['footer_boxes_content']) ){
                return;
            }
            foreach ( $wiloke->aThemeOptions['footer_boxes_content'] as $aBoxContent ) :
                $bgID = wp_get_attachment_image_url($aBoxContent['bg']['id']);
                if ( empty($bgID) ) {
                    continue;
                }
            ?>
            <div class="col-md-4">
                <div class="wil-icon-box <?php echo esc_attr($wiloke->aThemeOptions['footer_toggle_boxes_content_animation']); ?> text-center">
                    <a href="<?php echo esc_url($aBoxContent['link']); ?>">
                        <div class="wil-icon-box__media" style="background-image: url(<?php echo esc_url(wp_get_attachment_image_url($aBoxContent['bg']['id'], array(490, 300))) ?>);"></div>
                        <div class="wil-icon-box__content">
                            <div class="wil-icon--gradient"><i class="<?php echo esc_attr($aBoxContent['icon']); ?>"></i></div>
                            <div class="wil-icon-box__divider"></div>
                            <h2 class="wil-icon-box__title"><?php echo esc_html($aBoxContent['title']); ?></h2>
                            <?php if ( !empty($aBoxContent['description']) ) : ?>
                            <div class="wil-icon-box__text">
                                <p><?php Wiloke::wiloke_kses_simple_html($aBoxContent['description']); ?></p>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="wil-overlay wil-icon-box__overlay"></div>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php
    }

    /**
     * Render Related Posts
     * @since 1.0
     */
    public function render_related_articles(){
        global $wiloke, $post;
        if ( $wiloke->aThemeOptions['single_post_toggle_related_posts'] == 'disable' ) {
            return;
        }

        $loop = $wiloke->aThemeOptions['single_post_related_number_of_articles'] == 'col-md-4' ? 3 : 2;
        $oTerms = wp_get_post_terms($post->ID, 'category');

        $args = array(
            'post_type' => 'post',
            'post_status'=>'publish',
            'posts_per_page'=>$loop,
            'post__not_in'=>array($post->ID),
            'ignore_sticky_posts'=>1
        );

        if ( !empty($oTerms) && !is_wp_error($oTerms) ) {
            foreach ( $oTerms as $oTerm  ) {
                $args['category__in'][] = $oTerm->term_id;
            }
        }else{
            $oTerms = wp_get_post_terms($post->ID, 'post_tag');
            foreach ( $oTerms as $oTerm  ) {
                $args['tag__in'][] = $oTerm->term_id;
            }
        }

        if ( !isset($args['category__in']) && !isset($args['tag__in']) ) {
            return;
        }

        $query = new WP_Query($args);

        if ( !$query->have_posts() ){
            wp_reset_postdata();
            return;
        }

        ?>
        <div class="wil-related-post">
            <h3 class="wil-related-post__title"><?php Wiloke::wiloke_kses_simple_html($wiloke->aThemeOptions['single_post_related_posts_title']); ?></h3>
            <div class="row">
                <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                <div class="<?php echo esc_attr($wiloke->aThemeOptions['single_post_related_number_of_articles']); ?>">
                    <div class="wil-related-post__item">
                        <div class="wil-related-post__media">
                            <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('medium'); ?></a>
                        </div>
                        <div class="wil-related-post__body">
                            <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                            <div class="wil-related-post__date"><a href="<?php the_permalink(); ?>"><?php Wiloke::wiloke_render_date($post->ID); ?></a></div>
                        </div>
                    </div>
                </div>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        </div>
        <?php
    }

    /**
     * Rest of footer
     * @since 1.0
     */
    public function render_rest_of_footer(){
        global $wiloke;
        ?>
        <div class="wil-footer-last">
            <div class="wil-tb wil-footer-last__tb">
                <div class="wil-tb__cell text-left">
                    <?php if ( !empty($wiloke->aThemeOptions['footer_other_information']) ) : ?>
                    <div class="wil-footer__text">
                        <?php Wiloke::wiloke_kses_simple_html($this->parsing_social_network($wiloke->aThemeOptions['footer_other_information'])); ?>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="wil-tb__cell text-center">
                    <div class="wil-scroll-top"><span></span><span></span></div>
                </div>
                <div class="wil-tb__cell text-right">
                    <div class="wil-footer__text">
                        <?php Wiloke::wiloke_kses_simple_html( str_replace('[oz_apcy]', date('Y'),$wiloke->aThemeOptions['footer_copyright']) ); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Parsing [wiloke_social_networks]
     * @since 1.0
     */
    public function parsing_social_network($text){
        $text = preg_replace_callback('/\[wiloke_social_networks\]/', function($match){
            if ( !empty($match) ) {
                global $wiloke;
                ob_start();
                WilokeSocialNetworks::render_socials($wiloke->aThemeOptions, ', ');
                $content = ob_get_contents();
                ob_end_clean();
                return $content;
            }
        }, $text);

        return $text;
    }

    /**
     * Comment Template
     * @since 1.0
     */
    public static function comment_template($comment, $args, $depth){
        $GLOBALS['comment'] = $comment;
        switch ( $comment->comment_type ) :
        case 'pingback' :
        case 'trackback' :
        // Display trackbacks differently than normal comments.
        ?>
        <li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
            <p><?php esc_html_e( 'Pingback:', 'oz' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( esc_html__( '(Edit)', 'oz' ), '<span class="edit-link">', '</span>' ); ?></p>
            <?php
            break;
            default :
            ?>
        <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
            <div id="comment-<?php comment_ID(); ?>" class="comment-box">
                <div class="comment-author">
                    <?php
                        $commentID    = get_comment_ID();
                        $authorInfo   = get_comment($commentID);

                        if (  isset($authorInfo->user_id) )
                        {
                            $userData = get_user_meta($authorInfo->user_id);
                            if ( isset($userData['pi_user_info'][0]) )
                            {
                                $image = isset($aAuthorData['wiloke_avatar'][0]) && !empty($aAuthorData['avatar'][0]) ? $aAuthorData['avatar'][0] : '';
                                if ( !empty($image) )
                                {
                                    echo '<a href="'.esc_url(get_comment_author_url()).'"><img src="'.esc_url($image).'" class="pi-comment-avatar"  alt="'. esc_attr( $userData['nickname'][0] ) .'"></a>';
                                }
                            }
                        }

                        if ( empty($image) )
                        {
                            echo '<a href="'.esc_url(get_comment_author_url()).'">'.get_avatar( $comment, 100 ).'</a>';
                        }
                    ?>
                </div>
                <div class="comment-body">
                    <?php Wiloke::wiloke_kses_simple_html( sprintf('<cite class="fn">%1$s</cite>',get_comment_author_link())); ?>
                    <?php
                    printf( '<span class="comment-meta">%1$s</span>',
                        /* translators: 1: date, 2: time */
                        Wiloke::wiloke_kses_simple_html(sprintf( '%1$s', get_comment_date()), true)
                    );
                    ?>

                    <?php if ( '0' == $comment->comment_approved ) : ?>
                        <p><?php esc_html_e( 'Your comment is awaiting moderation.', 'oz' ); ?></p>
                    <?php endif; ?>
                    <?php comment_text(); ?>
                </div>
                <div class="comment-edit-reply">
                    <?php
                    if ( current_user_can( 'edit_comment', $comment->comment_ID ) )
                    {
                        edit_comment_link( esc_html__( 'Edit', 'oz' ), '', '' );
                    }
                    ?>
                    <?php comment_reply_link( array_merge( $args, array( 'reply_text' => esc_html__( 'Reply', 'oz' ), 'after' => '', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
                </div><!-- .reply -->
            </div><!-- #comment-## -->
        <?php
        break;
        endswitch; // end comment_type check
    }

    /**
     * Render Subscribe
     * @since 1.0
     */
    public function render_popup_subscribe(){
        ?>
        <section id="wil-subscribe" class="wil-box-toggle wil-box-toggle--anim-1 body-overflow-hidden">
            <div class="wil-tb">
                <div class="wil-tb__cell"><span class="wil-box-toggle__close"><span></span><span></span></span>
                    <form id="wiloke-ps-mailchimp" method="POST">
                        <p class="error-response-here"></p>
                        <div class="form-item message-success-also-here">
                            <label for="wiloke-ps-email"><?php esc_html_e('Your email*', 'oz'); ?></label>
                            <input id="wiloke-ps-email" type="email" name="email" required />
                        </div>
                        <div class="form-submit">
                            <input id="wiloke-ps-btn" type="submit" value="<?php esc_html_e('Subscribe', 'oz'); ?>" data-sending="<?php esc_html_e('Sending...', 'oz'); ?>" data-security="<?php echo esc_attr(wp_create_nonce('pi_subscribe_nonce')); ?>" />
                        </div>
                    </form>
                </div>
            </div>
        </section>
        <?php
    }

    /**
     * Render Preloader
     * @since 1.0
     */
    public function render_preloader(){
        global $wiloke;
        if ( isset($wiloke->aThemeOptions['is_preloader']) && ($wiloke->aThemeOptions['is_preloader'] === 'yes') || (isset($wiloke->aThemeOptions['advanced_ajax_feature']) && $wiloke->aThemeOptions['advanced_ajax_feature'] == 'enable') ) :
            $customPreloader = isset($wiloke->aThemeOptions['general_preloader']['url']) && !empty($wiloke->aThemeOptions['general_preloader']['url']) ? $wiloke->aThemeOptions['general_preloader']['url'] : '';
        ?>
        <div class="wil-preloader">
            <?php if ( empty($customPreloader) ) : ?>
            <div class="wil-spinner">
                <div class="wil-ball"></div>
                <p><?php esc_html_e('Loading', 'oz'); ?></p>
            </div>
            <?php else : ?>
                <div class="wil-preloader__img"><img src="<?php echo esc_url($customPreloader); ?>" alt="<?php esc_html_e('Preloader', 'oz'); ?>"></div>
            <?php endif; ?>
        </div>
        <?php
        endif;
    }

    /**
     * WooCommerce
     * @since 1.0
     */
    public function woocommerce_product_cat_open($category){
        $thumbnailID = get_woocommerce_term_meta( $category->term_id, 'thumbnail_id', true );
        if ( $thumbnailID ) {
            $image = wp_get_attachment_image_url($thumbnailID, 'large');
        } else {
            $image = wc_placeholder_img_src();
        }

        echo '<div style="background-image: url('.esc_url($image).')" class="product__header">';
    }

    public function woocommerce_product_cat_title($category){
        ?>
        <div class="product__body">
            <h2 class="product__name"><a href="<?php echo esc_url(get_term_link($category->term_id)); ?>"><?php Wiloke::wiloke_kses_simple_html($category->name); ?></a></h2>
            <?php
            if ( $category->count > 0 )
                echo apply_filters( 'woocommerce_subcategory_count_html', ' <ins><span class="amount">(' . $category->count . ')</span></ins>', $category );
            ?>
        </div>
        <?php
    }

    public function woocommerce_product_header_cat_close($category){
        echo '</div><!-- End / WooCommerce product Category-->';
    }

    public function product_navigator(){
        ?>
        <section class="wil-section-top wil-animation"><div class="wil-tb"> <!-- Start / Product navigator -->
        <?php
    }

    public function product_breadcrumb($args){
        $args = array(
            'delimiter'   => '',
            'wrap_before' => '<div class="wil-tb__cell"><div class="wil-animation"><nav class="woocommerce-breadcrumb"><ol class="breadcrumb">',
            'wrap_after'  => '</ol></nav></div></div><!--End product breadcrumb-->',
            'before'      => '<li>',
            'after'       => '</li>',
            'home'        => _x( 'Home', 'breadcrumb', 'oz' )
        );
        return $args;
    }

    public function product_section(){
        global $wilokeWoocommerceShopSetings;
        if ( !isset($wilokeWoocommerceShopSetings['is_wiloke_design_shop']) || ($wilokeWoocommerceShopSetings['is_wiloke_design_shop'] != 'yes') ) :
        ?>
        <section class="wil-section pt-0"> <!-- Start / Section Close -->
        <?php
        endif;
    }

    public function product_section_close(){
        global $wilokeWoocommerceShopSetings;
        if ( !isset($wilokeWoocommerceShopSetings['is_wiloke_design_shop']) || ($wilokeWoocommerceShopSetings['is_wiloke_design_shop'] != 'yes') ) :
        ?>
        </section> <!-- END / Section Close -->
        <?php
        endif;
    }

    public function product_mini_cart($isTop=false){
        if ( class_exists('Woocommerce') ) :
            if ( !$isTop ) {
                echo '<div class="wil-tb__cell">';
            }
            ?>
            <div class="wil-minicart woocommerce wil-animation">
                <div class="wil-minicart__inner">
                    <a href="#wil-minicart-container" class="wil-toggle-minicart">
                        <div class="wil-toggle-minicart__inner"><i class="icon icon-ecommerce-bag"></i>
                            <div class="wil-toggle-minicart__number">
                                <span class="wil-toggle-minicart__number-item"></span>
                                <span class="wil-toggle-minicart__number-item"></span>
                            </div>
                        </div>
                    </a>
                    <div id="wil-minicart-container" class="wil-box-toggle">
                        <div id="cart-mini-content" class="widget widget_shopping_cart">
                            <div class="widget_shopping_cart_content">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            if ( !$isTop ) {
                echo '</div>';
            }
        endif;
    }

    public function product_navigator_close(){
        ?>
        </div></section> <!-- END / Product Navigatior -->
        <?php
    }

    public function product_header($size) {
        global $post;
        $img = get_the_post_thumbnail_url($post->ID, $size);
        ?>
        <div style="background-image: url(<?php echo esc_url($img); ?>)" class="product__header"> <!-- Start / Product Header -->
            <a class="wiloke-product-link" href="<?php the_permalink(); ?>"><img src="<?php echo esc_url($img); ?>" alt="<?php the_title(); ?>" /></a>
        <?php
    }

    public function product_add_to_cart(){
        woocommerce_template_loop_add_to_cart();
    }

    public function product_out_of_stock(){
        global $product;

        if ( $product->get_stock_status() === 'outofstock' ) {
            ?>
            <div class="out-of-stock-label">
                <span><?php esc_html_e('Out of stock', 'oz'); ?></span>
            </div>
            <?php
        }
    }

    public function product_header_close(){
    ?>
        </div> <!-- End Header Close -->
    <?php
    }

    public function product_before_info(){
    ?>
        <div class="product__body"> <!-- Start / Product Body -->
    <?php
    }

    public function product_info(){
        ?>
        <h2 class="product__name">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h2>
        <?php
        woocommerce_template_loop_price();
    }

    public function product_before_info_close(){
    ?>
        </div> <!-- End / product info -->
    <?php
    }

    public function remove_some_woocommerce_scripts(){
        // Unless we're in the store, remove all the cruft!
        if ( is_404() ){
            return;
        }

        global $post;
        if ( function_exists('is_woocommerce') && ( !is_woocommerce() && !is_cart() && !is_checkout() && !has_shortcode($post->post_content, 'wiloke_design_shop') ) ) {
            wp_dequeue_style( 'woocommerce-layout' );
            wp_dequeue_style( 'woocommerce-smallscreen' );
            wp_dequeue_style( 'woocommerce-general' );
            wp_dequeue_style( 'woocommerce_frontend_styles' );
            wp_dequeue_style( 'woocommerce_fancybox_styles' );
            wp_dequeue_style( 'woocommerce_chosen_styles' );
            wp_dequeue_style( 'woocommerce_prettyPhoto_css' );
            wp_dequeue_style( 'select2' );
            wp_dequeue_script( 'wc-add-payment-method' );
            wp_dequeue_script( 'wc-lost-password' );
            wp_dequeue_script( 'wc_price_slider' );
            wp_dequeue_script( 'wc-single-product' );
            wp_dequeue_script( 'wc-add-to-cart' );
            wp_dequeue_script( 'wc-credit-card-form' );
            wp_dequeue_script( 'wc-checkout' );
            wp_dequeue_script( 'wc-add-to-cart-variation' );
            wp_dequeue_script( 'wc-single-product' );
            wp_dequeue_script( 'wc-cart' );
            wp_dequeue_script( 'wc-chosen' );
            wp_dequeue_script( 'woocommerce' );
            wp_dequeue_script( 'prettyPhoto' );
            wp_dequeue_script( 'prettyPhoto-init' );
            wp_dequeue_script( 'jquery-blockui' );
            wp_dequeue_script( 'jquery-placeholder' );
            wp_dequeue_script( 'jquery-payment' );
            wp_dequeue_script( 'fancybox' );
            wp_dequeue_script( 'jqueryui' );
        }
    }

    public function product_single_filter_image_html($atts, $postID){
    }

    public function woocommerce_filter_product_loop_in_wiloke_design_shortcode($queryArgs, $atts, $loopName){

        if ( $loopName != 'products' ) {
            return $queryArgs;
        }

        if ( isset($atts['is_wiloke']) && $atts['is_wiloke'] ) {
            $queryArgs['posts_per_page'] = $atts['posts_per_page'];
        }

        return $queryArgs;
    }

    public function product_add_fullwidth_to_woocommercepage($css){
        if ( function_exists('is_woocommerce') ) {
            if ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ){
                return 'col-xs-12 col-md-12';
            }
        }

        return $css;
    }

    public function product_related_products(){
        woocommerce_output_related_products();
    }

    public function product_upsell_display(){
        woocommerce_upsell_display();
    }

    /**
     * Tweak Shop Query
     * @since 1.0
     */
    public function product_tweak_shop_query($args, $atts, $loopName){
        global $wilokeWoocommerceShopSetings, $wilokeIsWooCommerceQuery, $wilokeWooCommerceShopPaged;

        if ( !$wilokeIsWooCommerceQuery || (!isset($wilokeWoocommerceShopSetings['is_wiloke_design_shop']) || ($wilokeWoocommerceShopSetings['is_wiloke_design_shop'] != 'yes')) ) {
            if ( is_woocommerce() ){
                return $args;
            }else{
                return $this->handle_woocommerce_args($args);
            }
        }

        if ( ( (is_product_tag() || is_product_category() || is_shop()) ) && $loopName == 'recent_products' ) 
        {
            return $this->handle_woocommerce_args($args);
        }

        return $args;
    }

    public function handle_woocommerce_args($args){

        if ( isset($args['tax_query']) && !empty($args['tax_query']) ) {
            $checkFisrt = $args['tax_query'][0]['terms'][0];
            if ( absint($checkFisrt) ) {
                $args['tax_query'][0]['field'] = 'term_id';
            }
        }

        $args['paged'] = get_query_var('paged', 1);

        return $args;
    }

    public function product_tweak_pagination_for_woocommerce_sc(){
        self::render_pagination();
    }

    /**
     * Detect current Mobile
     * @since 1.0
     */
    public static function detect_current_device()
    {
        if ( empty(Wiloke::$mobile_detect) ){
            Wiloke::$mobile_detect = new Mobile_Detect();
        }

        if ( Wiloke::$mobile_detect->isMobile() )
        {
            return 'extra_small';
        }elseif ( Wiloke::$mobile_detect->isTablet() )
        {
            return 'small';
        }else{
            return 'large';
        }
    }

    /**
     * Show on
     * @since 1.0
     */
    public function reset_sharing_post_social_show_on($aShowOn){
        unset($aShowOn['archive']);
        unset($aShowOn['home']);
        $aShowOn['project'] = esc_html__('Projects', 'oz');
        return $aShowOn;
    }

    /**
     * Is showing sharing post or not
     * @since 1.0
     */
    public function check_conditional_to_sharing_post($status, $aVal){
        if ( is_singular('portfolio') ){
            if ( !empty($aVal) && in_array('project', (array)$aVal['show_on']) ){
                return true;
            }

            return false;
        }

        return $status;
    }

    /**
     * Sharing Post Title
     * @since 1.0
     */
    public function sharing_post_title($title){
        if ( is_singular('portfolio') ) {
            return '<h2>'.$title.'</h2>';
        }

        return '';
    }

    /**
     * Running Ob Start
     * @since 1.0
     */
    public static function ob_start(){
        global $wiloke;
        if ( isset($wiloke->aThemeOptions['advanced_ajax_feature']) && ($wiloke->aThemeOptions['advanced_ajax_feature'] == 'enable') ) {
            return;
        }
        ob_start();
    }

    /**
     * Full Flush
     * @since 1.0
     */
    public static function full_flush($isEnd=false){
        global $wiloke;
        if ( isset($wiloke->aThemeOptions['advanced_ajax_feature']) && ($wiloke->aThemeOptions['advanced_ajax_feature'] == 'enable') ) {
            return;
        }

        flush();
        ob_flush();

        if ( $isEnd ){
            ob_start();
        }
    }

    /**
     * Filter Post gallery
     * @since 1.0
     */
    public function filter_post_gallery($html, $attr){
        
        if ( !class_exists( 'Jetpack' ) || !(Jetpack::is_module_active( 'tiled-gallery' ) || Jetpack::is_module_active( 'carousel' )) ) {

            add_filter( 'gallery_style', function( $html ) use ( $attr ) {

                $spacing = isset($attr['spacing']) ? $attr['spacing'] : 'none';

                $style = isset($attr['style']) ? $attr['style'] : 'none';

                $html = str_replace( "<div", sprintf( "<div data-spacing='%s' data-style='%s'", esc_attr( $spacing ), esc_attr( $style ) ), $html );

                return $html;

            });
        }

        return $html;
    }

    /**
     * Render Total Cart
     * @since 1.0
     */
    public function product_render_total_cart(){
        $currentCart = absint(WC()->cart->get_cart_contents_count());
        wp_send_json_success(array('total'=>$currentCart));
    }

    /**
     * Filter Header Class
     * @since 1.0
     */
    public function filter_class_of_header_tag($cssClass, $aPageSettings){
        global $wiloke, $post;

        if ( !isset($aPageSettings['header_skin']) || ($aPageSettings['header_skin'] === 'inherit') ){
            $cssClass .= isset($wiloke->aThemeOptions['general_header_skin']) ? ' ' . $wiloke->aThemeOptions['general_header_skin'] : '';
        }else{
            $cssClass .= ' ' . $aPageSettings['header_skin'];
        }
        
        if($aPageSettings['header_set_to_top'] === 'enable'){
            $cssClass .= ' wil-header--absolute';
        }

        if ( !isset($aPageSettings['header_sticky']) || ($aPageSettings['header_sticky'] === 'inherit') ){
            if ( isset($wiloke->aThemeOptions['general_header_sticky']) && ($wiloke->aThemeOptions['general_header_sticky'] === 'enable') ){
                $cssClass .= ' wil-header--sticky';
            }
        }elseif($aPageSettings['header_sticky'] === 'enable'){
            $cssClass .= ' wil-header--sticky';
        }

        return $cssClass;
    }
}