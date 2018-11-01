<?php namespace Lovata\Toolbox\Classes\Queue;

/**
 * Class ImportItemQueue
 * @package Lovata\Toolbox\Classes\Queue
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class ImportItemQueue
{
    /**
     * @param \Illuminate\Queue\Jobs\Job $obJob
     * @param array                      $arQueueData
     * @throws \Throwable
     */
    public function fire($obJob, $arQueueData)
    {
        $sImportClass = array_get($arQueueData, 'class');
        $arImportData = array_get($arQueueData, 'data');

        $this->import($sImportClass, $arImportData);

        $obJob->delete();
    }

    /**
     * Import item
     * @param string $sImportClass
     * @param array  $arImportData
     * @throws \Throwable
     */
    protected function import($sImportClass, $arImportData)
    {
        if (empty($sImportClass) || empty($arImportData) || !class_exists($sImportClass)) {
            return;
        }

        /** @var \Lovata\Toolbox\Classes\Helper\AbstractImportModel $obImport */
        $obImport = new $sImportClass();
        $obImport->import($arImportData, false);
    }
}
