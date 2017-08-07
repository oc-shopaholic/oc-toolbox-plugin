<?php namespace Kharanenka\Helper;

use October\Rain\Database\Collection;
use System\Models\File;

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
        
        return [
            'path' => $obFile->getPath(),
            'title' => $obFile->getAttribute('title'),
            'alt' => $obFile->getAttribute('description'),
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