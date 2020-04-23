<?php
/**
 * Metaboxer tab.
 * WordPress MVC view.
 * 
 * @author 10 Quality <info@10quality.com>
 * @package wpmvc-addon-metaboxer
 * @license MIT
 * @version 1.0.0
 */
?><tab id="<?php echo esc_attr( $metabox_id ) ?>-<?php echo esc_attr( $tab_id ) ?>"
    class="tab-content<?php if ( $tab_id === $default_tab ) : ?> active<?php endif ?>"
    role="tab"
>
    <?php if ( array_key_exists( 'description', $tab ) ) : ?>
        <p class="description"><?php echo esc_attr( $tab['description'] ) ?></p><hr>
    <?php endif ?>
</tab>