<?php

namespace WPMVC\Addons\Metaboxer\Abstracts;

use ReflectionClass;
use WP_Post;
use WPMVC\MVC\Models\PostModel as Model;
use WPMVC\Addons\Metaboxer\Contracts\Enqueueable;
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
class PostModel extends Model implements Enqueueable
{
    use MetaboxTrait, EnqueueTrait;
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
    public function from_post( WP_Post $post )
    {
        $this->attributes = (array)$post;
        $this->load_meta();
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