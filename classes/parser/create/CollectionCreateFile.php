<?php namespace Lovata\Toolbox\Classes\Parser\Create;

/**
 * Class CollectionCreateFile
 * @package Lovata\Toolbox\Classes\Parser\Create
 * @author Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class CollectionCreateFile extends CommonCreateFile
{
    /** @var string */
    protected $sFile = '{{studly_model}}Collection.php';
    /** @var string */
    protected $sFolderPath = '/{{lower_author}}/{{lower_plugin}}/classes/collection/';
    /** @var string */
    protected $sTemplatePath = '/lovata/toolbox/classes/parser/templates/collection.stub';
}
