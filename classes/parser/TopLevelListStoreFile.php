<?php namespace Lovata\Toolbox\Classes\Parser;

/**
 * Class TopLevelListStoreFile
 * @package Lovata\Toolbox\Classes\Parser
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class TopLevelListStoreFile extends CommonFile
{
    /** @var string */
    protected $sFile = 'TopLevelListStore.php';
    /** @var string */
    protected $sPathFolder = '/{{lower_author}}/{{lower_plugin}}/classes/store/{{lower_model}}/';
    /** @var string */
    protected $sPathTemplate = '/lovata/toolbox/classes/parser/templates/sorting_top_level_list_store.stub';
}
