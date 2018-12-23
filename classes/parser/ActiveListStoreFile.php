<?php namespace Lovata\Toolbox\Classes\Parser;

/**
 * Class ActiveListStoreFile
 * @package Lovata\Toolbox\Classes\Parser
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class ActiveListStoreFile extends CommonFile
{
    /** @var string */
    protected $sFile = 'ActiveListStore.php';
    /** @var string */
    protected $sPathFolder = '/{{lower_author}}/{{lower_plugin}}/classes/store/{{lower_model}}/';
    /** @var string */
    protected $sPathTemplate = '/lovata/toolbox/classes/parser/templates/active_list_store.stub';
}
