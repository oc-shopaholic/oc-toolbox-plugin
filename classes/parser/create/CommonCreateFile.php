<?php namespace Lovata\Toolbox\Classes\Parser\Create;

use October\Rain\Filesystem\Filesystem;
use Lovata\Toolbox\Traits\Parse\ParseByPatternTrait;

/**
 * Class CommonFile
 * @package Lovata\Toolbox\Classes\Parser\Create
 * @author Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class CommonCreateFile
{
    use ParseByPatternTrait;

    /** @var object */
    protected $obFile;
    /** @var string */
    protected $sFile;
    /** @var string */
    protected $sFolderPath;
    /** @var string */
    protected $sFilePath;
    /** @var string */
    protected $sTemplatePath;
    /** @var string */
    protected $sContent;
    /** @var bool */
    protected $bForce = false;
    /** @var bool */
    protected $bCreate = true;
    /** @var array */
    protected $arReplaceList = [];
    /** @var array */
    protected $arEnableList = [];
    /** @var array */
    protected $arDisableList = [];

    /**
     * CommonCreateFile constructor.
     * @param array $arData
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function __construct($arData)
    {
        $this->arReplaceList = array_get($arData, 'replace');
        $this->arEnableList  = array_get($arData, 'enable');
        $this->arDisableList = array_get($arData, 'disable');

        if (empty($this->arReplaceList) || empty($this->sFolderPath) || empty($this->sFile) || empty($this->sTemplatePath)) {
            $this->bCreate = false;

            return;
        }

        $this->sFolderPath = plugins_path().$this->parseByName($this->arReplaceList, $this->sFolderPath);
        $this->sFile = $this->parseByName($this->arReplaceList, $this->sFile);
        $this->sFilePath = $this->sFolderPath.$this->sFile;
        $this->obFile = new Filesystem();

        if (!$this->obFile->exists($this->sFolderPath)) {
            $this->obFile->makeDirectory($this->sFolderPath, 0777, true, true);
        }

        $this->sContent = $this->obFile->get(plugins_path().$this->sTemplatePath);

        if (!$this->obFile->exists($this->sFilePath)) {
            $this->bForce = true;
        }
    }

    /**
     * Create file
     * @param bool $bForce
     * @return null|string
     */
    public function create($bForce = false)
    {
        if (!$this->bForce && !$bForce || !$this->bCreate) {
            return $this->sFilePath;
        }

        if (!empty($this->arDisableList)) {
            $this->sContent = $this->parseByWrapper($this->arDisableList, $this->sContent);
        }

        if (!empty($this->arEnableList)) {
            $this->sContent = $this->parseByNameWrapper($this->arEnableList, $this->sContent);
        }

        if (!empty($this->arReplaceList)) {
            $this->sContent = $this->parseByName($this->arReplaceList, $this->sContent);
        }

        if ($bForce || $this->bForce) {
            $this->obFile->put($this->sFilePath, $this->sContent);
        }

        return null;
    }
}
