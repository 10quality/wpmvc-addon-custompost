<?php

namespace WPMVC\Addons\Metaboxer\Abstracts;

use ReflectionClass;
use WPMVC\MVC\Traits\FindTrait;
use WPMVC\MVC\Models\PostModel as Model;
use WPMVC\Addons\Metaboxer\Contracts\Enqueueable;
use WPMVC\Addons\Metaboxer\Contracts\Modelable;
use WPMVC\Addons\Metaboxer\Traits\MetaboxTrait;
use WPMVC\Addons\Metaboxer\Traits\EnqueueTrait;

/**
 * Metaboxer post model.
 * This model has the definition of all the fields to display,
 * tabs, admin menus and other.
 * While not a PHP abstract class, it is treated as one.
 *
 * @author 10 Quality <info@10quality.com>
 * @package wpmvc-addon-metaboxer
 * @license MIT
 * @version 1.0.0
 */
class PostModel extends Model implements Enqueueable, Modelable
{
    use FindTrait, MetaboxTrait, EnqueueTrait;
    /**
     * Tab ID that indicates to the rendering process
     * not to use tabs, as metabox fields will be displayed in one with no tabs.
     * @since 1.0.0
     * @var string
     */
    const NO_TAB = '__NOTAB';
    /**
     * Default constructor.
     * @since 1.0.0
     * 
     * @param string $id Model ID.
     */
    public function __construct( $id = null )
    {
        // Forces adds settings field
        $this->init();
        // Construct
        parent::__construct( $id );
    }
    /**
     * Loads model from a given post.
     * @since 1.0.0
     * 
     * @param \WP_Post $post
     */
    public function from_post( $post )
    {
        $this->attributes = (array)$post;
        $this->load_meta();
    }
    /**
     * Renders additional HTML code at the footer/end of the form.
     * @since 1.0.0
     */
    public function footer()
    {
        // TODO: Based on control.
    }
    /**
     * Inits model.
     * @since 1.0.0
     */
    protected function init()
    {
        // TODO on final class
    }
}