<?php namespace Lovata\Toolbox\Classes\Parser\Create;

/**
 * Class ActiveListStoreCreateFile
 * @package Lovata\Toolbox\Classes\Parser\Create\Create
 * @author Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class ActiveListStoreCreateFile extends CommonCreateFile
{
    /** @var string */
    protected $sFile = 'ActiveListStore.php';
    /** @var string */
    protected $sFolderPath = '/{{lower_author}}/{{lower_plugin}}/classes/store/{{lower_model}}/';
    /** @var string */
    protected $sTemplatePath = '/lovata/toolbox/classes/parser/templates/active_list_store.stub';
}
