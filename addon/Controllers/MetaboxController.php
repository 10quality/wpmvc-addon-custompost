<?php

namespace WPMVC\Addons\Metaboxer\Controllers;

use ReflectionClass;
use WPMVC\Request;
use WPMVC\MVC\Controller;
use WPMVC\Addons\Metaboxer\MetaboxerAddon;
use WPMVC\Addons\Metaboxer\Abstracts\PostModel;
use WPMVC\Addons\Metaboxer\Abstracts\Control;


/**
 * Metabox hooks.
 *
 * @author 10 Quality <info@10quality.com>
 * @package wpmvc-addon-metabox
 * @license MIT
 * @version 1.0.0
 */
class MetaboxController extends Controller
{
    /**
     * Models buffer.
     * @since 1.0.0
     * @var array
     */
    protected static $models = [];
    /**
     * Controls in use.
     * @since 1.0.0
     * @var array
     */
    protected static $controls = [];
    /**
     * Registered controls.
     * @since 1.0.0
     * @var array
     */
    protected static $registered_controls;
    /**
     * Prepares models and enqueues assets.
     * @since 1.0.0
     * 
     * @hook admin_enqueue_scripts
     * 
     * @global WP_post $post
     */
    public function enqueue()
    {
        if ( empty( static::$models ) || empty( static::$controls ) )
            return;
        wpmvc_enqueue_addon_resource( 'font-awesome' );
        wpmvc_enqueue_addon_resource( 'wpmvc-hideshow' );
        wpmvc_enqueue_addon_resource( 'wpmvc-repeater' );
        foreach ( static::$models as $key => $model ) {
            $model->enqueue();
            do_action( 'metaboxer_enqueue_' . $key );
        }
        foreach ( static::$controls as $control ) {
            $control->enqueue();
        }
        wp_enqueue_style( 'metaboxer', addon_assets_url( 'css/metaboxer.css', __DIR__ ), null, '1.0.0' );
        wp_enqueue_script( 'wpmvc-tabs', addon_assets_url( 'js/jquery.tabs.js', __DIR__ ), ['jquery'], '1.0.0' );
    }
    /**
     * Inits by registering metabox models and configurations.
     * @since 1.0.0
     * 
     * @hook add_meta_boxes
     */
    public function init()
    {
        global $post;
        $post_type = Request::input( 'post_type' );
        if ( isset( $post ) )
            $post_type = $post->post_type;
        if ( empty( $post_type ) )
            return;
        // Get models
        static::$models = array_filter( $this->get_models(), function( $model ) use( &$post_type ) {
            return $model->type === $post_type;
        } );
        if ( empty( static::$models ) )
            return;
        // Init models
        if ( isset( $post ) ) {
            static::$models = array_map( function( $model ) use( &$post ) {
                $model->from_post( $post );
                return $model;
            }, static::$models );
        }
        // Registered metaboxes models and obtain controls
        $controls_in_use = [];
        foreach ( static::$models as $key => $model ) {
            foreach ( $model->metaboxes as $metabox_id => $metabox ) {
                $id = $metabox_id . '_' . uniqid();
                add_meta_box(
                    $id,
                    array_key_exists( 'title', $metabox ) ? $metabox['title'] : __( 'Fields', 'wpmvc-addon-metabox' ),
                    [&$this, 'process_' . $key . '@' . $metabox_id],
                    array_key_exists( 'screen', $metabox ) ? $metabox['screen'] : $model->type,
                    array_key_exists( 'context ', $metabox ) ? $metabox['context '] : 'advanced',
                    array_key_exists( 'priority  ', $metabox ) ? $metabox['priority  '] : 'default',
                    array_key_exists( 'args  ', $metabox ) ? $metabox['args  '] : null
                );
                add_filter( 'postbox_classes_' . $model->type. '_' . $id, [&$this, 'css_' . $key . '@' . $metabox_id] );
                // Get controls in use
                if ( !array_key_exists( 'tabs', $metabox ) )
                    continue;
                foreach ( $metabox['tabs'] as $tab ) {
                    if ( !array_key_exists( 'fields', $tab ) )
                        continue;
                    array_map( function( $field ) use( &$controls_in_use ) {
                        if ( ( ! array_key_exists( 'type' , $field ) && ! in_array( 'input', $controls_in_use ) )
                            || ( array_key_exists( 'type' , $field ) && ! in_array( $field['type'], $controls_in_use ) )
                        ) {
                            $controls_in_use[] = array_key_exists( 'type' , $field ) ? $field['type'] : 'input';
                        }
                    }, $tab['fields'] );
                }
            }
        }
        $this->get_controls( $controls_in_use );
    }
    /**
     * Detenct metabox rendering.
     * @since 1.0.0
     * 
     * @param string $method
     * @param array  $args
     * 
     * @return mixed
     */
    public function __call( $method, $args = [] )
    {
        // Process rendering
        if ( strpos( $method, 'process_' ) === false
            && strpos( $method, 'css_' ) === false
        ) return;
        // Get model key and method id
        $key = explode( '@', str_replace( 'process_', '', $method ) );
        if ( strpos( $method, 'css_' ) !== false )
            $key = explode( '@', str_replace( 'css_', '', $method ) );
        if ( count( $key ) !== 2 ) return;
        $metabox_id = $key[1];
        $key = $key[0];
        // Check if metabox exists
        if ( !array_key_exists( $metabox_id, static::$models[$key]->metaboxes )
            || !array_key_exists( 'tabs', static::$models[$key]->metaboxes[$metabox_id] )
        )
            return;
        if ( strpos( $method, 'css_' ) !== false )
            return $this->metabox_css( static::$models[$key], $metabox_id, $args[0] );
        if ( !empty( $args )
            && !empty( $args[0] )
            && !static::$models[$key]->is_assigned()
        ) {
            static::$models[$key]->from_post( $args[0] );
        }
        // Model handling
        static::$models[$key] = apply_filters( 'metaboxer_model_' . $key, static::$models[$key], $metabox_id );
        // Render
        $this->render( $key, $metabox_id );
    }
    /**
     * Renders output.
     * @since 1.0.0
     * 
     * @param string &$key        Model key.
     * @param string &$metabox_id Metabox ID.
     */
    protected function render( &$key, &$metabox_id )
    {
        // Prepare fields
        $tabs = static::$models[$key]->metaboxes[$metabox_id]['tabs'];
        $default_tab = array_key_exists( 'default_tab', static::$models[$key]->metaboxes[$metabox_id] )
            ? static::$models[$key]->metaboxes[$metabox_id]['default_tab']
            : null;
        $no_value_fields = apply_filters( 'metaboxer_no_value_fields', [] );
        foreach ( $tabs as $tab_id => $tab_data ) {
            if ( !array_key_exists( 'fields', $tab_data ) )
                continue;
            if ( $default_tab === null )
                $default_tab = $tab_id;
            foreach ( $tab_data['fields'] as $field_id => $field ) {
                if ( array_key_exists( 'type', $field ) && in_array( $field['type'], $no_value_fields ) )
                    continue;
                $tab_data['fields'][$field_id]['id'] = $field_id;
                $tab_data['fields'][$field_id]['value'] = null;
                //$tab_data['fields'][$field_id]['value'] = static::$models[$key]->$field_id;
                $tab_data['fields'][$field_id]['_control'] = array_key_exists( 'type', $field ) ? $field['type'] : 'input';
                if ( $tab_data['fields'][$field_id]['value'] === null && array_key_exists( 'default', $field ) ) {
                    $tab_data['fields'][$field_id]['value'] = $field['default'];
                }
                $attributes = [];
                if ( array_key_exists( 'control', $field )
                    && is_array( $field['control'] )
                    && array_key_exists( 'attributes', $field['control'] )
                ) {
                    foreach ( $field['control']['attributes'] as $attr_key => $value) {
                        $attributes[] = esc_attr( $attr_key ) . '="'. esc_attr( $value )  .'"';
                    }
                }
                $tab_data['fields'][$field_id]['html_attributes'] = implode( ' ', $attributes );
            }
            $tabs[$tab_id]['fields'] = apply_filters(
                'metaboxer_model_fields_' . static::$models[$key]->type,
                $tab_data['fields'],
                static::$models[$key],
                $metabox_id,
                $tab_id
            );
        }
        // Render metabox
        MetaboxerAddon::view( 'metaboxer.wrapper-open', [
            'metabox_id' => &$metabox_id,
            'classes' => apply_filters( 'metaboxer_wrapper_class', [
                'metaboxer-wrapper',
            ] ),
        ] );
        if ( count( $tabs ) > 1 ) {
            MetaboxerAddon::view( 'metaboxer.tabs-open', [
                'metabox_id' => &$metabox_id,
                'tabs' => &$tabs,
                'default_tab' => &$default_tab,
            ] );
        }
        foreach ( $tabs as $tab_id => $tab ) {
            MetaboxerAddon::view( 'metaboxer.tab', [
                'metabox_id' => &$metabox_id,
                'tab_id' => &$tab_id,
                'default_tab' => &$default_tab,
                'tab' => &$tab,
            ] );
        }
        // Fields
        if ( count( $tabs ) > 1 ) {
            MetaboxerAddon::view( 'metaboxer.tabs-close' );
        }
        MetaboxerAddon::view( 'metaboxer.wrapper-close' );
        // End render
        /*
        foreach ( static::$controls as $key => $control ) {
            $control->footer();
        }*/
    }
    /**
     * Returns array collection with models available.
     * @since 1.0.0
     * 
     * @return array
     */
    private function get_models()
    {
        return array_filter(
            array_map( function( $class ) {
                $reflector = new ReflectionClass( $class );
                return $reflector->newInstance();
            }, apply_filters( 'metaboxer_models', [] ) ),
            function( $model ) {
                return $model && $model instanceof PostModel;
            }
        );
    }
    /**
     * Loads controls available.
     * @since 1.0.0
     * 
     * @param array &$controls_in_use
     */
    private function get_controls( &$controls_in_use )
    {
        if ( !isset( static::$registered_controls ) ) {
            static::$registered_controls = array_filter(
                array_map(
                    function ( $class ) {
                        $reflector = new ReflectionClass( $class );
                        return $reflector->newInstance();
                    },
                    apply_filters( 'metaboxer_controls', [] )
                ),
                function( $control ) {
                    return $control && $control instanceof Control;
                }
            );
        }
        for ( $i = count( $controls_in_use ) - 1; $i >= 0; --$i ) {
            $in_use = array_filter( static::$controls, function( $control ) use( &$controls_in_use, $i ) {
                return $control->type === $controls_in_use[$i];
            } );
            if ( count( $in_use ) )
                unset( $controls_in_use[$i] );
        }
        static::$controls = array_merge(
            static::$controls,
            array_filter( static::$registered_controls, function( $control ) use( &$controls_in_use ) {
                return in_array( $control->type, $controls_in_use );
            } )
        );
    }
    /**
     * Returns metabox css classes.
     * @since 1.0.0
     * 
     * @param \WPMVC\Addon\Metaboxes\Abstracts\PostModel &$model
     * @param string                                     $metabox_id
     * @param array                                      $classes
     * 
     * @return array
     */
    private function metabox_css( &$model, $metabox_id, $classes )
    {
        // Registered settings models
        $classes[] = 'wpmvc metaboxer';
        if ( array_key_exists( 'class', $model->metaboxes[$metabox_id] ) ) {
            $classes[] = $model->metaboxes[$metabox_id]['class'];
        }
        if ( array_key_exists( 'tabs', $model->metaboxes[$metabox_id] ) ) {
            $classes[] = count( $model->metaboxes[$metabox_id]['tabs'] ) > 1 ? 'has-tabs' : 'no-tabs';
        }
        return $classes;
    }
}