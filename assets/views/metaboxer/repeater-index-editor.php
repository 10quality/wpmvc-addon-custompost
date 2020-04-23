<?php
/**
 * Repeater index editor.
 * WordPress MVC view.
 * 
 * @author 10 Quality <info@10quality.com>
 * @package wpmvc-addon-metaboxer
 * @license MIT
 * @version 1.0.0
 */
?>
<script id="repeater-index-editor" type="text/template">
    <div class="index-editor">
        <label for="index-editor"><span class="label"><?php _e( 'Index', 'wpmvc-addon-resources' ) ?></span></label>
        <input id="index-editor" type="text"/>
        <button role="index-cancel"><?php _e( 'Cancel', 'wpmvc-addon-resources' ) ?></button>
        <button role="index-update"><?php _e( 'Update' ) ?></button>
    </div>
</script>