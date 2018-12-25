<?php namespace Lovata\Toolbox\Classes\Parser\Update;

use Lovata\Toolbox\Classes\Parser\Create\PluginLangCreateFile;

/**
 * Class PluginLangUpdateFile
 * @package Lovata\Toolbox\Classes\Parser\Update
 * @author Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class PluginLangUpdateFile extends CommonUpdateFile
{
    /** @var string */
    protected $sFilePath = '/{{lower_author}}/{{lower_plugin}}/lang/{{lang}}/lang.php';

    /**
     * Update file
     * @param array $arData
     */
    public function update($arData)
    {
        $arReplaceList = array_get($this->arData, 'replace');

        if (empty($arData) || !is_array($arData) || empty($arReplaceList) || !$this->bUpdate) {
            return;
        }

        $arLangData = $this->getLangData();

        foreach ($arData as $sKeyLang => $arValueLang) {
            if (!is_array($arValueLang)) {
                continue;
            }

            $sKeyLang = $this->parseByName($arReplaceList, $sKeyLang);

            foreach ($arValueLang as $sKeyParam => $sValueParam) {
                $sKeyParam   = $this->parseByName($arReplaceList, $sKeyParam);
                $sValueParam = $this->parseByName($arReplaceList, $sValueParam);

                $arCheck = array_get($arLangData, $sKeyLang.'.'.$sKeyParam);

                if (empty($arCheck)) {
                    array_set($arLangData, $sKeyLang.'.'.$sKeyParam, $sValueParam);
                }
            }
        }

        $this->sContent = $this->arrayToStringFile($arLangData);
        $this->save();
    }


    /**
     * Class create file
     * @return string
     */
    protected function classCreateFile()
    {
        return PluginLangCreateFile::class;
    }

    /**
     * Get lang data
     * @return array|mixed
     */
    protected function getLangData()
    {
        if (!isset($this->sFilePath) || empty($this->sFilePath)) {
            return [];
        }

        return require $this->sFilePath;
    }

    /**
     * Save lang.php
     */
    protected function save()
    {
        if (!isset($this->sContent) || empty($this->sContent)) {
            return;
        }

        $this->obFile->put($this->sFilePath, $this->sContent);
    }
}
