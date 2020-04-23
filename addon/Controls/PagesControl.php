<?php

namespace WPMVC\Addons\Metaboxer\Controls;

use WPMVC\Addons\Metaboxer\Abstracts\Control;
/**
 * HTML <select{pages}> control.
 *
 * @author 10 Quality <info@10quality.com>
 * @package wpmvc-addon-metaboxer
 * @license MIT
 * @version 1.0.0
 */
class PagesControl extends Control
{
    /**
     * Control type.
     * @since 1.0.0
     * @var string
     */
    const TYPE = 'pages';
    /**
     * The control type, acts like ID identifier.
     * @since 1.0.0
     * @var string
     */
    protected $type = self::TYPE;
    /**
     * View key to use if render method is not present.
     * View will be render as fallback.
     * @since 1.0.0
     * @var string
     */
    protected $view = 'metaboxer.controls.pages';
}