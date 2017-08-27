<?php namespace Kharanenka\Helper;

use Config;
use System\Models\File;
use October\Rain\Database\Collection;

/**
 * Class DataFileModel
 * @package Kharanenka\Helper;
 * @author Andrey Kharanenka, kharanenka@gmail.com
 */
trait DataFileModel {

    /**
     * Get file data
     * @param string $sFieldName
     * @return array|null
     */
    public function getFileData($sFieldName) {
        
        if(empty($sFieldName)) {
            return null;
        }
        
        /** @var File $obFile */
        $obFile = $this->$sFieldName;
        if(empty($obFile) || !$obFile instanceof File) {
            return null;
        }
        
        return $this->getFileDataValue($obFile);
    }

    /**
     * Get file data value
     * @param File $obFile
     * @return array|null
     */
    protected function getFileDataValue($obFile) {

        if(empty($obFile) || !$obFile instanceof File) {
            return null;
        }

        $sUploadFolder = Config::get('cms.storage.uploads.path', '/storage/app/uploads');

        return [
            'full_path' => $obFile->getPath(),
            'path'      => $sUploadFolder . str_replace('uploads', '', $obFile->getDiskPath()),
            'title'     => $obFile->getAttribute('title'),
            'alt'       => $obFile->getAttribute('description'),
        ];
    }

    /**
     * Get file list data
     * @param $sFieldName
     * @return array
     */
    public function getFileListData($sFieldName) {

        if(empty($sFieldName)) {
            return [];
        }

        /** @var Collection $obFileList */
        $obFileList = $this->$sFieldName;
        if($obFileList->isEmpty()) {
            return [];
        }
        
        $arResult = [];
        /** @var File $obFile */
        foreach($obFileList as $obFile) {
            if(empty($obFile) || !$obFile instanceof File) {
                continue;
            }
            
            $arResult[] = $this->getFileDataValue($obFile);
        }

        return $arResult;
    }
}