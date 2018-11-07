<?php namespace Lovata\Toolbox\Classes\Parser;

/**
 * Class ModelFieldFile
 * @package Lovata\Toolbox\Classes\Parser
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class ModelFieldFile extends CommonFile
{
    /** @var string */
    protected $sFile = 'fields.yaml';
    /** @var string */
    protected $sPathFolder = '/{{lower_author}}/{{lower_plugin}}/models/{{lower_model}}/';
    /** @var string */
    protected $sPathTemplate = '/lovata/toolbox/classes/parser/templates/fields.stub';

    /**
     * Create file
     * @param bool $bForce
     * @return null|string
     */
    public function create($bForce = false)
    {
        if (!$this->bForce && !$bForce) {
            return $this->sPathFile;
        }

        $arReplace = array_get($this->arData , 'replace');
        $arEnableFieldList = array_get($this->arData , 'enable');
        $arDisableFieldList = array_get($this->arData , 'disable');

        if (!empty($arDisableFieldList)) {
            $this->sContent = $this->parseByWrapper($arDisableFieldList, $this->sContent);
        }

        if (!empty($arReplace)) {
            $this->sContent = $this->parseByName($arReplace, $this->sContent);
        }

        if (!empty($arEnableFieldList)) {
            $this->sContent = $this->parseByNameWrapper($arEnableFieldList, $this->sContent);
        }

        if ($bForce || $this->bForce) {
            $this->obFile->put($this->sPathFile, $this->sContent);
        }

        return null;
    }
}