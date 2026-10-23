<?php namespace Lovata\Toolbox\Tests\Fixtures;

use Lovata\Toolbox\Classes\Event\ModelHandler;

/**
 * Class TestKeyIdentifierModelHandler
 * Handler with identifier field "key", like SeoTemplateModelHandler
 * @package Lovata\Toolbox\Tests\Fixtures
 */
class TestKeyIdentifierModelHandler extends ModelHandler
{
    protected $sIdentifierField = 'key';

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
        return TestImageElementItem::class;
    }
}
