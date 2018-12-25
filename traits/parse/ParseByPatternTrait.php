<?php namespace Lovata\Toolbox\Traits\Parse;

/**
 * Trait ParseByPatternTrait
 * @package Lovata\Toolbox\Traits\Parse
 * @author Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
trait ParseByPatternTrait
{
    /**
     * Parse content by name
     * @param array $arNameList
     * @param string $sContent
     * @return string|null
     */
    public function parseByName($arNameList, $sContent)
    {
        if (empty($arNameList) || !is_array($arNameList) || empty($sContent)) {
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
    public function parseByNameWrapper($arNameList, $sContent)
    {
        if (empty($arNameList) || !is_array($arNameList) || empty($sContent)) {
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
    public function parseByWrapper($arNameList, $sContent)
    {
        if (empty($arNameList) || !is_array($arNameList) || empty($sContent)) {
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
    public function namePattern($sKey)
    {
        return '{{'.$sKey.'}}';
    }

    /**
     * Name wrapper pattern. Example: [[key]]
     * @param string $sKey
     * @return string
     */
    public function nameWrapperPattern($sKey)
    {
        return '/\[\['.$sKey.'\]\]/';
    }

    /**
     * Wrapper pattern. Example: [[key]]...[[key]]
     * @param string $sKey
     * @return string
     */
    public function wrapperPattern($sKey)
    {
        return "[\[\[".$sKey."\]\][A-Za-z0-9\t\n\r\f\v\x20-\x7E]+?\[\[".$sKey."\]\]]";
    }

    /**
     * Parse array to string file
     * @param array $arData
     * @return string
     */
    public function arrayToStringFile($arData)
    {
        if (empty($arData) || !is_array($arData)) {
            return '';
        }

        $sContent = var_export($arData, true);
        $sContent = preg_replace("/(\\n[ ]+array[ , \\n]+\(\\n)/", "[\n", $sContent);
        $sContent = preg_replace("/(array[ , \\n]+\(\\n)/", "[\n", $sContent);
        $sContent = preg_replace("/\)\,/", "],", $sContent);
        $sContent = preg_replace("/\)$/", "];", $sContent);
        $sContent = '<?php return '.$sContent;
        $sContent = preg_replace("/\\n  /", "\n    ", $sContent);
        $sContent = preg_replace("/\\n      /", "\n        ", $sContent);
        $sContent = preg_replace("/\[[ ,\n]+ \]/", "[]", $sContent);

        return $sContent;
    }
}
