<?php namespace Lovata\Toolbox\Tests\Fixtures;

use Lovata\Toolbox\Classes\Item\ElementItem;

/**
 * Class TestImageElementItem
 * @package Lovata\Toolbox\Tests\Fixtures
 *
 * @property int    $id
 * @property string $name
 * @property array  $image_sets
 */
class TestImageElementItem extends ElementItem
{
    const MODEL_CLASS = TestImageElement::class;

    public static $arQueryWith = ['image_sets.images', 'image_sets.profile'];

    /** @var TestImageElement */
    protected $obElement = null;
}
