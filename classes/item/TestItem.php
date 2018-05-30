<?php namespace Lovata\Toolbox\Classes\Item;

use Model;
use Lovata\Toolbox\Plugin;
use Lovata\Toolbox\Classes\Collection\TestCollection;

/**
 * Class TestItem
 * @package Lovata\Toolbox\Classes\Item
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @param int $id
 */
class TestItem extends ElementItem
{
    const MODEL_CLASS = Model::class;

    /** @var Model */
    protected $obElement = null;

    public $arExtendResult = [
        'addTitle',
    ];

    public $arRelationList = [
        'test' => [
            'class' => self::class,
            'field' => 'test_id',
        ],
        'test_null' => null,
        'test_class' => [
            'class_fail' => self::class,
            'field'      => 'test_id',
        ],
        'test_field' => [
            'class'      => self::class,
            'field_fail' => 'test_id',
        ],
        'test_exist' => [
            'class'      => self::class.'Test',
            'field' => 'test_id',
        ],
        'test_list' => [
            'class' => TestCollection::class,
            'field' => 'test_list_id',
        ],
        'test_empty_list' => [
            'class' => TestCollection::class,
            'field' => 'test_empty_list_id',
        ],
    ];

    /**
     * Set element object
     */
    protected function setElementObject()
    {
        $obElement = new Model();
        $obElement->id = $this->iElementID;

        $this->obElement = $obElement;
    }

    /**
     * Set brand data from model object
     *
     * @return array
     */
    protected function getElementData()
    {
        $arResult = [
            'id'           => $this->obElement->id,
            'test_id'      => $this->obElement->id + 1,
            'test_list_id' => [$this->obElement->id, $this->obElement->id + 1],
        ];

        return $arResult;
    }

    /**
     * Add title
     */
    protected function addTitle()
    {
        $this->setAttribute('title', 'title'.$this->obElement->id);
    }
}
