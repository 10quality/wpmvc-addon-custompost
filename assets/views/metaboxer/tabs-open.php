<?php
/**
 * Metaboxer tabs.
 * WordPress MVC view.
 * 
 * @author 10 Quality <info@10quality.com>
 * @package wpmvc-addon-metaboxer
 * @license MIT
 * @version 1.0.0
 */
?><div class="metaboxer-tabs" role="tabs">
    <div class="tabs-list">
        <?php foreach ( $tabs as $tab_id => $tab ) : ?>
            <div id="toggler-<?php echo esc_attr( $tab_id ) ?>"
                class="tab-toggler<?php if ( $tab_id === $default_tab ) : ?> active<?php endif ?>"
                data-tab="<?php echo esc_attr( $metabox_id ) ?>-<?php echo esc_attr( $tab_id ) ?>"
                role="tab-toggler"
            >
                <?php if ( array_key_exists( 'icon', $tab ) ) : ?><i class="fa <?php echo esc_attr( $tab['icon'] ) ?>" aria-hidden="true"></i><?php endif ?>
                <span class="toggler-label"><?php echo $tab['title'] ?></span>
            </div>
        <?php endforeach ?>
    </div>
    <div class="tabs-content">