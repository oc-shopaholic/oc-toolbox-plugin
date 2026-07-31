<?php namespace Lovata\Toolbox\Tests\Unit;

include_once __DIR__.'/../../../toolbox/vendor/autoload.php';
include_once __DIR__.'/../../../../../tests/PluginTestCase.php';

use PluginTestCase;
use Lovata\Toolbox\Classes\Store\AbstractStoreWithoutParam;

/**
 * Class EmptyListTestStore
 * @package Lovata\Toolbox\Tests\Unit
 *
 * Test double: store whose ID list is always empty, counts DB hits.
 */
class EmptyListTestStore extends AbstractStoreWithoutParam
{
    protected static $instance;

    /** @var int */
    public static $iDBCallCount = 0;

    /**
     * Get ID list from database
     * @return array
     */
    protected function getIDListFromDB() : array
    {
        static::$iDBCallCount++;

        return [];
    }
}

/**
 * Class StoreEmptyListCacheTest
 * @package Lovata\Toolbox\Tests\Unit
 *
 * @mixin \PHPUnit\Framework\Assert
 */
class StoreEmptyListCacheTest extends PluginTestCase
{
    /**
     * Empty ID list must be served from persistent cache, not re-queried from DB
     */
    public function testEmptyIDListIsServedFromCache()
    {
        EmptyListTestStore::$iDBCallCount = 0;

        self::assertEquals([], EmptyListTestStore::instance()->get());
        self::assertEquals(1, EmptyListTestStore::$iDBCallCount);

        //New store instance: in-memory memoization is gone, persistent cache must hit
        EmptyListTestStore::forgetInstance();

        self::assertEquals([], EmptyListTestStore::instance()->get());
        self::assertEquals(1, EmptyListTestStore::$iDBCallCount);

        //Explicit clear must invalidate the cached empty list
        EmptyListTestStore::instance()->clear();

        self::assertEquals([], EmptyListTestStore::instance()->get());
        self::assertEquals(2, EmptyListTestStore::$iDBCallCount);
    }
}
