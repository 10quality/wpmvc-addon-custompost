<?php

use WPMVC\Addons\PHPUnit\TestCase;
use WPMVC\Addons\Metaboxer\MetaboxerAddon;

/**
 * Test addon class.
 * 
 * @author 10 Quality <info@10quality.com>
 * @package wpmvc-addon-customizer
 * @license MIT
 * @version 1.0.5
 */
class MetaboxerAddonTest extends TestCase
{
    /**
     * Tear down.
     * @since 1.0.5
     */
    public function tearDown(): void
    {
        wpmvc_addon_phpunit_reset();
    }
    /**
     * Test init.
     * @since 1.0.5
     * @group addon
     */
    public function testInit()
    {
        // Prepare
        $bridge = $this->getBridgeMock();
        $addon = new MetaboxerAddon($bridge);
        // Run
        $addon->init();
        // Assertc
        $this->assertAddedFilter( 'metaboxer_models' );
        $this->assertAddedFilter( 'metaboxer_no_value_fields' );
        $this->assertAddedFilter( 'metaboxer_bool_fields' );
    }
    /**
     * Test init.
     * @since 1.0.5
     * @group addon
     */
    public function testOnAdmin()
    {
        // Prepare
        $bridge = $this->getBridgeMock();
        $addon = new MetaboxerAddon($bridge);
        // Run
        $addon->on_admin();
        // Assertc
        $this->assertAddedAction( 'admin_enqueue_scripts' );
        $this->assertAddedAction( 'add_meta_boxes' );
        $this->assertAddedAction( 'admin_footer' );
        $this->assertAddedAction( 'save_post' );
        $this->assertAddedFilter( 'metaboxer_controls' );
        $this->assertAddedFilter( 'metaboxer_control_tr' );
        $this->assertAddedFilter( 'metaboxer_control_section' );
    }
    /**
     * Test init.
     * @since 1.0.5
     * @group addon
     */
    public function testRegisterControls()
    {
        // Prepare
        $bridge = $this->getBridgeMock();
        $addon = new MetaboxerAddon($bridge);
        // Run
        $controls = $addon->register_controls();
        // Assertc
        $this->assertNotEmpty( $controls );
        $this->assertCount( 14 , $controls );
    }
}