<?php namespace Lovata\Toolbox\Classes\Parser\Create;

/**
 * Class PluginYAMLCreateFile
 * @package Lovata\Toolbox\Classes\Parser\Create
 * @author Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class PluginYAMLCreateFile extends CommonCreateFile
{
    /** @var string */
    protected $sFile = 'plugin.yaml';
    /** @var string */
    protected $sFolderPath = '/{{lower_author}}/{{lower_plugin}}/';
    /** @var string */
    protected $sTemplatePath = '/lovata/toolbox/classes/parser/templates/plugin_yaml.stub';
}
