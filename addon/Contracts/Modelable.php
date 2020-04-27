<?php

namespace WPMVC\Addons\Metaboxer\Contracts;

/**
 * Interface for objects that will render output.
 *
 * @author 10 Quality <info@10quality.com>
 * @package wpmvc-addon-metaboxer
 * @license MIT
 * @version 1.0.0
 */
interface Modelable
{
    /**
     * Sets meta.
     * @since 1.0.0
     * @param string $key
     * @param mixed  $value
     */
    function set_meta( $key, $value );
    /**
     * Sets model property.
     * @since 1.0.0
     * @param string $key
     * @param mixed  $value
     */
    function set_prop( $key, $value );
}