<?php

namespace WPMVC\Addons\Metaboxer\Controllers;

use WPMVC\MVC\Controller;
use WPMVC\Addons\Metaboxer\Controls\InputControl;
/*
use WPMVC\Addons\Metaboxer\Controls\CheckboxControl;
use WPMVC\Addons\Metaboxer\Controls\SelectControl;
use WPMVC\Addons\Metaboxer\Controls\RadioControl;
use WPMVC\Addons\Metaboxer\Controls\PagesControl;
use WPMVC\Addons\Metaboxer\Controls\ChooseControl;
use WPMVC\Addons\Metaboxer\Controls\DatepickerControl;
use WPMVC\Addons\Metaboxer\Controls\MediaControl;
use WPMVC\Addons\Metaboxer\Controls\TextareaControl;
use WPMVC\Addons\Metaboxer\Controls\EditorControl;
use WPMVC\Addons\Metaboxer\Controls\Select2Control;
use WPMVC\Addons\Metaboxer\Controls\ColorpickerControl;
use WPMVC\Addons\Metaboxer\Controls\SwitchControl;
use WPMVC\Addons\Metaboxer\Controls\DatetimepickerControl;
*/

/**
 * Add-on configuration hooks.
 *
 * @author 10 Quality <info@10quality.com>
 * @package wpmvc-addon-metaboxer
 * @license MIT
 * @version 1.0.0
 */
class ConfigController extends Controller
{
    /**
     * Registers Metaboxer control classes.
     * @since 1.0.0
     * 
     * @hook metaboxer_controls
     * 
     * @param array $controls
     * 
     * @return array
     */
    public function controls( $classes )
    {
        $classes[InputControl::TYPE] = 'WPMVC\Addons\Metaboxer\Controls\InputControl';
        /*
        $classes[CheckboxControl::TYPE] = 'WPMVC\Addons\Metaboxer\Controls\CheckboxControl';
        $classes[SelectControl::TYPE] = 'WPMVC\Addons\Metaboxer\Controls\SelectControl';
        $classes[RadioControl::TYPE] = 'WPMVC\Addons\Metaboxer\Controls\RadioControl';
        $classes[PagesControl::TYPE] = 'WPMVC\Addons\Metaboxer\Controls\PagesControl';
        $classes[ChooseControl::TYPE] = 'WPMVC\Addons\Metaboxer\Controls\ChooseControl';
        $classes[DatepickerControl::TYPE] = 'WPMVC\Addons\Metaboxer\Controls\DatepickerControl';
        $classes[MediaControl::TYPE] = 'WPMVC\Addons\Metaboxer\Controls\MediaControl';
        $classes[TextareaControl::TYPE] = 'WPMVC\Addons\Metaboxer\Controls\TextareaControl';
        $classes[EditorControl::TYPE] = 'WPMVC\Addons\Metaboxer\Controls\EditorControl';
        $classes[Select2Control::TYPE] = 'WPMVC\Addons\Metaboxer\Controls\Select2Control';
        $classes[ColorpickerControl::TYPE] = 'WPMVC\Addons\Metaboxer\Controls\ColorpickerControl';
        $classes[SwitchControl::TYPE] = 'WPMVC\Addons\Metaboxer\Controls\SwitchControl';
        $classes[DatetimepickerControl::TYPE] = 'WPMVC\Addons\Metaboxer\Controls\DatetimepickerControl';
        */
        return $classes;
    }
}