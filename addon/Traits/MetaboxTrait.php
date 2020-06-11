<?php

namespace WPMVC\Addons\Metaboxer\Traits;

/**
 * Trait to be used to add metabox related properties
 * and methods to a WordPress MVC model.
 *
 * @author 10 Quality <info@10quality.com>
 * @package wpmvc-addon-metaboxer
 * @license M
 * @version 1.0.2
 */
trait MetaboxTrait
{
    /**
     * Metaboxes, tabs, settings and fields definition.
     * @since 1.0.0
     * @var array
     */
    protected $metaboxes = [];
    /**
     * Grouped model data. Non individual metabox
     * @since 1.0.0
     * @var array
     */
    protected $_model = [];
    /**
     * Getter function.
     * @since 1.0.0
     *
     * @param string $property
     *
     * @return mixed
     */
    public function &__get( $property )
    {
        if ( array_key_exists( $property, $this->_model ) ) {
            return $this->_model[$property];
        } elseif ( $property !== '_wpmvc_model' && array_key_exists( $property, $this->meta ) ) {
            return $this->meta[$property];
        } elseif ( property_exists( $this, $property ) ) {
            return $this->$property;
        }
        return parent::__get( $property );
    }
    /**
     * Getter function.
     * @since 1.0.0
     *
     * @param string $property
     *
     * @return mixed
     */
    public function __set( $property, $value )
    {
        if ( array_key_exists( $property, $this->_model ) ) {
            $this->_model[$property] = $value;
        } elseif ( $property !== '_wpmvc_model' && array_key_exists( $property, $this->meta ) ) {
            $this->meta[$property] = $value;
        } else {
            $set = false;
            foreach ( $this->metaboxes as $metabox ) {
                if ( !array_key_exists( 'tabs', $metabox ) )
                    continue;
                foreach ( $metabox['tabs'] as $tab ) {
                    if ( !array_key_exists( 'fields', $tab ) )
                        continue;
                    foreach ( $tab['fields'] as $field_id => $field ) {
                        if ( $property !== $field_id )
                            continue;
                        if ( array_key_exists( 'type', $field )
                            && in_array( $field['type'], apply_filters( 'metaboxer_no_value_fields', [] ) )
                        ) {
                            $set = true;
                            continue;
                        }
                        if ( array_key_exists( 'storage', $field ) && $field['storage'] === 'model' ) {
                            $this->set_prop( $field_id, $value );
                        } else {
                            $this->set_meta( $field_id, $value );
                        }
                        $set = true;
                    }
                }
            }
            if ( !$set )
                parent::__set( $property, $value );
        }
    }
    /**
     * Returns flag indicating if settings object is empty.
     * Meaning that it has no fields to display.
     * @since 1.0.0
     *
     * @return bool
     */
    public function is_empty()
    {
        return empty( $this->metaboxes );
    }
    /**
     * Returns flag indicating model has been loaded or assigned with an ID.
     * @since 1.0.0
     *
     * @return bool
     */
    public function is_assigned()
    {
        return !empty( $this->attributes['ID'] );
    }
    /**
     * Overrides parent load meta to decode model data.
     * @since 1.0.0
     */
    public function load_meta()
    {
        parent::load_meta();
        if ( $this->has_meta( '_wpmvc_model' ) ) {
            $this->_model = is_object( $this->meta['_wpmvc_model'] )
                ? (array)$this->meta['_wpmvc_model']
                : ( !is_array( $this->meta['_wpmvc_model'] )
                    ? (array)json_decode( $this->meta['_wpmvc_model'] )
                    : $this->meta['_wpmvc_model']
                );
            if ( empty( $this->_model ) || !is_array( $this->_model ) )
                $this->_model = [];
            // For array values
            foreach ( $this->_model as $key => $value ) {
                if ( is_object( $value ) )
                    $this->_model[$key] = (array)$value;
            }
        }
    }
    /**
     * Sets value inside meta data,
     * @since 1.0.0
     * 
     * @param string $key
     * @param mixed  $value
     */
    public function set_prop( $key, $value )
    {
        $this->_model[$key] = $value;
    }
    /**
     * Saves all meta values and model properties.
     * @since 1.0.0
     */
    public function save_meta_all()
    {
        update_post_meta( $this->ID, '_wpmvc_model', json_encode( $this->_model ) );
        foreach ( $this->metaboxes as $metabox ) {
            if ( !array_key_exists( 'tabs', $metabox ) )
                continue;
            foreach ( $metabox['tabs'] as $tab ) {
                if ( !array_key_exists( 'fields', $tab ) )
                    continue;
                foreach ( $tab['fields'] as $field_id => $field ) {
                    if ( ( array_key_exists( 'storage', $field ) && $field['storage'] === 'model' )
                        || ( array_key_exists( 'type', $field )
                            && in_array( $field['type'], apply_filters( 'metaboxer_no_value_fields', [] ) )
                        )
                    ) {
                        continue;
                    }
                    update_post_meta( $this->ID, $field_id, $this->$field_id );
                }
            }
        }
        parent::save_meta_all();
    }
}