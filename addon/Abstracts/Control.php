<?php

namespace WPMVC\Addons\Metaboxer\Abstracts;

use WPMVC\Addons\Metaboxer\MetaboxerAddon;
use WPMVC\Addons\Metaboxer\Contracts\Enqueueable;
use WPMVC\Addons\Metaboxer\Contracts\Renderable;
/**
 * Administrator control.
 * Base abstract class.
 *
 * @author 10 Quality <info@10quality.com>
 * @package wpmvc-addon-metaboxer
 * @license MIT
 * @version 1.0.0
 */
abstract class Control implements Enqueueable, Renderable
{
    /**
     * The control type, acts like ID identifier.
     * @since 1.0.0
     * @var string
     */
    protected $type = 'input';
    /**
     * View key to use if render method is not present.
     * View will be render as fallback.
     * @since 1.0.0
     * @var string
     */
    protected $view = 'metaboxer.controls.input';
    /**
     * Getter function.
     * @since 1.0.0
     *
     * @param string $property
     *
     * @return mixed
     */
    public function __get( $property )
    {
        return property_exists( $this, $property ) ? $this->$property : null;
    }
    /**
     * Enqueues styles and scripts especific to the control.
     * @since 1.0.0
     */
    public function enqueue()
    {
        // TODO: Based on control.
    }
    /**
     * Renders output.
     * @since 1.0.0
     * @param array $args
     */
    public function render( $args = [] )
    {
        MetaboxerAddon::view( $this->view, $args );
    }
    /**
     * Renders additional HTML code at the footer/end of the form.
     * @since 1.0.0
     */
    public function footer()
    {
        // TODO: Based on control.
    }
}