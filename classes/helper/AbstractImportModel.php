<?php namespace Lovata\Toolbox\Classes\Helper;

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
     * Get model class
     * @return string
     */
    abstract protected function getModelClass() : string;

    /**
     * Create new item
     */
    abstract protected function createItem();

    /**
     * Update item
     */
    abstract protected function updateItem();

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
            if (is_string($sValue)) {
                $this->arImportData[$sKey] = trim($sValue);
            } elseif (is_array($sValue)) {
                $this->arImportData[$sKey] = array_filter($sValue);
            }
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
            $this->arImportData['active'] = $this->processBooleanValue($bActive);
        }
    }

    /**
     * @param string $sValue
     * @return bool
     */
    protected function processBooleanValue($sValue) : bool
    {
        if (is_string($sValue) && $sValue == 'false') {
            return false;
        } else {
            return (bool) $sValue;
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
}
