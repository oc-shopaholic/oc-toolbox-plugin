<?php namespace Lovata\Toolbox\Classes\Parser;

/**
 * Class PluginLangENFile
 * @package Lovata\Toolbox\Classes\Parser
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class PluginLangENFile extends CommonFile
{
    /** @var string */
    protected $sFile = 'lang.php';
    /** @var string */
    protected $sPathFolder = '/{{lower_author}}/{{lower_plugin}}/lang/en/';
    /** @var string */
    protected $sPathTemplate = '/lovata/toolbox/classes/parser/templates/lang.stub';

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

        if (!empty($arReplace)) {
            $this->sContent = $this->parseByName($arReplace, $this->sContent);
        }

        if ($bForce || $this->bForce) {
            $this->obFile->put($this->sPathFile, $this->sContent);
        }

        return null;
    }
}