<?php namespace Lovata\Toolbox\Classes\Parser\Create;

/**
 * Class TopLevelListStoreCreateFile
 * @package Lovata\Toolbox\Classes\Parser\Create
 * @author Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class TopLevelListStoreCreateFile extends CommonCreateFile
{
    /** @var string */
    protected $sFile = 'TopLevelListStore.php';
    /** @var string */
    protected $sFolderPath = '/{{lower_author}}/{{lower_plugin}}/classes/store/{{lower_model}}/';
    /** @var string */
    protected $sTemplatePath = '/lovata/toolbox/classes/parser/templates/sorting_top_level_list_store.stub';
}
