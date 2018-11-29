<?php namespace Lovata\Toolbox\Classes\Parser;

/**
 * Class ExtendBackendMenuHandlerFile
 * @package Lovata\Toolbox\Classes\Parser
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class ExtendBackendMenuHandlerFile extends CommonFile
{
    /** @var string */
    protected $sFile = 'ExtendBackendMenuHandler.php';
    /** @var string */
    protected $sPathFolder = '/{{lower_author}}/{{lower_plugin}}/classes/event/';
    /** @var string */
    protected $sPathTemplate = '/lovata/toolbox/classes/parser/templates/extend_backend_menu_handler.stub';

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