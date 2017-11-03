<?php
/**
 * WilokeTaxonomy Class
 *
 * @category Taxonomy
 * @package Wiloke Framework
 * @author Wiloke Team
 * @version 1.0
 */

if ( !defined('ABSPATH') )
{
    exit;
}

class WilokeTaxonomy
{
    /**
     * List of tax name
     * @since 1.0
     */
    public $aListOfTerms = array();

    /**
     * Whether featured image settings
     * @since 1.0
     */
    public $aListOfFeaturedImage = array();

    /**
     * List of tax configuration
     * @since 1.0
     */
    public $aListOfConfiguration = array();


    public function __construct()
    {
        $this->parse_configuration();
        $this->taxonomy_settings();
    }

    /**
     * Parse data from configuration
     * @since 1.0
     */
    public function parse_configuration()
    {
        global $wiloke;

        if ( !isset($wiloke->aConfigs['taxonomy']['category_portfolio_cat']) ) {
            return;
        }

        if ( isset($wiloke->aConfigs['taxonomy']) && !empty($wiloke->aConfigs['taxonomy']) )
        {
            foreach ( $wiloke->aConfigs['taxonomy'] as $key => $aTaxInfo )
            {
                foreach ( $aTaxInfo['taxonomy'] as $tax )
                {
                    $this->aListOfConfiguration[$tax][] = $aTaxInfo['fields'];

                    if ( !isset($this->aListOfFeaturedImage[$tax]) && $this->has_featured_image($aTaxInfo['fields']) )
                    {
                        $this->aListOfFeaturedImage[$tax] = true;
                    }

                    if ( empty($this->aListOfTerms) || !in_array($tax, $this->aListOfTerms) )
                    {
                        $this->aListOfTerms[]  = $tax;
                    }
                }
            }
        }
    }

    /**
     * The settings has featured image or not
     * @since 1.0
     */
    public function has_featured_image($aSettings)
    {
        foreach ( $aSettings as $aSetting )
        {
            if ( 'featured_image' == $aSetting['id'] )
            {
                return true;
            }
        }

        return false;
    }

    /**
     * Including css and js to taxonomy pages that need to settings
     * @since 1.0
     */
    public function enqueue_scripts()
    {
        global $wiloke;

        if ( !isset($wiloke->aConfigs['taxonomy']['category_portfolio_cat']) && !isset($wiloke->aConfigs['taxonomy']) && !isset($_GET['taxonomy']) ) {
            return false;
        }

        // Check Wiloke Post Format plugin is activating or not
        if ( !wp_script_is('wiloke_post_format', 'enqueued') )
        {
            wp_enqueue_media();

            wp_enqueue_script('wiloke_post_format_ui', WILOKE_AD_SOURCE_URI . 'js/wiloke_post_format.js', array('jquery'), null, true);
            wp_enqueue_style('wiloke_post_format_ui', WILOKE_AD_SOURCE_URI . 'css/wiloke_post_format.css', array(), null);

            wp_enqueue_script('wiloke_taxonomy', WILOKE_AD_SOURCE_URI . 'js/taxonomy.js', array('jquery', 'wiloke_post_format_ui'), null, true);
            wp_enqueue_style('wiloke_taxonomy', WILOKE_AD_SOURCE_URI . 'css/taxonomy.css', array(), '1.0');
        }

        if ( !wp_script_is('wiloke_spectrum', 'enqueued') )
        {
            wp_enqueue_script('wiloke_spectrum', WILOKE_AD_ASSET_URI . 'js/spectrum.js', array('jquery'), null, true);
            wp_enqueue_style('wiloke_spectrum', WILOKE_AD_ASSET_URI . 'css/spectrum.css', array(), null);
        }
    }


    /**
     * Taxonomy Settings
     * @since 1.0
     */
    public function taxonomy_settings()
    {
        global $wiloke;

        if ( empty($this->aListOfTerms) || !$wiloke->kindofrequest('admin') )
        {
            return false;
        }

        foreach ( $this->aListOfTerms as $key )
        {
            add_action( $key.'_edit_form_fields', array($this, 'wiloke_edit_form_field'), 30, 1);

            add_action( 'edited_'.$key, array($this, 'wiloke_save_tax_settings'), 10, 2 );
            add_action( 'create_'.$key, array($this, 'wiloke_save_tax_settings'), 10, 2 );

            add_filter('manage_edit-'.$key.'_columns', array($this, 'wiloke_taxonomy_columns_head'));
            add_filter('manage_'.$key.'_custom_column', array($this, 'wiloke_taxonomy_columns_content_taxonomy'), 10, 3);
        }
    }

    /**
     * In the case of Featured Setting is available, Render Featured Image On Taxonomy Table
     * @since 1.0
     */
    public function wiloke_set_featured_category_column($order)
    {
        return "<a href='".admin_url("edit-tags.php?taxonomy=category&orderby=name&order=$order")."'><span>".esc_html__('Featured Image', 'oz')."</span><span class='sorting-indicator'></span></a>";
    }


    public function wiloke_taxonomy_columns_head($defaults)
    {
        if ( !empty($this->aListOfFeaturedImage) && isset($this->aListOfFeaturedImage[$_GET['taxonomy']]) && $this->aListOfFeaturedImage[$_GET['taxonomy']] == true  )
        {
	        $aNew = array();
            $i = 1;
            foreach ( $defaults as $key => $val )
            {
                if ( $i == 2 )
                {
                    $aNew['featured_image'] = $this->wiloke_set_featured_category_column($key);
                }

                $aNew[$key] = $val;
                $i++;
            }

            return $aNew;
        }

        return $defaults;
    }

    public function wiloke_render_featured_image_of_term($c, $column_name, $term_id)
    {
        $aData = get_option("_wiloke_cat_settings_$term_id");
        if ( isset($aData['featured_image']) && !empty($aData['featured_image']) )
        {
           echo wp_get_attachment_image($aData['featured_image'], 'thumbnail');
        }
    }

    public function wiloke_taxonomy_columns_content_taxonomy($c, $column_name, $term_id)
    {
        if ( $column_name == 'featured_image' )
        {
            $this->wiloke_render_featured_image_of_term($c, $column_name, $term_id);
        }
    }

    public function wiloke_edit_form_field($term)
    {
        if ( empty($this->aListOfTerms) || !in_array($term->taxonomy, $this->aListOfTerms)  )
        {
            return;
        }

        $termID         = $term->term_id;
        $aOptions       = get_option('_wiloke_cat_settings_'.$termID);
        $name           = 'wiloke_cat_settings_'.$termID;
        $taxonomyType   = $term->taxonomy;

        foreach ( $this->aListOfConfiguration[$taxonomyType] as $aFields )
        {
            foreach ( $aFields as $aField )
            {
                if ( isset($aOptions[$aField['id']]) && !empty($aOptions[$aField['id']]) )
                {
                    $value = $aOptions[$aField['id']];
                }else{
                    if ( isset($aField['default']) && !empty($aField['default']) )
                    {
                        $value = $aField['default'];
                    }else{
                        $value = '';
                    }
                }

                $aField['value'] = $value;

                $funcName       = 'wiloke_render_'.$aField['type'].'_field';
                $aField['id']   = $name . '[' . $aField['id'] . ']';

                WilokeHtmlHelper::$funcName($aField);
            }
        }
    }

    public function wiloke_save_tax_settings($termID)
    {
        if ( isset($_POST['wiloke_cat_settings_'.$termID]) && !empty($_POST['wiloke_cat_settings_'.$termID]) )
        {
            do_action('wiloke_before_update_tax', $termID, $_POST['wiloke_cat_settings_'.$termID]);
            update_option('_wiloke_cat_settings_'.$termID, $_POST['wiloke_cat_settings_'.$termID]);
        }
    }
}
