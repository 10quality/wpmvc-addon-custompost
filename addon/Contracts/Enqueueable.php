<?php

namespace WPMVC\Addons\Metaboxer\Contracts;

/**
 * Interface for objects that enqueue styles and scripts.
 *
 * @author 10 Quality <info@10quality.com>
 * @package wpmvc-addon-metaboxer
 * @license MIT
 * @version 1.0.0
 */
interface Enqueueable
{
    /**
     * Enqueues styles and scripts especific to the control.
     * @since 1.0.0
     */
    function enqueue();
}