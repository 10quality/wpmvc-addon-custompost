<?php

namespace WPMVC\Addons\Metaboxer\Traits;

/**
 * Trait to be used to add metabox related properties
 * and methods to a WordPress MVC model.
 *
 * @author 10 Quality <info@10quality.com>
 * @package wpmvc-addon-metaboxer
 * @license MIT
 * @version 1.0.0
 */
trait MetaboxTrait
{
    /**
     * Tab ID that indicates to the rendering process
     * not to use tabs, as metabox fields will be displayed in one with no tabs.
     * @since 1.0.0
     * @var string
     */
    const NO_TAB = '__NOTAB';
    /**
     * Metaboxes, tabs, settings and fields definition.
     * @since 1.0.0
     * @var array
     */
    protected $metaboxes = [];
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
        if ( property_exists( $this, $property ) ) {
            return $this->$property;
        }
        return parent::__get( $property );
    }
    /**
     * Returns flag indicating if settings object is empty.
     * Meaning that it has no fields to display.
     * @since 1.0.0
     * *
     * @return bool
     */
    public function is_empty()
    {
        return empty( $this->metaboxes );
    }
}