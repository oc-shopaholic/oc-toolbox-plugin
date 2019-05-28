<?php namespace Lovata\Toolbox\Classes\Parser\Create;

/**
 * Class SortingListStoreCreateFile
 * @package Lovata\Toolbox\Classes\Parser\Create
 * @author Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class SortingListStoreCreateFile extends CommonCreateFile
{
    /** @var string */
    protected $sFile = 'SortingListStore.php';
    /** @var string */
    protected $sFolderPath = '/{{lower_author}}/{{lower_plugin}}/classes/store/{{lower_model}}/';
    /** @var string */
    protected $sTemplatePath = '/lovata/toolbox/classes/parser/templates/sorting_list_store.stub';
}
