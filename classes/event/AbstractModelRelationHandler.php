<?php namespace Lovata\Toolbox\Classes\Event;

/**
 * Class AbstractModelRelationHandler
 * @package Lovata\Toolbox\Classes\Event
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
abstract class AbstractModelRelationHandler
{
    protected $iPriority = 1000;

    /** @var string */
    protected $sRelationName;

    /**
     * Add listeners
     */
    public function subscribe()
    {
        $sModelClass = $this->getModelClass();

        $sModelClass::extend(function ($obModel) {
            if (class_exists('System')) {
                $sAfterAttach = 'model.relation.attach';
                $sAfterDetach = 'model.relation.detach';
            }else {
                $sAfterAttach = 'model.relation.afterAttach';
                $sAfterDetach = 'model.relation.afterDetach';
            }

            /** @var \Model $obModel */
            $obModel->bindEvent($sAfterAttach, function ($sRelationName, $arAttachedIDList, $arInsertData) use ($obModel, $sAfterAttach) {
                if (!$this->checkRelationName($sRelationName)) {
                    return;
                }

                $this->sRelationName = $sRelationName;
                $this->afterAttach($obModel, $arAttachedIDList, $arInsertData);
            }, $this->iPriority);

            $obModel->bindEvent($sAfterDetach, function ($sRelationName, $arAttachedIDList) use ($obModel, $sAfterDetach) {
                if (!$this->checkRelationName($sRelationName)) {
                    return;
                }
                if (is_null($arAttachedIDList)) {
                    $arAttachedIDList = $obModel->$sRelationName()->newPivotQuery()->lists($obModel->$sRelationName()->getRelatedPivotKeyName());
                }

                $this->sRelationName = $sRelationName;
                $this->afterDetach($obModel, $arAttachedIDList);
            }, $this->iPriority);
        });
    }

    /**
     * After attach event handler
     * @param \Model $obModel
     * @param array  $arAttachedIDList
     * @param array  $arInsertData
     */
    protected function afterAttach($obModel, $arAttachedIDList, $arInsertData)
    {
    }

    /**
     * After detach event handler
     * @param \Model $obModel
     * @param array  $arAttachedIDList
     */
    protected function afterDetach($obModel, $arAttachedIDList)
    {
    }

    /**
     * Check relation name
     * @param string $sRelationName
     * @return bool
     */
    protected function checkRelationName($sRelationName) : bool
    {
        $sCheckedRelationName = $this->getRelationName();
        if (empty($sCheckedRelationName)) {
            return true;
        }

        if (is_array($sCheckedRelationName) && in_array($sRelationName, $sCheckedRelationName)) {
            return true;
        }

        $bResult = $sRelationName == $sCheckedRelationName;

        return $bResult;
    }

    /**
     * Get model class name
     * @return string
     */
    abstract protected function getModelClass() : string;

    /**
     * Get relation name
     * @return string|array
     */
    abstract protected function getRelationName();
}
