<?php

namespace WPMVC\Addons\Metaboxer\Controllers;

use ReflectionClass;
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
     * Model buffer.
     * @since 1.0.0
     * @var array
     */
    protected static $buffer = [];
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
     * Inits by registering metabox models and configurations.
     * @since 1.0.0
     * 
     * @hook add_meta_boxes
     */
    public function init()
    {
        // Registered settings models
        $models = $this->get_models();
        foreach ( $models as $key => $model ) {
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
            }
        }
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
        // Get model
        if ( !array_key_exists( $key, static::$buffer ) ) {
            // Prepare
            $models = $this->get_models();
            if ( !array_key_exists( $key, $models ) ) return;
            static::$buffer[$key] = apply_filters( 'administrator_preload_model_' . $key, $models[$key] );
        }
        // Check if metabox exists
        if ( !array_key_exists( $metabox_id, static::$buffer[$key]->metaboxes )
            || !array_key_exists( 'tabs', static::$buffer[$key]->metaboxes[$metabox_id] )
        )
            return;
        if ( strpos( $method, 'css_' ) !== false )
            return $this->metabox_css( static::$buffer[$key], $metabox_id, $args[0] );
        if ( !empty( $args )
            && !empty( $args[0] )
            && !static::$buffer[$key]->is_assigned()
        ) {
            static::$buffer[$key]->from_post( $args[0] );
        }
        // Obtain all registered controls
        $controls_in_use = [];
        foreach ( static::$buffer[$key]->metaboxes[$metabox_id]['tabs'] as $tab ) {
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
        $this->get_controls( $controls_in_use );
        // Model handling
        static::$buffer[$key] = apply_filters( 'metaboxer_model_' . $key, static::$buffer[$key], $metabox_id );
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
        // @todo render
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
        $classes[] = 'wpmvc';
        if ( array_key_exists( 'class', $model->metaboxes[$metabox_id] ) ) {
            $classes[] = $model->metaboxes[$metabox_id]['class'];
        }
        if ( array_key_exists( 'tabs', $model->metaboxes[$metabox_id] ) ) {
            $classes[] = count( $model->metaboxes[$metabox_id]['tabs'] ) > 1 ? 'has-tabs' : 'no-tabs';
        }
        return $classes;
    }
}