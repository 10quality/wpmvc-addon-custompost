<?php
/**
 * Editor control.
 * WordPress MVC view.
 * 
 * @author 10 Quality <info@10quality.com>
 * @package wpmvc-addon-metaboxer
 * @license MIT
 * @version 1.0.0
 */
$settings = isset( $control ) ? $control : [];
$settings['textarea_name'] = isset( $name ) ? $name : $id;
wp_editor( stripslashes( $value ), $id, $settings );