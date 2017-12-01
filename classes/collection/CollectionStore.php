<?php namespace Lovata\Toolbox\Classes\Collection;

use October\Rain\Support\Traits\Singleton;

/**
 * Class CollectionStore
 * @package Lovata\Toolbox\Classes\Collection
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class CollectionStore
{
    use Singleton;

    /** @var array */
    protected $arStore = [];

    /**
     * Save item collection
     * @see \Lovata\Toolbox\Tests\Unit\CollectionTest::testSaveMethod()
     * @param string            $sKey
     * @param ElementCollection $obCollection
     */
    public function save($sKey, $obCollection)
    {
        if (empty($sKey)) {
            return;
        }

        $this->arStore[$sKey] = $obCollection;
    }

    /**
     * Get saved element collection
     * @see \Lovata\Toolbox\Tests\Unit\CollectionTest::testSaveMethod()
     * @param string $sKey
     * @return ElementCollection
     */
    public function get($sKey)
    {
        if (empty($sKey) || empty($this->arStore) || !isset($this->arStore[$sKey])) {
            return null;
        }

        return $this->arStore[$sKey];
    }
}
