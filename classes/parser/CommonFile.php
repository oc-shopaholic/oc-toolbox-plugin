<?php namespace Lovata\Toolbox\Classes\Parser;

use October\Rain\Filesystem\Filesystem;

/**
 * Class CommonFile
 * @package Lovata\Toolbox\Classes\Parser
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class CommonFile
{
    /** @var object */
    protected $obFile;
    /** @var string */
    protected $sFile = '';
    /** @var string */
    protected $sPathFolder = '';
    /** @var string */
    protected $sPathFile = '';
    /** @var string */
    protected $sPathTemplate = '';
    /** @var array */
    protected $arData = [];
    /** @var string */
    protected $sContent = '';
    /** @var boolean */
    protected $bForce = false;

    /**
     * CommonFile constructor.
     * @param array $arData
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function __construct($arData)
    {
        if (empty($arData) || empty($this->sPathFolder) || empty($this->sFile) || empty($this->sPathTemplate)) {
            return;
        }

        $this->arData = $arData;

        if (empty($this->arData['replace'])) {
            return;
        }

        $this->sPathFolder = plugins_path() . $this->parseByName($this->arData['replace'], $this->sPathFolder);
        $this->sFile = $this->parseByName($this->arData['replace'], $this->sFile);
        $this->sPathFile = $this->sPathFolder . $this->sFile;

        $this->obFile = new Filesystem();

        $this->sContent = $this->obFile->get(plugins_path() . $this->sPathTemplate);

        if (!$this->obFile->exists($this->sPathFolder)) {
            $this->obFile->makeDirectory($this->sPathFolder, 0777, true, true);
        }

        if (!$this->obFile->exists($this->sPathFile)) {
            $this->bForce = true;
        }
    }

    /**
     * Parse content by name
     * @param array $arNameList
     * @param string $sContent
     * @return string|null
     */
    protected function parseByName($arNameList, $sContent)
    {
        if (empty($arNameList) || empty($sContent)) {
            return '';
        }

        foreach ($arNameList as $sKey => $sName) {
            $sPattern = $this->namePattern($sKey);
            $sContent = str_replace($sPattern, $sName, $sContent);
        }

        return $sContent;
    }

    /**
     * Parse content by name wrapper
     * @param array $arNameList
     * @param string $sContent
     * @return string
     */
    protected function parseByNameWrapper($arNameList, $sContent)
    {
        if (empty($arNameList) || empty($sContent)) {
            return '';
        }

        foreach ($arNameList as $sName) {
            $sPattern = $this->nameWrapperPattern($sName);
            $sContent = preg_replace($sPattern, '', $sContent);
        }

        return $sContent;
    }

    /**
     * Parse content by wrapper
     * @param array $arNameList
     * @param string $sContent
     * @return string
     */
    protected function parseByWrapper($arNameList, $sContent)
    {
        if (empty($arNameList) || empty($sContent)) {
            return '';
        }

        foreach ($arNameList as $sName) {
            $sPattern = $this->wrapperPattern($sName);
            $sContent = preg_replace($sPattern, '', $sContent);
        }

        return $sContent;
    }

    /**
     * Name pattern. Example: {{key}}
     * @param string $sKey
     * @return string
     */
    protected function namePattern($sKey)
    {
        return '{{' . $sKey . '}}';
    }

    /**
     * Name wrapper pattern. Example: [[key]]
     * @param string $sKey
     * @return string
     */
    protected function nameWrapperPattern($sKey)
    {
        return '/\[\[' . $sKey . '\]\]/';
    }

    /**
     * Wrapper pattern. Example: [[key]]...[[key]]
     * @param string $sKey
     * @return string
     */
    protected function wrapperPattern($sKey)
    {
        return "[\[\[" . $sKey . "\]\][A-Za-z0-9\t\n\r\f\v\x20-\x7E]+?\[\[" . $sKey . "\]\]]";
    }
}