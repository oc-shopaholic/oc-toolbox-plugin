<?php namespace Lovata\Toolbox\Tests\Fixtures;

use Model;

/**
 * Class TestRegistryElement
 * Test model for handlers, which must not be added to "model => Item" registry
 * @package Lovata\Toolbox\Tests\Fixtures
 */
class TestRegistryElement extends Model
{
    public $table = 'lovata_toolbox_test_image_elements';
}
