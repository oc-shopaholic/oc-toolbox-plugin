<?php namespace Lovata\Toolbox\Classes\Helper;

use Log;
use Lang;
use Event;

use Kharanenka\Helper\Result;
use Lovata\Toolbox\Models\Settings;

/**
 * Class AbstractImportModelFromXML
 * @package Lovata\Toolbox\Classes\Helper
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
abstract class AbstractImportModelFromXML extends AbstractImportModel
{
    const EXTEND_FIELD_LIST = '';
    const EXTEND_IMPORT_DATA = '';
    const PARSE_NODE_CLASS = ParseXMLNode::class;

    /** @var ImportXMLNode */
    protected $obMainXMLFile;
    protected $arFieldList = [];

    protected $sMainFilePath;
    protected $sElementListPath;
    protected $sImageFolderPath = '';
    protected $arImportSettings = [];
    protected $arXMLFileList = [];

    protected $iCreatedCount = 0;
    protected $iUpdatedCount = 0;
    protected $iSkippedCount = 0;
    protected $iProcessedCount = 0;

    /** @var array ImportXMLNode */
    protected $arElementList;

    /**
     * Get model fields
     * @return array
     */
    public function getFields() : array
    {
        $this->arFieldList = $this->extendImportFields($this->arFieldList);
        $this->arFieldList = $this->initLangFields($this->arFieldList);

        return $this->arFieldList;
    }

    /**
     * Get created count
     * @return int
     */
    public function getCreatedCount()
    {
        return $this->iCreatedCount;
    }

    /**
     * Get updated count
     * @return int
     */
    public function getUpdatedCount()
    {
        return $this->iUpdatedCount;
    }

    /**
     * Get skipped count
     * @return int
     */
    public function getSkippedCount()
    {
        return $this->iSkippedCount;
    }

    /**
     * Get processed count
     * @return int
     */
    public function getProcessedCount()
    {
        return $this->iProcessedCount;
    }

    /**
     * Get total count of elements
     * @return int
     */
    public function getTotalCount()
    {
        return !empty($this->arElementList) ? count($this->arElementList) : 0;
    }

    /**
     * Start import
     * @param $obProgressBar
     * @throws
     */
    public function import($obProgressBar = null)
    {
        $this->openMainFile();
        if (empty($this->arElementList)) {
            return;
        }

        $sParseNodeClass = static::PARSE_NODE_CLASS;
        foreach ($this->arElementList as $obElementNode) {

            /** @var ParseXMLNode $obParseNode */
            $obParseNode = new $sParseNodeClass($obElementNode, $this->arImportSettings);
            $arImportData = $obParseNode->get();

            $arImportData = $this->extendImportData($arImportData, $obParseNode);

            $this->importRow($arImportData);
            if (!empty($obProgressBar)) {
                $obProgressBar->advance();
            }
        }
    }

    /**
     * Import item
     * @param array $arModeData
     * @param bool  $bWithQueue
     * @throws \Throwable
     */
    public function importRow($arModeData, $bWithQueue = true)
    {
        $this->iProcessedCount++;

        if (empty($arModeData)) {
            $this->setErrorMessage(Lang::get('lovata.toolbox::lang.message.row_is_empty'));
            return;
        }

        $this->sExternalID = trim(array_get($arModeData, 'external_id'));
        if (empty($this->sExternalID)) {
            $this->setErrorMessage(Lang::get('lovata.toolbox::lang.message.external_id_is_empty'));
            return;
        }

        $this->arImportData = $arModeData;
        $this->arProcessedIDList[] = $this->sExternalID;

        $bQueueOn = Settings::getValue('import_queue_on');
        if ($bQueueOn && $bWithQueue) {
            $this->createJob();

            return;
        }

        $this->run();
    }

    /**
     * Open XML file and read file
     */
    public function openMainFile()
    {
        if (!empty($this->obMainXMLFile)) {
            return;
        }

        $sFilePath = storage_path($this->sMainFilePath);
        if (empty($this->sMainFilePath) || !file_exists($sFilePath)) {
            return;
        }

        $this->obMainXMLFile = new ImportXMLNode(file_get_contents($sFilePath));
        if (empty($this->obMainXMLFile)) {
            return;
        }

        $this->arElementList = $this->obMainXMLFile->findListByPath($this->sElementListPath);
    }

    /**
     * Create new item
     */
    protected function createItem()
    {
        $sModelClass = static::MODEL_CLASS;
        try {
            $this->obModel = $sModelClass::create($this->arImportData);
        } catch (\Exception $obException) {
            trace_log($obException);
            $this->setErrorMessage($obException->getMessage());

            return;
        }

        $this->iCreatedCount++;
    }

    /**
     * Update item
     */
    protected function updateItem()
    {
        try {
            $this->obModel->update($this->arImportData);
        } catch (\Exception $obException) {
            trace_log($obException);
            $this->setErrorMessage($obException->getMessage());

            return;
        }

        if ($this->bWithTrashed && $this->obModel->trashed()) {
            $this->obModel->restore();
        }

        $this->iUpdatedCount++;
    }

    /**
     * Init image list
     */
    protected function initImageList()
    {
        if (!array_key_exists('images', $this->arImportData)) {
            $this->bNeedUpdateImageList = false;
            return;
        }

        $this->bNeedUpdateImageList = true;
        $this->arImageList = array_get($this->arImportData, 'images');
        array_forget($this->arImportData, 'images');
        if (empty($this->arImageList)) {
            return;
        }

        if (is_string($this->arImageList)) {
            $this->arImageList = [$this->arImageList];
        }

        foreach ($this->arImageList as $iKey => $sPath) {
            $sPath = trim($sPath);
            $sPath = trim($sPath, '/');
            if (empty($sPath)) {
                unset($this->arImageList[$iKey]);
                continue;
            }

            $sFilePath = storage_path(trim($this->sImageFolderPath.'/'.$sPath, '/'));
            if (!file_exists($sFilePath)) {
                unset($this->arImageList[$iKey]);
            } else {
                $this->arImageList[$iKey] = $sFilePath;
            }
        }
    }

    /**
     * Init preview image path
     */
    protected function initPreviewImage()
    {
        if (!array_key_exists('preview_image', $this->arImportData)) {
            $this->bNeedUpdatePreviewImage = false;

            return;
        }

        $this->bNeedUpdatePreviewImage = true;
        $this->sPreviewImage = array_get($this->arImportData, 'preview_image');
        if (is_array($this->sPreviewImage)) {
            $this->sPreviewImage = array_shift($this->sPreviewImage);
        }

        $this->sPreviewImage = trim($this->sPreviewImage);
        $this->sPreviewImage = trim($this->sPreviewImage, '/');
        array_forget($this->arImportData, 'preview_image');
        if (empty($this->sPreviewImage)) {
            return;
        }

        $this->sPreviewImage = storage_path(trim($this->sImageFolderPath.'/'.$this->sPreviewImage, '/'));
        if (!file_exists($this->sPreviewImage)) {
            $this->sPreviewImage = null;
        }
    }

    /**
     * Set error message
     * @param string $sMessage
     */
    protected function setErrorMessage($sMessage)
    {
        Log::warning($sMessage);

        Result::setFalse()->setMessage($sMessage);
        $this->iSkippedCount++;
    }

    /**
     * Fire event and extend import fields
     * @param array $arFieldList
     * @return array
     */
    protected function extendImportFields($arFieldList)
    {
        $arEventData = Event::fire(static::EXTEND_FIELD_LIST, [$arFieldList]);
        if (empty($arEventData)) {
            return $arFieldList;
        }

        foreach ($arEventData as $arAdditionFieldList) {
            if (empty($arAdditionFieldList) || !is_array($arAdditionFieldList)) {
                continue;
            }

            $arFieldList = array_merge($arFieldList, $arAdditionFieldList);
        }

        return $arFieldList;
    }

    /**
     * Fire event and extend import data
     * @param array        $arImportData
     * @param ParseXMLNode $obParseNode
     * @return array
     */
    protected function extendImportData($arImportData, $obParseNode)
    {
        $arEventData = Event::fire(static::EXTEND_IMPORT_DATA, [$arImportData, $obParseNode]);
        if (empty($arEventData)) {
            return $arImportData;
        }

        foreach ($arEventData as $arAdditionData) {
            if (empty($arAdditionData) || !is_array($arAdditionData)) {
                continue;
            }

            $arImportData = array_merge($arImportData, $arAdditionData);
        }

        return $arImportData;
    }

    /**
     * Add lang fields
     * @param array $arFieldList
     * @return array
     */
    protected function initLangFields($arFieldList)
    {
        $arActiveLangList = $this->getActiveLangList();
        if (empty($arActiveLangList)) {
            return $arFieldList;
        }

        $sModelClass = static::MODEL_CLASS;
        $obModel = new $sModelClass();
        $arLangFieldList = $obModel->translatable;
        if (empty($arLangFieldList)) {
            return $arFieldList;
        }

        foreach ($arLangFieldList as $sFieldName) {
            if (!array_key_exists($sFieldName, $arFieldList)) {
                continue;
            }

            foreach ($arActiveLangList as $sLangCode) {
                $arFieldList[$sLangCode.'.'.$sFieldName] = $arFieldList[$sFieldName]." ($sLangCode)";
            }
        }

        return $arFieldList;
    }
}
