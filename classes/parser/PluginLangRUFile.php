<?php namespace Lovata\Toolbox\Classes\Parser;

/**
 * Class PluginLangRUFile
 * @package Lovata\Toolbox\Classes\Parser
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class PluginLangRUFile extends CommonFile
{
    /** @var string */
    protected $sFile = 'lang.php';
    /** @var string */
    protected $sPathFolder = '/{{lower_author}}/{{lower_plugin}}/lang/ru/';
    /** @var string */
    protected $sPathTemplate = '/lovata/toolbox/classes/parser/templates/lang.stub';
}
