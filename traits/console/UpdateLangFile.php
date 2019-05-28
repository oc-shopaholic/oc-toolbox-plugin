<?php namespace Lovata\Toolbox\Traits\Console;

use Lovata\Toolbox\Classes\Parser\Update\PluginLangUpdateFile;

/**
 * Trait UpdateLangFile
 * @package Lovata\Toolbox\Traits\Console
 * @author Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
trait UpdateLangFile
{
    /**
     * Get lang list
     * @return array
     */
    protected function getLangList()
    {
        if (!isset($this->arData) || empty($this->arData)) {
            return [];
        }

        $sLowerAuthor = array_get($this->arData, 'replace.'.self::PREFIX_LOWER.self::CODE_AUTHOR);
        $sLowerPlugin = array_get($this->arData, 'replace.'.self::PREFIX_LOWER.self::CODE_PLUGIN);

        $sFolderPath = plugins_path($sLowerAuthor.'/'.$sLowerPlugin.'/lang');

        if (empty($sLowerAuthor) || empty($sLowerPlugin) || !file_exists($sFolderPath)) {
            return [];
        }

        $arLangList = scandir($sFolderPath);
        array_shift($arLangList);
        array_shift($arLangList);

        return $arLangList;
    }

    /**
     * Update lang file
     * @param array $arLangData
     */
    protected function updatePluginLang($arLangData)
    {
        if (empty($arLangData)) {
            return;
        }

        foreach ($this->getLangList() as $sLang) {
            array_set($this->arData, 'replace.lang', $sLang);

            $obUpdate = new PluginLangUpdateFile($this->arData);
            $obUpdate->update($arLangData);
        }
    }
}
