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

    const CODE_DEVELOPER          = 'developer';
    const CODE_AUTHOR             = 'author';
    const CODE_PLUGIN             = 'plugin';
    const CODE_MODEL              = 'model';
    const CODE_CONTROLLER         = 'controller';
    const CODE_CREATION_MIGRATION = 'creation migration';
    const CODE_EMPTY_FIELD        = 'empty_fields';
    const CODE_SET_NAME           = 'Set developer';
    const CODE_DEFAULT            = 'Default';

    const PREFIX_LOWER   = 'lower_';
    const PREFIX_STUDLY  = 'studly_';

    /** @var array */
    protected $arDeveloperList = [
        [
            'name'      => 'Andrey',
            'last_name' => 'Kharanenka',
            'email'     => 'a.khoronenko@lovata.com',
        ],
        [
            'name'      => 'Sergey',
            'last_name' => 'Zakharevich',
            'email'     => 's.zakharevich@lovata.com',
        ],
    ];
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
    protected $arData = [
        'replace'  => [],
        'enable'   => [],
        'disable'  => [],
        'addition' => [],
    ];

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
     * Choice developer
     */
    protected function choiceDeveloper()
    {
        $arChoiceList = [self::CODE_SET_NAME];

        foreach ($this->arDeveloperList as $arDeveloper) {
            $arDeveloper[] = self::GROUP_NAME;
            $arChoiceList[] = implode(' ', $arDeveloper);
        }

        $sMessage = Lang::get('lovata.toolbox::lang.message.choice_developer');
        $sDeveloper = $this->choice($sMessage, $arChoiceList);

        if ($sDeveloper == self::CODE_SET_NAME) {
            $sMessage = Lang::get('lovata.toolbox::lang.message.set_developer');
            $sDeveloper = $this->ask($sMessage);
        }

        array_set($this->arData, 'replace.' . self::CODE_DEVELOPER, $sDeveloper);
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
}