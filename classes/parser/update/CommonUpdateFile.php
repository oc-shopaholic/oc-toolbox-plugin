<?php namespace Lovata\Toolbox\Classes\Parser\Update;

use October\Rain\Filesystem\Filesystem;
use Lovata\Toolbox\Traits\Parse\ParseByPatternTrait;

/**
 * Class CommonUpdateFile
 * @package Lovata\Toolbox\Classes\Parser\Update
 * @author Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class CommonUpdateFile
{
    use ParseByPatternTrait;

    /** @var object */
    protected $obFile;
    /** @var string */
    protected $sFilePath = '';
    /** @var array */
    protected $arData = [];
    /** @var string */
    protected $sContent = '';
    /** @var bool */
    protected $bSave = true;
    /** @var bool */
    protected $bUpdate = true;

    /**
     * CommonUpdateFile constructor.
     * @param array $arData
     */
    public function __construct($arData = [])
    {
        $this->arData = $arData;
        $arReplace = array_get($this->arData, 'replace');
        $sClassCreateFile = $this->classCreateFile();

        if (empty($this->arData) || empty($arReplace) || empty($this->sFilePath) || empty($sClassCreateFile)) {
            $this->bUpdate = false;

            return;
        }

        $this->obFile = new Filesystem();
        $this->sFilePath = plugins_path($this->sFilePath);
        $this->sFilePath = $this->parseByName($arReplace, $this->sFilePath);

        if (!$this->obFile->exists($this->sFilePath)) {
            $obFile = new $sClassCreateFile($this->arData);
            $obFile->create(true);
        }
    }

    /**
     * Class create file
     * @return string
     */
    protected function classCreateFile()
    {
        return '';
    }
}
