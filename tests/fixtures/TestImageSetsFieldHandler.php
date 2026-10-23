<?php namespace Lovata\Toolbox\Tests\Fixtures;

use Lovata\Toolbox\Classes\Event\AbstractImageSetsFieldHandler;
use Lovata\Toolbox\Tests\Fixtures\Controllers\TestImageElements;

/**
 * Class TestImageSetsFieldHandler
 * @package Lovata\Toolbox\Tests\Fixtures
 */
class TestImageSetsFieldHandler extends AbstractImageSetsFieldHandler
{
    /**
     * Get tab name for image sets field
     * @return string
     */
    protected function getTabName() : string
    {
        return 'lovata.toolbox::lang.tab.images';
    }

    /**
     * Get model class name
     * @return string
     */
    protected function getModelClass() : string
    {
        return TestImageElement::class;
    }

    /**
     * Get controller class name
     * @return string
     */
    protected function getControllerClass() : string
    {
        return TestImageElements::class;
    }
}
