<?php

namespace WPMVC\Addons\Metaboxer;

use WPMVC\Addon;

/**
 * Addon class.
 * Wordpress MVC.
 *
 * @link http://www.wordpress-mvc.com/v1/add-ons/
 * @author 10 Quality <info@10quality.com>
 * @package wpmvc-addon-metaboxer
 * @license MIT
 * @version 1.0.0
 */
class MetaboxerAddon extends Addon
{
    /**
     * Instance.
     * @since 1.0.0
     * 
     * @var \WPMVC\Addons\Customizer\MetaboxerAddon
     */
    protected static $instance;
    /**
     * Function called when plugin or theme starts.
     * @since 1.0.0
     */
    public function init()
    {
        add_filter( 'metaboxer_models', [&$this, 'register_models'], 1 );
    }
    /**
     * Function called when user is on admin dashboard.
     * @since 1.0.0
     */
    public function on_admin()
    {
        if ( !isset( static::$instance ) ) {
            static::$instance = $this;
            add_action( 'admin_enqueue_scripts', [&$this, 'admin_enqueue'], 99 );
            add_action( 'add_meta_boxes', [&$this, 'metaboxes_init'], 5 );
            add_filter( 'metaboxer_controls', [&$this, 'register_controls'], 1 );
            add_filter( 'metaboxer_no_value_fields', function() {
                return [
                    'section_open',
                    'section_close',
                    'section_separator',
                    'callback',
                    'repeater_open',
                    'repeater_close',
                ];
            }, 1 );
            add_filter( 'metaboxer_bool_fields', function() {
                return [
                    'checkbox',
                ];
            }, 1 );
            add_filter( 'metaboxer_control_tr', [&$this, 'control_tr'], 99999, 4 );
            add_filter( 'metaboxer_control_section', [&$this, 'control_section'], 99999, 4 );
        }
    }
    /**
     * Inits
     * @since 1.0.0
     * 
     * @hook add_meta_boxes
     */
    public function metaboxes_init()
    {
        $this->mvc->call( 'MetaboxController@init' );
    }
    /**
     * Registers metaboxer controls.
     * @since 1.0.0
     * 
     * @hook metaboxer_controls
     */
    public function register_controls()
    {
        return $this->mvc->action( 'ConfigController@controls', [] );
    }
    /**
     * Returns control's <tr> attributes.
     * @since 1.0.1
     * 
     * @hook metaboxer_control_tr
     * 
     * @param array                                               $attributes
     * @param array                                               $field
     * @param \WPMVC\Addons\Administrator\Abstracts\SettingsModel $model
     * @param \WPMVC\Addons\Administrator\Helpers\RenderHelper    $helper
     * 
     * @return array|string
     */
    public function control_tr( $attributes, $field, $model, $helper )
    {
        return $this->mvc->action( 'AdminController@control_tr', $attributes, $field, $model, $helper );
    }
    /**
     * Returns section control's attributes.
     * @since 1.0.0
     * 
     * @hook metaboxer_control_section
     * 
     * @param array                                               $attributes
     * @param array                                               $field
     * @param \WPMVC\Addons\Administrator\Abstracts\SettingsModel $model
     * @param \WPMVC\Addons\Administrator\Helpers\RenderHelper    $helper
     * 
     * @return array|string
     */
    public function control_section( $attributes, $field, $model, $helper )
    {
        return $this->mvc->action( 'AdminController@control_section', $attributes, $field, $model, $helper );
    }
    /**
     * Registers/enqueues general admin assets.
     * @since 1.0.0
     * 
     * @hook admin_enqueue_scripts
     */
    public function admin_enqueue()
    {
        wpmvc_register_addon_resource( 'font-awesome' );
    }
    /**
     * Returns settings models.
     * Reads config file and registers settings models.
     * @since 1.0.0
     * 
     * @hook metaboxer_models
     * 
     * @param array $models
     * 
     * @return array
     */
    public function register_models( $models )
    {
        $config = $this->main->config->get( 'metaboxer_models' );
        if ( $config && is_array( $config ) && count( $config ) ) {
            foreach ( $config as $id => $class ) {
                $models[$id] = $class;
            }
        }
        return $models;
    }
    /**
     * Renders an addon view.
     * @since 1.0.0
     * 
     * @param string $key  View key.
     * @param array  $args View arguments.
     */
    public static function view( $key, $args = [] )
    {
        if ( isset( static::$instance ) ) {
            static::$instance->mvc->view->show( $key, $args );
        }
    }
    /**
     * Renders an addon view.
     * @since 1.0.0
     * 
     * @param string $key  View key.
     * @param array  $args View arguments.
     */
    public static function get_view( $key, $args = [] )
    {
        return isset( static::$instance ) ? static::$instance->mvc->view->get( $key, $args ) : '';
    }
}