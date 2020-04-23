<?php

namespace ECommerce\Middlewares;

/**
 * WP REST API middleware class.
 * 
 * @author Bellwether Coffee <https://www.bellwethercoffee.com/>
 * @package ecommerce
 * @version 1.0.0
 */
class RestMiddleware
{
    /**
     * Returns flag indicating if value is a numeric value or not.
     * - validate_callback
     * @since 1.0.0
     * 
     * @param string $value
     * 
     * @return bool
     */
    public static function validate_numeric( $value )
    {
        return is_numeric( $value );
    }
    /**
     * Returns flag indicating if value is a numeric value or not.
     * - validate_callback
     * @since 1.0.0
     * 
     * @param string $value
     * 
     * @return bool
     */
    public static function validate_int( $value )
    {
        return is_numeric( $value ) && is_integer( $value );
    }
    /**
     * Validates order by rest parameters.
     * - validate_callback
     * @since 1.0.0
     * 
     * @param string|array $value
     * 
     * @return bool
     */
    public static function validate_order_by( $value )
    {
        $value = is_array( $value ) ? $value : explode( ' ', $value );
        if ( isset( $value[1] ) )
            $value[1] = trim( strtoupper( $value[1] ) );
        return !empty( $value[0] )
            && ( !isset( $value[1] ) || ( $value[1] === 'ASC' || $value[1] === 'DESC' ) );
    }
    /**
     * Sanitizes order by rest parameters.
     * - sanitize_callback
     * @since 1.0.0
     * 
     * @param string|array $value
     * 
     * @return array
     */
    public static function sanitize_order_by( $value )
    {
        $value = is_array( $value ) ? $value : explode( ' ', $value );
        if ( isset( $value[1] ) )
            $value[1] = trim( strtoupper( $value[1] ) );
        return $value;
    }
}