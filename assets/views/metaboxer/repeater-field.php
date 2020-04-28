<?php
/**
 * Repeater field.
 * WordPress MVC view.
 * 
 * @author 10 Quality <info@10quality.com>
 * @package wpmvc-addon-metaboxer
 * @license MIT
 * @version 1.0.0
 */
?>
<tr id="tr-<?php echo esc_attr( $repeater_id ) ?>-<?php echo esc_attr( $field_id ) ?>-<?php echo esc_attr( $key ) ?>"
    <?php echo apply_filters( 'metaboxer_control_tr', [], $field, $model, $helper ) ?>
>
    <?php if ( !array_key_exists( 'show_title', $field ) || $field['show_title'] === true ) : ?>
        <th><?php echo array_key_exists( 'title', $field ) ? $field['title'] : $field_id ?></th>
    <?php endif ?>
    <td class="type-<?php echo esc_attr( array_key_exists( 'type', $field ) ? $field['type'] : 'input' ) ?>">
        <?php if ( array_key_exists( $field['_control'], $controls ) ) : ?>
            <?php $controls[$field['_control']]->render( $field ) ?>
        <?php endif ?>
        <?php if ( array_key_exists( 'description', $field ) && !empty( $field['description'] ) ) : ?>
            <br><p class="description"><?php echo $field['description'] ?></p>
        <?php endif ?>
    </td>
    <td role="repeater-actions"></td>
</tr>