<?php namespace Lovata\Toolbox\Classes\Parser\Create;

/**
 * Class ListStoreCreateFile
 * @package Lovata\Toolbox\Classes\Parser\Create
 * @author Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class ListStoreCreateFile extends CommonCreateFile
{
    /** @var string */
    protected $sFile = '{{studly_model}}ListStore.php';
    /** @var string */
    protected $sFolderPath = '/{{lower_author}}/{{lower_plugin}}/classes/store/';
    /** @var string */
    protected $sTemplatePath = '/lovata/toolbox/classes/parser/templates/list_store.stub';
}
