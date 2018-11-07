<?php namespace Lovata\Toolbox\Classes\Console;

use Lang;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Lovata\Toolbox\Traits\Console\Logo;

/**
 * Class CommonCreateFile
 * @package Lovata\Toolbox\Classes\Console
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class CommonCreateFile extends Command
{
    use Logo;

    const GROUP_NAME = 'LOVATA Group';

    const CODE_DEVELOPER              = 'developer';
    const CODE_AUTHOR                 = 'author';
    const CODE_PLUGIN                 = 'plugin';
    const CODE_MODEL                  = 'model';
    const CODE_CONTROLLER             = 'controller';
    const CODE_ITEM                   = 'item';
    const CODE_COLLECTION             = 'collection';
    const CODE_STORE                  = 'store';
    const CODE_COMPONENT_PAGE         = 'component page';
    const CODE_COMPONENT_DATA         = 'component data';
    const CODE_COMPONENT_LIST         = 'component list';
    const CODE_EVENT                  = 'event';
    const CODE_CREATION_MIGRATION     = 'creation migration';
    const CODE_CREATION_MODEL_COLUMNS = 'model columns';
    const CODE_CREATION_MODEL_FIELDS  = 'model fields';
    const CODE_EMPTY_FIELD            = 'empty_fields';
    const CODE_SORT                   = 'sort';
    const CODE_ACTIVE                 = 'active';
    const CODE_FIELDS                 = 'fields';
    const CODE_EMPTY_DEVELOPER        = 'empty_developer';
    const CODE_SET_NAME               = 'Set developer';
    const CODE_DEFAULT                = 'Default';

    const PREFIX_LOWER   = 'lower_';
    const PREFIX_STUDLY  = 'studly_';

    /** @var array */
    protected $arFieldList = [
        'active',
        'name',
        'slug',
        'code',
        'external_id',
        'preview_text',
        'description',
        'preview_image',
        'images',
    ];
    /** @var array */
    protected $arInoutData = [];
    /** @var array */
    protected $arData = [
        'replace'  => [],
        'enable'   => [],
        'disable'  => [],
        'addition' => [],
    ];

    /**
     * CommonCreateFile constructor.
     */
    public function __construct()
    {
        parent::__construct();

        array_set($this->arData, 'replace.developer', env('DEVELOPER', ''));
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->arInoutData = $this->argument('data');

        if (!empty($this->arInoutData)) {
            $this->arData = $this->arInoutData;
        }
    }

    /**
     * Get the console command arguments.
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['data', InputArgument::OPTIONAL],
        ];
    }

    /**
     * Choice field list
     * @param array $arException
     */
    protected function choiceFieldList($arException = [])
    {
        if (!empty($arException)) {
            $this->arFieldList = array_diff($this->arFieldList, $arException);
        }

        $sMessage = Lang::get('lovata.toolbox::lang.message.choice_field_list');
        $this->choiceByList($this->arFieldList, $sMessage);

        $arEnableList = array_get($this->arData, 'enable');

        if (empty($arEnableList)) {
            $this->arData['disable'][] = self::CODE_EMPTY_FIELD;
        }

        foreach ($this->arFieldList as $sField) {
            if (in_array($sField, $arEnableList)) {
                $this->arData['addition'][] = self::CODE_EMPTY_FIELD;
            }
        }

        $this->arData['enable'][] = self::CODE_EMPTY_FIELD;
    }

    /**
     * Choice by list
     * @param array $arList
     * @param string $sMessage
     */
    protected function choiceByList($arList, $sMessage)
    {
        if (empty($arList) || empty($sMessage)) {
            return;
        }

        $sMessage = '<info>' . $sMessage . '</info>';
        $arChoice = [$sMessage];

        foreach ($arList as $sKey => $sField) {
            $arChoice[] = '[<info>' . $sKey . '</info>] ' . $sField;
        }

        $this->output->writeln($arChoice);

        $sKeyList = (string) $this->ask('', self::CODE_DEFAULT);

        if (!preg_match('/(^([0-9]+\,)+([0-9]+)$)|(^([0-9]+)$)/', $sKeyList) && $sKeyList != self::CODE_DEFAULT) {
            $this->choiceByList($arList, $sMessage);
        }

        $this->processingListByAnswer($sKeyList, $arList);
    }

    /**
     * Processing list by answer
     * @param string $sKeyList
     * @param array $arValueList
     */
    protected function processingListByAnswer($sKeyList, $arValueList)
    {
        if (empty($arValueList)) {
            return;
        }

        $arKeyList = explode(',', $sKeyList);
        $arKeyList = array_unique($arKeyList);

        $arEnableList  = [];

        foreach ($arKeyList as $iKey) {

            $sValue = array_get($arValueList, $iKey);

            if (!empty($sValue)) {
                $arEnableList[] = $sValue;
            }
        }

        $arDisableList = array_diff($arValueList, $arEnableList);
        array_set($this->arData, 'enable', $arEnableList);
        array_set($this->arData, 'disable', $arDisableList);
    }

    /**
     * Set author
     */
    protected function setAuthor()
    {
        $sMessage = Lang::get('lovata.toolbox::lang.message.set', [
            'name'    => self::CODE_AUTHOR,
            'example' => 'Lovata',
        ]);

        $sAuthor = $this->validationAskByName($sMessage);
        $this->setRegisterString($sAuthor, self::CODE_AUTHOR);
    }

    /**
     * Set plugin
     */
    protected function setPlugin()
    {
        $sMessage = Lang::get('lovata.toolbox::lang.message.set', [
            'name'    => self::CODE_PLUGIN,
            'example' => 'Shopaholic',
        ]);

        $sPlugin = $this->validationAskByName($sMessage);
        $this->setRegisterString($sPlugin, self::CODE_PLUGIN);
    }

    /**
     * Set model
     */
    protected function setModel()
    {
        $sMessage = Lang::get('lovata.toolbox::lang.message.set', [
            'name'    => self::CODE_MODEL,
            'example' => 'Product',
        ]);

        $sModel = $this->validationAskByName($sMessage);
        $this->setRegisterString($sModel, self::CODE_MODEL);
    }

    /**
     * Set controller
     */
    protected function setController()
    {
        $sMessage = Lang::get('lovata.toolbox::lang.message.set', [
            'name'    => self::CODE_CONTROLLER,
            'example' => 'Products',
        ]);

        $sController = $this->validationAskByName($sMessage);
        $this->setRegisterString($sController, self::CODE_CONTROLLER);
    }

    /**
     * Validation answer to a question by name
     * @param string $sMessage
     * @return string
     */
    protected function validationAskByName($sMessage)
    {
        $sResult = $this->ask($sMessage);

        if (!preg_match("/^[a-zA-Z]+$/", $sResult)) {
            $sResult = $this->validationAskByName($sMessage);
        }

        return $sResult;
    }

    /**
     * Set register string for $arData
     * @param string $sString
     * @param string $sArrayKey
     */
    protected function setRegisterString($sString, $sArrayKey)
    {
        $sStringCase   = snake_case($sString);
        $sStringStudly = studly_case($sStringCase);
        $sStringLower  = mb_strtolower($sString);
        array_set($this->arData, 'replace.' . self::PREFIX_STUDLY . $sArrayKey, $sStringStudly);
        array_set($this->arData, 'replace.' . self::PREFIX_LOWER . $sArrayKey, $sStringLower);
    }

    /**
     * Create file
     * @param string $sClass
     */
    protected function createFile($sClass)
    {
        if (empty($sClass)) {
            return;
        }

        $obFile = new $sClass($this->arData);
        $sFile = $obFile->create();
        if (!empty($sFile)) {
            $sMessage = Lang::get('lovata.toolbox::lang.message.force_file', ['file' => $sFile]);
            if ($this->confirm($sMessage, true)) {
                $obFile->create(true);
            }
        }
    }

    /**
     * Check addition config
     * @param string $sCode
     * @return bool
     */
    protected function checkAddition($sCode)
    {
        $arAdditionList = array_get($this->arData, 'addition');

        if (!empty($sCode) && !empty($arAdditionList) && in_array($sCode, $arAdditionList)) {
            return true;
        }

        return false;
    }

    /**
     * Set additional list
     */
    protected function setAdditionalList()
    {
        $arEnableList  = array_get($this->arData, 'enable');
        $arDisableList = array_get($this->arData, 'disable');

        if (in_array(self::CODE_ACTIVE, $arEnableList) || in_array(self::CODE_ACTIVE, $arDisableList)) {
            $this->arData['addition'][] = self::CODE_ACTIVE;
        }

        if (in_array(self::CODE_SORT, $arEnableList) || in_array(self::CODE_SORT, $arDisableList)) {
            $this->arData['addition'][] = self::CODE_SORT;
        }

        if (in_array(self::CODE_EMPTY_FIELD, $arEnableList) || in_array(self::CODE_EMPTY_FIELD, $arDisableList)) {
            $this->arData['addition'][] = self::CODE_EMPTY_FIELD;
        }
    }
}