<?php namespace Lovata\Toolbox\Tests\Unit;

include_once __DIR__.'/../../../toolbox/vendor/autoload.php';
include_once __DIR__.'/../../../../../tests/PluginTestCase.php';

use Lovata\Toolbox\Classes\Collection\CollectionStore;
use PluginTestCase;
use Lovata\Toolbox\Classes\Collection\TestCollection;

/**
 * Class CollectionStoreTest
 * @package Lovata\Toolbox\Tests\Unit
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @mixin \PHPUnit\Framework\Assert
 */
class CollectionStoreTest extends PluginTestCase
{
    /**
     * Test item class
     */
    public function test()
    {
        $obList = TestCollection::make([1]);

        self::assertEquals(null, CollectionStore::instance()->saved('test'));

        CollectionStore::instance()->save('', $obList);
        self::assertEquals(null, CollectionStore::instance()->saved(''));

        CollectionStore::instance()->save('test', $obList);

        $obList->merge([2]);
        $obSavedList = CollectionStore::instance()->saved('test');

        self::assertEquals([1], $obSavedList->getIDList());

        $obSavedList->clear();

        self::assertEquals([1,2], $obList->getIDList());
    }
}