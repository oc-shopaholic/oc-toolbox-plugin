<?php namespace Lovata\Toolbox\Classes\Parser\Create;

/**
 * Class PluginPHPCreateFile
 * @package Lovata\Toolbox\Classes\Parser\Create
 * @author Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class PluginPHPCreateFile extends CommonCreateFile
{
    /** @var string */
    protected $sFile = 'Plugin.php';
    /** @var string */
    protected $sFolderPath = '/{{lower_author}}/{{lower_plugin}}/';
    /** @var string */
    protected $sTemplatePath = '/lovata/toolbox/classes/parser/templates/plugin_php.stub';
}
