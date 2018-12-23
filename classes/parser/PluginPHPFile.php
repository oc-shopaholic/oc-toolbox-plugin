<?php namespace Lovata\Toolbox\Classes\Parser;

/**
 * Class PluginPHPFile
 * @package Lovata\Toolbox\Classes\Parser
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class PluginPHPFile extends CommonFile
{
    /** @var string */
    protected $sFile = 'Plugin.php';
    /** @var string */
    protected $sPathFolder = '/{{lower_author}}/{{lower_plugin}}/';
    /** @var string */
    protected $sPathTemplate = '/lovata/toolbox/classes/parser/templates/plugin_php.stub';
}
