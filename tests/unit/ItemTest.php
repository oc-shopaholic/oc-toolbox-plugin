<?php namespace Lovata\Toolbox\Tests\Unit;

include_once __DIR__.'/../../../toolbox/vendor/autoload.php';
include_once __DIR__.'/../../../../../tests/PluginTestCase.php';

use Lovata\Toolbox\Classes\Item\TestItem;
use PluginTestCase;

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
            'id' => 1
        ];

        self::assertEquals($arItemData, $obItem->toArray(), 'Error in "toArray" item method');

        self::assertInstanceOf(\Model::class, $obItem->getObject(), 'Error in "getObject" item method');
    }
}