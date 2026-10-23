<?php namespace Lovata\Toolbox\Tests\Fixtures;

use Lovata\Toolbox\Classes\Event\ModelHandler;

/**
 * Class TestImageElementModelHandler
 * @package Lovata\Toolbox\Tests\Fixtures
 */
class TestImageElementModelHandler extends ModelHandler
{
    /**
     * Get model class name
     * @return string
     */
    protected function getModelClass()
    {
        return TestImageElement::class;
    }

    /**
     * Get item class name
     * @return string
     */
    protected function getItemClass()
    {
        return TestImageElementItem::class;
    }
}
