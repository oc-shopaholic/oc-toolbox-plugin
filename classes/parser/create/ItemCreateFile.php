<?php namespace Lovata\Toolbox\Classes\Parser\Create;

/**
 * Class ItemCreateFile
 * @package Lovata\Toolbox\Classes\Parser\Create
 * @author Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class ItemCreateFile extends CommonCreateFile
{
    /** @var string */
    protected $sFile = '{{studly_model}}Item.php';
    /** @var string */
    protected $sFolderPath = '/{{lower_author}}/{{lower_plugin}}/classes/item/';
    /** @var string */
    protected $sTemplatePath = '/lovata/toolbox/classes/parser/templates/item.stub';
}
