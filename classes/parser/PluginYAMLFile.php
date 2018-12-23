<?php namespace Lovata\Toolbox\Classes\Parser;

/**
 * Class PluginYAMLFile
 * @package Lovata\Toolbox\Classes\Parser
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class PluginYAMLFile extends CommonFile
{
    /** @var string */
    protected $sFile = 'plugin.yaml';
    /** @var string */
    protected $sPathFolder = '/{{lower_author}}/{{lower_plugin}}/';
    /** @var string */
    protected $sPathTemplate = '/lovata/toolbox/classes/parser/templates/plugin_yaml.stub';
}
