<?php namespace Lovata\Toolbox\Tests\Unit;

include_once __DIR__.'/../../../toolbox/vendor/autoload.php';
include_once __DIR__.'/../../../../../tests/PluginTestCase.php';

use PluginTestCase;
use Lovata\Toolbox\Classes\Item\TestItem;
use Lovata\Toolbox\Classes\Collection\TestCollection;

/**
 * Class ItemTest
 * @package Lovata\Toolbox\Tests\Unit
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @mixin \PHPUnit\Framework\Assert
 */
class ItemTest extends PluginTestCase
{
    /**
     * Test item class
     */
    public function testItem()
    {
        $obItem = TestItem::make(1);
        self::assertEquals(1, $obItem->id, 'Error in "make" item method');
        self::assertEquals(false, empty($obItem->id), 'Error in "__isset" item method');

        $obItem = TestItem::makeNoCache(1);
        self::assertEquals(1, $obItem->id, 'Error in "makeNoCache" item method');

        $arItemData = [
            'id'           => 1,
            'test_id'      => 2,
            'title'        => 'title1',
            'test_list_id' => [1,2],
        ];

        self::assertEquals($arItemData, $obItem->toArray(), 'Error in "toArray" item method');

        self::assertInstanceOf(\Model::class, $obItem->getObject(), 'Error in "getObject" item method');
    }

    /**
     * Test item relations
     */
    public function testItemRelations()
    {
        $sMessage = 'Error in relation methods';
        $obItem = TestItem::make(1);
        
        $obRelationItem = $obItem->test;
        self::assertInstanceOf(TestItem::class, $obRelationItem, $sMessage);
        self::assertEquals(2, $obRelationItem->id, $sMessage);
        
        $obRelationItem = $obItem->test;
        self::assertInstanceOf(TestItem::class, $obRelationItem, $sMessage);
        self::assertEquals(2, $obRelationItem->id, $sMessage);

        $obRelationItem = $obItem->test_null;
        self::assertEquals(null, $obRelationItem, $sMessage);

        $obRelationItem = $obItem->test_class;
        self::assertEquals(null, $obRelationItem, $sMessage);

        $obRelationItem = $obItem->test_field;
        self::assertEquals(null, $obRelationItem, $sMessage);

        $obRelationItem = $obItem->test_exist;
        self::assertEquals(null, $obRelationItem, $sMessage);
        
        /** @var TestCollection $obRelationList */
        $obRelationList = $obItem->test_list;
        self::assertInstanceOf(TestCollection::class, $obRelationList, $sMessage);
        self::assertEquals(2, $obRelationList->count(), $sMessage);
        
        /** @var TestCollection $obRelationList */
        $obRelationList = $obItem->test_empty_list;
        self::assertInstanceOf(TestCollection::class, $obRelationList, $sMessage);
        self::assertEquals(0, $obRelationList->count(), $sMessage);
        self::assertEquals([], $obRelationList->getIDList(), $sMessage);
    }
}