<?php namespace Lovata\Toolbox\Classes\Helper;

use Backend\Models\ExportModel;
use Event;

/**
 * Class AbstractExportModelInCSV
 * @package Lovata\Toolbox\Classes\Helper
 * @author  Sergey Zakharevich, <s.v.zakharevich@gmail.com>, LOVATA Group
 */
abstract class AbstractExportModelInCSV extends ExportModel
{
    const EVENT_BEFORE_EXPORT = 'model.beforeExport';

    const RELATION_LIST = [];

    /** @var array */
    protected $arColumnList = [];
    /** @var array */
    protected $arRelationColumnList = [];

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

        foreach ($obItemList as $obOrderPosition) {
            $arRow = $this->prepareRow($obOrderPosition);
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

        foreach ($arColumns as $sColumn) {
            if (in_array($sColumn, static::RELATION_LIST)) {
                $this->arRelationColumnList[] = $sColumn;
            } else {
                $this->arColumnList[] = $sColumn;
            }
        }
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

        $arEventData = [static::class, $arData];
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
     * Prepare model property data.
     * @param \Model $obModel
     * @return array
     */
    protected function prepareModelPropertiesData($obModel) : array
    {
        return [];
    }
}
