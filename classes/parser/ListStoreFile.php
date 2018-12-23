<?php namespace Lovata\Toolbox\Classes\Parser;

/**
 * Class ListStoreFile
 * @package Lovata\Toolbox\Classes\Parser
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class ListStoreFile extends CommonFile
{
    /** @var string */
    protected $sFile = '{{studly_model}}ListStore.php';
    /** @var string */
    protected $sPathFolder = '/{{lower_author}}/{{lower_plugin}}/classes/store/';
    /** @var string */
    protected $sPathTemplate = '/lovata/toolbox/classes/parser/templates/list_store.stub';
}
