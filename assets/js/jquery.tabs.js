/*!
 * WordPress MVC resources. Tab manager script.
 * jQuery script.
 * @author 10 Quality <info@10quality.com>
 * @package wpmvc-addon-resources
 * @license MIT
 * @version 1.0.0
 */
( function( $ ) {
    /**
     * jQuery plugin.
     * @since 1.0.0
     */
    $.fn.tab_manager = function()
    {
        var self = this;
        self.methods = {
            /**
             * Init tab manager.
             * @since 1.0.0
             */
            ready: function()
            {
                $( document ).on( 'click', '*[role="tab-toggler"]', self.methods.on_click );
            },
            /**
             * Handlers toggler switch event.
             * @since 1.0.0
             */
            on_click: function( event )
            {
                event.preventDefault();
                var $wrapper = $( this ).closest( '*[role="tabs"]' );
                var tab = '#' + $( this ).data( 'tab' );
                // Disable current selected
                $wrapper.find( '.active[role="tab-toggler"]' ).removeClass( 'active' );
                $wrapper.find( '.active[role="tab"]' ).removeClass( 'active' );
                $wrapper.trigger( 'tab:deselected' );
                $( this ).addClass( 'active' );
                $wrapper.find( tab ).addClass( 'active' );
                $wrapper.trigger( 'tab:selected', [tab] );
            },
        };
        $( document ).ready( self.methods.ready );
    };
    /**
     * Assign tab manager to DOM.
     * @since 1.0.0
     */
    $( document ).tab_manager();
} )( jQuery );