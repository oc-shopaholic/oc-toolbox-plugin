<?php namespace Lovata\Toolbox\Classes\Api\Event;

use Lovata\Toolbox\Classes\Api\Collection\AbstractCollectionType;

/**
 * Class AbstractExtendCollectionType
 * @package Lovata\Toolbox\Classes\Api\Event
 * @author  Igor Tverdokhleb, i.tverdokhleb@lovata.com, LOVATA Group
 */
abstract class AbstractExtendCollectionType extends AbstractApiObjectTypeHandler
{
    /** @var array */
    private $arMethodData = [];

    /** @var string */
    private $sFilterInputTypeClass = '';

    /** @var string */
    private $sSortEnumInputTypeClass = '';

    /** @var null|string */
    private $sSortMethodName = null;


    abstract public function subscribe();

    /**
     * @inheritDoc
     */
    protected function validateApiTypeClass()
    {
        if (is_a($this->sApiTypeClass, AbstractCollectionType::class, true)) {
            return;
        }

        throw new \Exception('Extended class must be a subclass off ' . AbstractCollectionType::class);
    }

    /**
     * Set filter input type
     * @param string $sFilterInputTypeClass
     * @return void
     */
    protected function setFilterInputType(string $sFilterInputTypeClass)
    {
        $this->sFilterInputTypeClass = $sFilterInputTypeClass;
    }

    /**
     * Set sort enum input type
     * @param string $sSortEnumInputTypeClass
     * @return void
     */
    protected function setSortEnumInputType(string $sSortEnumInputTypeClass)
    {
        $this->sSortEnumInputTypeClass = $sSortEnumInputTypeClass;
    }

    protected function setSortMethodName(string $sSortMethodName)
    {
        $this->sSortMethodName = $sSortMethodName;
    }

    /**
     * Add filter method to ElementCollectionType
     * @param $sMethodName
     * @param $obFilteredList
     * @return void
     */
    protected function addFilterMethod($sMethodName, $obFilteredList)
    {
        $this->arMethodData[$sMethodName] = $obFilteredList;
    }

    /**
     * Extend ElementCollectionType
     * @return void
     */
    protected function runExtensionLogic($obApiType)
    {
        parent::runExtensionLogic($obApiType);

        /** @var AbstractCollectionType $obApiType */
        // Set filter input type
        if (!empty($this->sFilterInputTypeClass)) {
            $obApiType->setFilterInputTypeClass($this->sFilterInputTypeClass);
        }

        // Set sort enum input type
        if (!empty($this->sSortEnumInputTypeClass)) {
            $obApiType->setSortEnumInputTypeClass($this->sSortEnumInputTypeClass);
        }

        // Set sort method name
        if (!empty($this->sSortMethodName)) {
            $obApiType->setSortMethodName($this->sSortMethodName);
        }

        // Add filter methods
        foreach ($this->arMethodData as $sMethodName => $closure) {
            $obApiType->addDynamicMethod(
                $sMethodName,
                function ($mArguments) use ($obApiType, $closure) {
                    $obCurrentList = $obApiType->getList();
                    $obFilteredList = $closure($obCurrentList, $mArguments);
                    $arElementIdList = is_array($obFilteredList)
                        ? array_keys($obFilteredList)
                        : $obFilteredList->getIDList();
                    $obApiType->setList($obCurrentList->intersect($arElementIdList));
                }
            );
        }
    }
}
