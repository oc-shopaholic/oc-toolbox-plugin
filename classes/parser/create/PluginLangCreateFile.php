<?php namespace Lovata\Toolbox\Classes\Parser\Create;

/**
 * Class PluginLangCreateFile
 * @package Lovata\Toolbox\Classes\Parser\Create
 * @author Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class PluginLangCreateFile extends CommonCreateFile
{
    /** @var string */
    protected $sFile = 'lang.php';
    /** @var string */
    protected $sFolderPath = '/{{lower_author}}/{{lower_plugin}}/lang/{{lang}}/';
    /** @var string */
    protected $sTemplatePath = '/lovata/toolbox/classes/parser/templates/lang.stub';
}
