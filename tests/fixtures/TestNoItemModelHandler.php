<?php namespace Lovata\Toolbox\Tests\Fixtures;

use Lovata\Toolbox\Classes\Event\ModelHandler;

/**
 * Class TestNoItemModelHandler
 * Handler without Item class, like PropertyValueLinkModelHandler
 * @package Lovata\Toolbox\Tests\Fixtures
 */
class TestNoItemModelHandler extends ModelHandler
{
    /**
     * Get model class name
     * @return string
     */
    protected function getModelClass()
    {
        return TestRegistryElement::class;
    }

    /**
     * Get item class name
     * @return string
     */
    protected function getItemClass()
    {
        return null;
    }
}
