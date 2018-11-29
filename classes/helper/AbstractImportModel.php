<?php namespace Lovata\Toolbox\Classes\Helper;

use Input;
use Lang;
use Event;
use Queue;
use System\Models\File;
use Lovata\Toolbox\Models\Settings;
use Lovata\Toolbox\Classes\Queue\ImportItemQueue;

/**
 * Class AbstractImportModel
 * @package Lovata\Toolbox\Classes\Helper
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
abstract class AbstractImportModel
{
    const EVENT_BEFORE_IMPORT = 'model.beforeImport';
    const EVENT_AFTER_IMPORT = 'model.afterImport';

    /** @var array */
    protected $arImportData = [];

    /** @var \Model */
    protected $obModel;

    /** @var null|string */
    protected $sExternalID = null;

    protected $bWithTrashed = false;

    /** @var array */
    protected $arImageList = [];

    protected $sPreviewImage;
    protected $bNeedUpdateImageList = false;
    protected $bNeedUpdatePreviewImage = false;

    /** @var bool */
    protected $bDeactivate = true;

    /** @var array */
    protected $arExistIDList = [];

    /** @var array */
    protected $arProcessedIDList = [];

    protected $sResultMethod = null;
    protected $sErrorMessage = null;

    /**
     * Set deactivate flag
     */
    public function setDeactivateFlag()
    {
        $this->bDeactivate = (bool) Input::get('ImportOptions.deactivate');
    }

    /**
     * Import item
     * @param array $arModeData
     * @param bool  $bWithQueue
     * @throws \Throwable
     */
    public function import($arModeData, $bWithQueue = true)
    {
        $this->sResultMethod = null;
        $this->sErrorMessage = null;

        if (empty($arModeData)) {
            $this->setWarningResult('lovata.toolbox::lang.message.row_is_empty');

            return;
        }

        $this->sExternalID = trim(array_get($arModeData, 'external_id'));
        if (empty($this->sExternalID)) {
            $this->setWarningResult('lovata.toolbox::lang.message.external_id_is_empty');

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
     * Deactivate active elements
     */
    public function deactivateElements()
    {
        if (!$this->bDeactivate) {
            return;
        }

        $arDeactivateIDList = array_diff((array) $this->arExistIDList, (array) $this->arProcessedIDList);
        if (empty($arDeactivateIDList)) {
            return;
        }

        //Get element list
        $sModelClass = $this->getModelClass();
        $obElementList = $sModelClass::whereIn('external_id', $arDeactivateIDList)->get();
        foreach ($obElementList as $obElement) {
            $obElement->active = false;
            $obElement->save();
        }
    }

    /**
     * Get result method
     * @return string
     */
    public function getResultMethod()
    {
        return $this->sResultMethod;
    }

    /**
     * Get result error message
     * @return string
     */
    public function getResultError()
    {
        return $this->sErrorMessage;
    }

    /**
     * Get model class
     * @return string
     */
    abstract protected function getModelClass() : string;

    /**
     * Run import item
     */
    protected function run()
    {
        $this->prepareImportData();
        $this->fireBeforeImportEvent();

        $this->findByExternalID();
        if (!empty($this->obModel)) {
            $this->updateItem();
        } else {
            $this->createItem();
        }

        if (empty($this->obModel)) {
            return;
        }

        $this->processModelObject();
        Event::fire(self::EVENT_AFTER_IMPORT, [$this->obModel, $this->arImportData]);
    }

    /**
     * Create new item
     */
    protected function createItem()
    {
        $sModelClass = $this->getModelClass();
        try {
            $this->obModel = $sModelClass::create($this->arImportData);
        } catch (\Exception $obException) {
            trace_log($obException);
            $this->setErrorResult($obException->getMessage());

            return;
        }

        $this->setCreatedResult();
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
            $this->setErrorResult($obException->getMessage());

            return;
        }

        if ($this->bWithTrashed && $this->obModel->trashed()) {
            $this->obModel->restore();
        }

        $this->setUpdatedResult();
    }

    /**
     * Find item by external ID
     */
    protected function findByExternalID()
    {
        $sModelClass = $this->getModelClass();
        if ($this->bWithTrashed) {
            $this->obModel = $sModelClass::withTrashed()->getByExternalID($this->sExternalID)->first();
        } else {
            $this->obModel = $sModelClass::getByExternalID($this->sExternalID)->first();
        }
    }

    /**
     * Prepare array of import data
     */
    protected function prepareImportData()
    {
        if (empty($this->arImportData)) {
            return;
        }

        foreach ($this->arImportData as $sKey => $sValue) {
            if (!is_string($sValue)) {
                continue;
            }

            $this->arImportData[$sKey] = trim($sValue);
        }
    }

    /**
     * Process model object after creation/updating
     */
    protected function processModelObject()
    {
    }

    /**
     * Fire beforeImport event and update import data array
     */
    protected function fireBeforeImportEvent()
    {
        $arEventData = [$this->getModelClass(), $this->arImportData];

        $arEventData = Event::fire(self::EVENT_BEFORE_IMPORT, $arEventData);
        if (empty($arEventData)) {
            return;
        }

        foreach ($arEventData as $arModelData) {
            if (empty($arModelData)) {
                continue;
            }

            foreach ($arModelData as $sKey => $sValue) {
                $this->arImportData[$sKey] = $sValue;
            }
        }
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
        $this->arImageList = explode(',', array_get($this->arImportData, 'images'));
        array_forget($this->arImportData, 'images');

        if (empty($this->arImageList)) {
            return;
        }

        foreach ($this->arImageList as $iKey => $sPath) {
            $sPath = trim($sPath);
            if (empty($sPath)) {
                unset($this->arImageList[$iKey]);
                continue;
            }

            $sFilePath = storage_path($sPath);
            if (!file_exists($sFilePath)) {
                unset($this->arImageList[$iKey]);
            } else {
                $this->arImageList[$iKey] = $sFilePath;
            }
        }
    }

    /**
     * Import obProductModel images
     */
    protected function importImageList()
    {
        if (!$this->bNeedUpdateImageList) {
            return;
        }

        if (empty($this->arImageList)) {
            $this->removeAllImages();

            return;
        }

        //Update old images
        $obImageList = $this->obModel->images;
        if (!$obImageList->isEmpty()) {
            /** @var File $obImage */
            foreach ($obImageList as $obImage) {
                $sFilePath = array_shift($this->arImageList);

                //Check image
                if (!empty($sFilePath) && (!file_exists($obImage->getLocalPath()) || md5_file($sFilePath) != md5_file($obImage->getLocalPath()))) {
                    $obImage->deleteThumbs();
                    $obImage->fromFile($sFilePath);
                    $obImage->save();
                } elseif (empty($sFilePath)) {
                    $obImage->deleteThumbs();
                    $obImage->delete();
                }
            }
        }

        //Create new images
        if (!empty($this->arImageList)) {
            foreach ($this->arImageList as $sFilePath) {
                $obImage = new File();
                $obImage->fromFile($sFilePath);

                $this->obModel->images()->add($obImage);
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
        $this->sPreviewImage = trim(array_get($this->arImportData, 'preview_image'));
        if (empty($this->sPreviewImage)) {
            return;
        }

        $this->sPreviewImage = storage_path($this->sPreviewImage);
        if (!file_exists($this->sPreviewImage)) {
            $this->sPreviewImage = null;
        }
    }

    /**
     * Import preview image
     */
    protected function importPreviewImage()
    {
        if (!$this->bNeedUpdatePreviewImage) {
            return;
        }

        $obPreviewImage = $this->obModel->preview_image;
        if (empty($obPreviewImage) && empty($this->sPreviewImage)) {
            return;
        }

        if (empty($obPreviewImage) && !empty($this->sPreviewImage)) {
            //Create new preview
            $obPreviewImage = new File();
            $obPreviewImage->fromFile($this->sPreviewImage);
            $this->obModel->preview_image()->add($obPreviewImage);

            return;
        }

        if (!file_exists($obPreviewImage->getLocalPath())) {
            $obPreviewImage->fromFile($this->sPreviewImage);
            $obPreviewImage->save();
        } elseif (!empty($this->sPreviewImage) && file_exists($obPreviewImage->getLocalPath()) && md5_file($this->sPreviewImage) != md5_file($obPreviewImage->getLocalPath())) {
            //Update preview image
            $obPreviewImage->deleteThumbs();
            $obPreviewImage->fromFile($this->sPreviewImage);
            $obPreviewImage->save();
        } elseif (!empty($obPreviewImage) && empty($this->sPreviewImage)) {
            $obPreviewImage->deleteThumbs();
            $obPreviewImage->delete();
        }
    }

    /**
     * Remove all images
     */
    protected function removeAllImages()
    {
        //Delete old images
        $obImageList = $this->obModel->images;
        if ($obImageList->isEmpty()) {
            return;
        }

        /** @var \System\Models\File $obFile */
        foreach ($obImageList as $obFile) {
            $obFile->deleteThumbs();
            $obFile->delete();
        }
    }

    /**
     * Set active filed value, if active value is not null
     */
    protected function setActiveField()
    {
        $bActive = array_get($this->arImportData, 'active');
        if ($bActive === null) {
            $this->arImportData['active'] = true;
        } else {
            $this->arImportData['active'] = (bool) $bActive;
        }
    }

    /**
     * Create queue job with import single item
     * @throws \Throwable
     */
    protected function createJob()
    {
        $sQueueName = Settings::getValue('import_queue_name');

        $arQueueData = [
            'class' => static::class,
            'data'  => $this->arImportData,
        ];

        if (empty($sQueueName)) {
            Queue::push(ImportItemQueue::class, $arQueueData);
        } else {
            Queue::pushOn($sQueueName, ImportItemQueue::class, $arQueueData);
        }

        $this->setResultMethod();
    }

    /**
     * Set create/update result method
     */
    protected function setResultMethod()
    {
        if (!empty($this->arExistIDList) && in_array($this->sExternalID, $this->arExistIDList)) {
            $this->setUpdatedResult();

            return;
        }

        $this->setCreatedResult();
    }

    /**
     * Set result method name as logCreated()
     */
    protected function setCreatedResult()
    {
        $this->sResultMethod = 'logCreated';
    }

    /**
     * Set result method name as logUpdated()
     */
    protected function setUpdatedResult()
    {
        $this->sResultMethod = 'logUpdated';
    }

    /**
     * Set result method name as logError()
     * @param string $sMessage
     */
    protected function setErrorResult($sMessage = null)
    {
        if (!empty($sMessage)) {
            $this->sErrorMessage = Lang::get($sMessage);
        }

        $this->sResultMethod = 'logError';
    }

    /**
     * Set result method name as logWarning()
     * @param string $sMessage
     */
    protected function setWarningResult($sMessage = null)
    {
        if (!empty($sMessage)) {
            $this->sErrorMessage = Lang::get($sMessage);
        }

        $this->sResultMethod = 'logWarning';
    }

    /**
     * Set result method name as logSkipped()
     * @param string $sMessage
     */
    protected function setSkippedResult($sMessage = null)
    {
        if (!empty($sMessage)) {
            $this->sErrorMessage = Lang::get($sMessage);
        }

        $this->sResultMethod = 'logSkipped';
    }
}
