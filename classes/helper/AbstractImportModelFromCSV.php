<?php namespace Lovata\Toolbox\Classes\Helper;

use Input;
use Lang;
use Exception;
use System\Models\File;
use Lovata\Toolbox\Models\Settings;

/**
 * Class AbstractImportModelFromCSV
 * @package Lovata\Toolbox\Classes\Helper
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
abstract class AbstractImportModelFromCSV extends AbstractImportModel
{
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
            $this->setResultMethod();

            return;
        }

        $this->run();
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
     * Create new item
     */
    protected function createItem()
    {
        $sModelClass = static::MODEL_CLASS;
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
            $sPath = $this->checkForRemoteFile(trim($sPath));
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
     * Init preview image path
     */
    protected function initPreviewImage()
    {
        if (!array_key_exists('preview_image', $this->arImportData)) {
            $this->bNeedUpdatePreviewImage = false;

            return;
        }

        $this->bNeedUpdatePreviewImage = true;
        $sTrimmedImage = trim(array_get($this->arImportData, 'preview_image'));
        $this->sPreviewImage = $this->checkForRemoteFile($sTrimmedImage);
        if (empty($this->sPreviewImage)) {
            return;
        }

        $this->sPreviewImage = storage_path($this->sPreviewImage);
        if (!file_exists($this->sPreviewImage)) {
            $this->sPreviewImage = null;
        }
    }

    /**
     * Check for remote file and downloads it if possible
     */
    protected function checkForRemoteFile($sPotentialUrl)
    {
        if (!preg_match('/https?:\/\//', $sPotentialUrl)) {
            return $sPotentialUrl;
        }

        try {
            $obFile = new File;
            $obFile->fromUrl($sPotentialUrl);
            $obFile->save();

            $sValue = 'app/' . $obFile->getDiskPath();

            return $sValue;
        } catch(Exception $obException) {
            return $sPotentialUrl;
        }
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
