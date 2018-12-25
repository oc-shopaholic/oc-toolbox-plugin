<?php namespace Lovata\Toolbox\Classes\Console;

use Lang;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Lovata\Toolbox\Traits\Console\LogoTrait;

/**
 * Class CommonCreateFile
 * @package Lovata\Toolbox\Classes\Console
 * @author Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class CommonCreateFile extends Command
{
    use LogoTrait;

    const PREFIX_LOWER   = 'lower_';
    const PREFIX_STUDLY  = 'studly_';

    const CODE_DEFAULT                    = 'Default';
    const CODE_EMPTY_FIELD                = 'empty_fields';
    const CODE_EMPTY_VALIDATE             = 'empty_validate';
    const CODE_EMPTY_ATTACH_ONE           = 'empty_attach_one';
    const CODE_EMPTY_ATTACH_MANY          = 'empty_attach_many';
    const CODE_SLUG                       = 'slug';
    const CODE_NAME                       = 'name';
    const CODE_PREVIEW_IMAGE              = 'preview_image';
    const CODE_FILE                       = 'file';
    const CODE_IMAGES                     = 'images';
    const CODE_FIELDS                     = 'fields';
    const CODE_DEVELOPER                  = 'developer';
    const CODE_AUTHOR                     = 'author';
    const CODE_EXPANSION_AUTHOR           = 'expansion_author';
    const CODE_EXPANSION_PLUGIN           = 'expansion_plugin';
    const CODE_PLUGIN                     = 'plugin';
    const CODE_MODEL                      = 'model';
    const CODE_CONTROLLER                 = 'controller';
    const CODE_LOGO                       = 'logo';
    const CODE_IMPORT_SVG                 = 'import_svg';
    const CODE_EXPORT_SVG                 = 'export_svg';
    const CODE_EMPTY_IMPORT_EXPORT_SVG    = 'empty_import_export_svg';
    const CODE_IMPORT_EXPORT_SVG          = 'import_export_svg';
    const CODE_NESTED_TREE                = 'nested_tree';
    const CODE_SORTABLE                   = 'sortable';
    const CODE_DEFAULT_SORTING            = 'default_sorting';
    const CODE_SORTING                    = 'sorting';
    const CODE_EMPTY_SORTABLE_NESTED_TREE = 'empty_sortable_nested_tree';
    const CODE_VIEW_COUNT                 = 'view_count';
    const CODE_ACTIVE                     = 'active';
    const CODE_COMMAND_PARENT             = 'command_parent';
    const CODE_ITEM                       = 'item';
    const CODE_COLLECTION                 = 'collection';
    const CODE_STORE                      = 'store';
    const CODE_COMPONENT_PAGE             = 'component page';
    const CODE_COMPONENT_DATA             = 'component data';
    const CODE_COMPONENT_LIST             = 'component list';
    const CODE_EVENT                      = 'event';
    const CODE_CREATION_MODEL_COLUMNS     = 'model columns';
    const CODE_CREATION_MODEL_FIELDS      = 'model fields';
    const CODE_CREATION_MIGRATION         = 'creation migration';

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
        'file',
        'view_count',
    ];
    /** @var array */
    protected $arInoutData = [];
    /** @var array */
    protected $arData = [
        'replace'  => [],
        'enable'   => [],
        'disable'  => [],
        'addition' => [],
        'lang'     => [],
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->initData();
        $this->setLogo();
        $this->setDeveloper();
        $this->setAuthor();
        $this->setPlugin();

        $this->call('toolbox:create.plugin', ['data' => $this->arData]);
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
     * Init data
     */
    protected function initData()
    {
        $this->arInoutData = $this->argument('data');

        if (!empty($this->arInoutData)) {
            $this->arData = $this->arInoutData;
        } else {
            $this->setDisableList();
        }
    }

    /**
     * Set logo
     */
    protected function setLogo()
    {
        if ($this->checkAdditionList(self::CODE_LOGO)) {
            return;
        }

        $this->setAdditionList(self::CODE_LOGO);
        $this->logoToolBox();
    }

    /**
     * Set developer
     */
    protected function setDeveloper()
    {
        if ($this->checkAdditionList(self::CODE_DEVELOPER)) {
            return;
        }

        $this->setAdditionList(self::CODE_DEVELOPER);
        $sDeveloper = env('DEVELOPER', '');

        if (empty($sDeveloper)) {
            return;
        }

        array_set($this->arData, 'replace.developer'.self::CODE_DEVELOPER, $sDeveloper);
    }

    /**
     * Set author
     * @param boolean $bExpansion
     */
    protected function setAuthor($bExpansion = false)
    {
        $this->setAuthorAndPlugin(
            $bExpansion,
            self::CODE_AUTHOR,
            self::CODE_EXPANSION_AUTHOR,
            'Lovata'
        );
    }

    /**
     * Set plugin
     * @param boolean $bExpansion
     */
    protected function setPlugin($bExpansion = false)
    {
        $this->setAuthorAndPlugin(
            $bExpansion,
            self::CODE_PLUGIN,
            self::CODE_EXPANSION_PLUGIN,
            'Shopaholic'
        );
    }

    /**
     * Set author and plugin
     * @param bool $bExpansion
     * @param string $sCode
     * @param string $sExpansionCode
     * @param string $sExample
     */
    protected function setAuthorAndPlugin($bExpansion, $sCode, $sExpansionCode, $sExample)
    {
        if (empty($sCode) || empty($sExpansionCode) || empty($sExample) || !is_bool($bExpansion)) {
            return;
        }

        $bCheckCreateAll = $this->checkAdditionList(self::CODE_COMMAND_PARENT);

        if (!$this->checkAdditionList($sCode) || !$bCheckCreateAll) {
            if (!$bCheckCreateAll && $bExpansion) {
                $sCode = $sExpansionCode;
            }

            $sMessage = Lang::get('lovata.toolbox::lang.message.set', [
                'name'    => $sCode,
                'example' => $sExample,
            ]);

            $sValue = $this->validationAskByName($sMessage);

            if (!$bCheckCreateAll && $bExpansion) {
                $this->setRegisterString($sValue, $sExpansionCode);

                return;
            }

            $this->setAdditionList($sCode);
            $this->setRegisterString($sValue, $sCode);
            $this->setRegisterString($sValue, $sExpansionCode);
        }
    }

    /**
     * Set model
     */
    protected function setModel()
    {
        if ($this->checkAdditionList(self::CODE_MODEL)) {
            return;
        }

        $this->setAdditionList(self::CODE_MODEL);
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
        if ($this->checkAdditionList(self::CODE_CONTROLLER)) {
            return;
        }

        $this->setAdditionList(self::CODE_CONTROLLER);
        $sMessage = Lang::get('lovata.toolbox::lang.message.set', [
            'name'    => self::CODE_CONTROLLER,
            'example' => 'Products',
        ]);

        $sController = $this->validationAskByName($sMessage);
        $this->setRegisterString($sController, self::CODE_CONTROLLER);
    }

    /**
     * Set field list
     * @param array $arException
     * @param array $arOnlyThis
     */
    protected function setFieldList($arException = [], $arOnlyThis = [])
    {
        if ($this->checkAdditionList(self::CODE_FIELDS)) {
            return;
        }

        $this->setAdditionList(self::CODE_FIELDS);
        $sMessage = Lang::get('lovata.toolbox::lang.message.choice_field_list');
        $arChoiceList = [self::CODE_DEFAULT];
        $arChoiceList = array_merge($this->arFieldList, $arChoiceList);
        $arChoiceList = $this->exceptionByList($arChoiceList, $arException, $arOnlyThis);
        $this->arFieldList = $this->choice($sMessage, $arChoiceList, null, null, true);

        if (empty($this->arFieldList) || in_array(self::CODE_DEFAULT, $this->arFieldList)) {
            return;
        }

        $bCheckName         = in_array(self::CODE_NAME, $this->arFieldList);
        $bCheckSlug         = in_array(self::CODE_SLUG, $this->arFieldList);
        $bCheckPreviewImage = in_array(self::CODE_PREVIEW_IMAGE, $this->arFieldList);
        $bCheckImages       = in_array(self::CODE_IMAGES, $this->arFieldList);
        $bCheckFile         = in_array(self::CODE_FILE, $this->arFieldList);

        $this->setEnableList(self::CODE_EMPTY_FIELD);
        $this->setEnableList($this->arFieldList);

        if ($bCheckName || $bCheckSlug) {
            $this->setEnableList(self::CODE_EMPTY_VALIDATE);
        }

        if ($bCheckPreviewImage || $bCheckFile) {
            $this->setEnableList(self::CODE_EMPTY_ATTACH_ONE);
        }

        if ($bCheckImages) {
            $this->setEnableList(self::CODE_EMPTY_ATTACH_MANY);
        }
    }

    /**
     * Set import export csv extends for model
     */
    protected function setImportExportCSV()
    {
        if ($this->checkAdditionList(self::CODE_IMPORT_EXPORT_SVG)) {
            return;
        }

        $this->setAdditionList(self::CODE_IMPORT_EXPORT_SVG);
        $sMessage = Lang::get('lovata.toolbox::lang.message.choice_extend_model');
        $arChoiceList = [
            self::CODE_MODEL,
            self::CODE_IMPORT_SVG,
            self::CODE_EXPORT_SVG,
        ];

        $sResult = $this->choice($sMessage, $arChoiceList);

        if ($sResult != self::CODE_MODEL) {
            $this->setEnableList([self::CODE_EMPTY_IMPORT_EXPORT_SVG, $sResult, self::CODE_EMPTY_ATTACH_ONE]);
        } else {
            $this->setEnableList(self::CODE_MODEL);
        }
    }

    /**
     * Set sorting
     * @param array $arException
     * @param array $arOnlyThis
     */
    protected function setSorting($arException = [], $arOnlyThis = [])
    {
        if ($this->checkAdditionList(self::CODE_SORTING)) {
            return;
        }

        $this->setAdditionList(self::CODE_SORTING);
        $sMessage = Lang::get('lovata.toolbox::lang.message.choice_sorting');
        $arChoiceList = [
            self::CODE_NESTED_TREE,
            self::CODE_SORTABLE,
            self::CODE_DEFAULT_SORTING,
            self::CODE_DEFAULT,
        ];

        $arChoiceList = $this->exceptionByList($arChoiceList, $arException, $arOnlyThis);
        $sResult = $this->choice($sMessage, $arChoiceList);

        if ($sResult == self::CODE_DEFAULT) {
            return;
        } elseif ($sResult == self::CODE_NESTED_TREE || $sResult == self::CODE_SORTABLE) {
            $this->setEnableList(self::CODE_EMPTY_SORTABLE_NESTED_TREE);
        }

        $this->setEnableList($sResult);
    }

    /**
     * Exception by list
     * @param array $arList
     * @param array $arException
     * @param array $arOnlyThis
     * @return array
     */
    protected function exceptionByList($arList = [], $arException = [], $arOnlyThis = [])
    {
        if (empty($arException) && empty($arOnlyThis)) {
            return $arList;
        } elseif (empty($arException) && !empty($arOnlyThis)) {
            return $arOnlyThis;
        }

        $arChoiceList = array_diff($arList, $arException);

        return array_values($arChoiceList);
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
        if (empty($sString) || empty($sArrayKey)) {
            return;
        }

        $sStringCase   = snake_case($sString);
        $sStringStudly = studly_case($sStringCase);
        $sStringLower  = mb_strtolower($sString);
        array_set($this->arData, 'replace.'.self::PREFIX_STUDLY.$sArrayKey, $sStringStudly);
        array_set($this->arData, 'replace.'.self::PREFIX_LOWER.$sArrayKey, $sStringLower);
    }

    /**
     * Check enable list
     * @param string $sCode
     * @return bool
     */
    protected function checkEnableList($sCode)
    {
        $arEnableList = array_get($this->arData, 'enable');

        if (!empty($sCode) && !empty($arEnableList) && is_array($arEnableList) && in_array($sCode, $arEnableList)) {
            return true;
        }

        return false;
    }

    /**
     * Check addition list
     * @param string $sCode
     * @return bool
     */
    protected function checkAdditionList($sCode)
    {
        $arAdditionList = array_get($this->arData, 'addition');

        if (!empty($sCode) && !empty($arAdditionList) && is_array($arAdditionList) && in_array($sCode, $arAdditionList)) {
            return true;
        }

        return false;
    }

    /**
     * Set disable list
     */
    protected function setDisableList()
    {
        $arDisableList = [
            self::CODE_DEVELOPER,
            self::CODE_EMPTY_FIELD,
            self::CODE_EMPTY_VALIDATE,
            self::CODE_EMPTY_ATTACH_ONE,
            self::CODE_EMPTY_ATTACH_MANY,
            self::CODE_EMPTY_IMPORT_EXPORT_SVG,
            self::CODE_IMPORT_SVG,
            self::CODE_EXPORT_SVG,
            self::CODE_MODEL,
            self::CODE_NESTED_TREE,
            self::CODE_SORTABLE,
            self::CODE_DEFAULT_SORTING,
            self::CODE_EMPTY_SORTABLE_NESTED_TREE,
        ];

        if (!empty($this->arFieldList) && is_array($this->arFieldList)) {
            $arDisableList = array_merge($arDisableList, $this->arFieldList);
        }

        array_set($this->arData, 'disable', $arDisableList);
    }

    /**
     * Set addition list
     * @param string $sValue
     */
    protected function setAdditionList($sValue)
    {
        if (!empty($sValue)) {
            $this->arData['addition'][] = $sValue;
        }
    }

    /**
     * Set enable list
     * @param string|array
     */
    protected function setEnableList($arData)
    {
        if (empty($arData)) {
            return;
        }

        $arResult = [];

        if (!is_array($arData)) {
            $arData = [$arData];
        }

        foreach ($arData as $mixData) {
            if (is_array($mixData)) {
                $arResult = array_merge($arResult, $mixData);
            }
            $arResult[] = $mixData;
        }

        $arResult = array_unique($arResult);
        $arDisableList = array_get($this->arData, 'disable');

        foreach ($arResult as $sValue) {
            $mixKey = array_search($sValue, $arDisableList);
            if (!$mixKey) {
                continue;
            }

            $sValue = $arDisableList[$mixKey];
            $this->arData['enable'][] = $sValue;
            array_forget($this->arData, 'disable.'.$mixKey);
        }
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
