<?php
if ( ! defined( 'ABSPATH' ) )
{
    exit;
}

/*
 *
 * array(
                        'title'   => esc_html__('Rating Flag Settings', 'oz'),
                        'type'    => 'wiloke_repeater_field',
                        'id'      => 'blog_single_rating_flag',
                        'repeatable' => false,
                        'sortable'=> true, // Allow the users to sort the repeater blocks or not
                        'fields'  => array(
                            array(
                                'default'    => array(
                                    array(
                                        'type'       => 'text',
                                        'default'    => 'Excellent',
                                        'name'       => 'excellent'
                                    ),
                                    array(
                                        'type'       => 'text',
                                        'default'    => 'Very Good',
                                        'name'       => 'verygood'
                                    ),
                                    array(
                                        'type'       => 'text',
                                        'default'    => 'EVERAGE',
                                        'name'       => 'everage'
                                    ),
                                    array(
                                        'type'       => 'text',
                                        'default'    => 'Poor',
                                        'name'       => 'poor'
                                    ),
                                    array(
                                        'type'       => 'text',
                                        'default'    => 'Terrible',
                                        'name'       => 'terrible'
                                    ),
                                ),
                                'pattern' => array(
                                    array(
                                        'type'       => 'text',
                                        'default'    => ''
                                    )
                                )
                            )
                        )
                    ),
 */

if ( !class_exists('ReduxFramework_Extension_Wiloke_Repeater') )
{
    class ReduxFramework_Extension_Wiloke_Repeater extends ReduxFramework
    {
        protected $parent;
        public $extension_url;
        public $extension_dir;
        public static $theInstance;
        public $is_field = false;

        public function __construct( $parent ) {

            $this->parent = $parent;
            if ( empty( $this->extension_dir ) ) {
                $this->extension_dir = trailingslashit( str_replace( '\\', '/', get_template_directory() . '/admin/inc/redux-extensions/wiloke-repeater' ) );
            }
            $this->field_name = 'wiloke_repeater_field';
            self::$theInstance = $this;

            $this->is_field = Redux_Helpers::isFieldInUse($parent, $this->field_name);

            add_filter( 'redux/'.$this->parent->args['opt_name'].'/field/class/'.$this->field_name, array( &$this, 'overload_field_path' ) ); // Adds the local field
        }

        public function getInstance() {
            return self::$theInstance;
        }

        // Forces the use of the embeded field path vs what the core typically would use
        public function overload_field_path($field) {
            return  get_template_directory() . '/admin/inc/redux-extensions/wiloke-repeater' . '/'.$this->field_name.'/field_'.$this->field_name.'.php';
        }
    }
}
