<?php namespace Lovata\Toolbox\Traits\Helpers;

use System\Classes\PluginManager;

/**
 * Class TraitInitActiveLang
 * @package Lovata\Toolbox\Traits\Helpers
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
trait TraitInitActiveLang
{
    /** @var bool - Flag, Translate plugin data was init */
    protected static $bLangInit = false;

    /** @var string - Active lang code from Translate plugin */
    protected static $sActiveLang = null;

    /** @var string - Default lang code from Translate plugin */
    protected static $sDefaultLang = null;

    /** @var array Active lang list from Translate plugin */
    protected static $arActiveLangList = null;

    /**
     * Get and save active lang list
     */
    protected function getActiveLangList()
    {
        if (self::$arActiveLangList !== null || !PluginManager::instance()->hasPlugin('RainLab.Translate')) {
            return self::$arActiveLangList;
        }

        self::$arActiveLangList = \RainLab\Translate\Models\Locale::isEnabled()->pluck('code')->all();
        if (empty(self::$arActiveLangList)) {
            return self::$arActiveLangList;
        }

        //Remove default lang from list
        foreach (self::$arActiveLangList as $iKey => $sLangCode) {
            if ($sLangCode == self::$sDefaultLang) {
                unset(self::$arActiveLangList[$iKey]);
                break;
            }
        }

        return self::$arActiveLangList;
    }

    /**
     * Get and save active lang from Translate plugin
     */
    protected function initActiveLang()
    {
        if (self::$bLangInit || !PluginManager::instance()->hasPlugin('RainLab.Translate')) {
            return;
        }

        self::$bLangInit = true;
        $obTranslate = \RainLab\Translate\Classes\Translator::instance();

        self::$sDefaultLang = $obTranslate->getDefaultLocale();

        $sActiveLangCode = $obTranslate->getLocale();
        if (empty($sActiveLangCode) || $obTranslate->getDefaultLocale() == $sActiveLangCode) {
            return;
        }

        self::$sActiveLang = $sActiveLangCode;
    }

    /**
     * Add suffix with active lang code
     * @param string $sValue
     * @param string $sSeparator
     *
     * @return string
     */
    protected function addActiveLangSuffix($sValue, $sSeparator = '_')
    {
        if (empty(self::$sActiveLang)) {
            return $sValue;
        }

        return $sValue.$sSeparator.self::$sActiveLang;
    }

    /**
     * Add prefix with active lang code
     * @param string $sValue
     * @param string $sSeparator
     *
     * @return string
     */
    protected function addActiveLangPrefix($sValue, $sSeparator = '_')
    {
        if (empty(self::$sActiveLang)) {
            return $sValue;
        }

        return self::$sActiveLang.$sSeparator.$sValue;
    }
}
