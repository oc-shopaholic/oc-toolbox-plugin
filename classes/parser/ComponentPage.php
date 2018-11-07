<?php namespace Lovata\Toolbox\Classes\Parser;

/**
 * Class ComponentPage
 * @package Lovata\Toolbox\Classes\Parser
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class ComponentPage extends CommonFile
{
    /** @var string */
    protected $sFile = '{{studly_model}}Page.php';
    /** @var string */
    protected $sPathFolder = '/{{lower_author}}/{{lower_plugin}}/components/';
    /** @var string */
    protected $sPathTemplate = '/lovata/toolbox/classes/parser/templates/component_page.stub';

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