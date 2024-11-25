<?php namespace Lovata\Toolbox\Classes\Helper;

use Event;
use Backend\Models\ExportModel;

/**
 * Class AbstractExportModelInCSV
 * @package Lovata\Toolbox\Classes\Helper
 * @author  Sergey Zakharevich, <s.v.zakharevich@gmail.com>, LOVATA Group
 */
abstract class AbstractExportModelInCSV extends ExportModel
{
    const EVENT_BEFORE_EXPORT = 'model.beforeExport';

    const RELATION_LIST = [];

    const EVENT_EXTEND_GET_PROPERTY_LIST = 'model.getPropertyList';

    /** @var array */
    protected $arDefaultColumnList = [];
    /** @var array */
    protected $arColumnList = [];
    /** @var array */
    protected $arRelationColumnList = [];
    /** @var array */
    protected $arPropertyColumnList = [];

    /**
     * Export data.
     * @param array|null  $arColumns
     * @param string|null $sSessionKey
     * @return array
     */
    public function exportData($arColumns, $sSessionKey = null) : array
    {
        $arList = [];
        if (empty($arColumns)) {
            return $arList;
        }

        $this->init($arColumns);

        $obItemList = $this->getItemList();

        if (!$obItemList instanceof \Illuminate\Database\Eloquent\Collection || $obItemList->isEmpty()) {
            return $arList;
        }

        foreach ($obItemList as $obItem) {
            $arRow = $this->prepareRow($obItem);
            if (empty($arRow)) {
                continue;
            }
            $arList[] = $arRow;
        }

        return $arList;
    }

    /**
     * Init.
     * @param array|null $arColumns
     * @return void
     */
    protected function init($arColumns)
    {
        if (empty($arColumns) || !is_array($arColumns)) {
            return;
        }

        $this->arDefaultColumnList = $arColumns;
        $arPropertyList = $this->getPropertyList();

        foreach ($arColumns as $sColumn) {
            if (in_array($sColumn, static::RELATION_LIST)) {
                $this->arRelationColumnList[] = $sColumn;
            } elseif (in_array($sColumn, $arPropertyList)) {
                $this->arPropertyColumnList[] = $sColumn;
            } else {
                $this->arColumnList[] = $sColumn;
            }
        }

        $this->initPropertyColumnList();
    }

    /**
     * Get property list.
     * @return array
     */
    protected function getPropertyList() : array
    {
        $arEventData    = Event::fire(self::EVENT_EXTEND_GET_PROPERTY_LIST, [static::class]);
        $arPropertyList = [];

        if (empty($arEventData) || !is_array($arEventData)) {
            return $arPropertyList;
        }

        foreach ($arEventData as $arAdditionData) {
            if (empty($arAdditionData) || !is_array($arAdditionData)) {
                continue;
            }

            $arPropertyList = array_merge($arPropertyList, $arAdditionData);
        }

        return $arPropertyList;
    }

    /**
     * Init property column list.
     */
    protected function initPropertyColumnList()
    {
        if (empty($this->arPropertyColumnList)) {
            return;
        }

        $arPropertyColumnListTemp = [];

        foreach ($this->arPropertyColumnList as $sPropertyColumn) {
            $arPropertyColumn   = explode('.', $sPropertyColumn);
            $iPropertyColumnKey = array_pop($arPropertyColumn);

            $arPropertyColumnListTemp[$sPropertyColumn] = $iPropertyColumnKey;
        }

        $this->arPropertyColumnList = $arPropertyColumnListTemp;
    }

    /**
     * Get item list.
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    abstract protected function getItemList();

    /**
     * Prepare row.
     * @param \Model $obModel
     * @return array
     */
    protected function prepareRow($obModel) : array
    {
        $arModelData           = $this->prepareModelData($obModel);
        $arModelRelationsData  = $this->prepareModelRelationsData($obModel);
        $arModelPropertiesData = $this->prepareModelPropertiesData($obModel);

        $arData = array_merge($arModelData, $arModelRelationsData, $arModelPropertiesData);

        $arEventData = [static::class, $obModel, $arData, $this->arDefaultColumnList];
        $arEventData = Event::fire(self::EVENT_BEFORE_EXPORT, $arEventData);

        foreach ($arEventData as $arModelData) {
            if (empty($arModelData)) {
                continue;
            }

            foreach ($arModelData as $sKey => $sValue) {
                $arData[$sKey] = $sValue;
            }
        }

        return $arData;
    }

    /**
     * Prepare model data.
     * @param \Model $obModel
     * @return array
     */
    protected function prepareModelData($obModel) : array
    {
        $arResult = [];

        if (empty($this->arColumnList)) {
            return $arResult;
        }

        foreach ($this->arColumnList as $sField) {
            $arResult[$sField] = (string) $obModel->$sField;
        }

        return $arResult;
    }

    /**
     * Prepare model relations data.
     * @param \Model $obModel
     * @return array
     */
    protected function prepareModelRelationsData($obModel) : array
    {
        return [];
    }

    /**
     * Prepare model properties data.
     * @param \Model $obModel
     * @return array
     */
    protected function prepareModelPropertiesData($obModel) : array
    {
        $arResult = [];

        if (empty($this->arPropertyColumnList) || !isset($obModel->property) || empty($obModel->property)) {
            return $arResult;
        }

        foreach ($this->arPropertyColumnList as $sKey => $sField) {
            $arResult[$sKey] = array_get($obModel->property, $sField);
        }

        return $arResult;
    }
}
