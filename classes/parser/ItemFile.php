<?php namespace Lovata\Toolbox\Classes\Parser;

/**
 * Class ItemFile
 * @package Lovata\Toolbox\Classes\Parser
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class ItemFile extends CommonFile
{
    /** @var string */
    protected $sFile = '{{studly_model}}Item.php';
    /** @var string */
    protected $sPathFolder = '/{{lower_author}}/{{lower_plugin}}/classes/item/';
    /** @var string */
    protected $sPathTemplate = '/lovata/toolbox/classes/parser/templates/item.stub';
}
