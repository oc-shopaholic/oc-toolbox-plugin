<?php namespace Lovata\Toolbox\Classes\Parser;

/**
 * Class CollectionFile
 * @package Lovata\Toolbox\Classes\Parser
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class CollectionFile extends CommonFile
{
    /** @var string */
    protected $sFile = '{{studly_model}}Collection.php';
    /** @var string */
    protected $sPathFolder = '/{{lower_author}}/{{lower_plugin}}/classes/collection/';
    /** @var string */
    protected $sPathTemplate = '/lovata/toolbox/classes/parser/templates/collection.stub';
}
