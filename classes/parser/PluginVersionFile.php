<?php namespace Lovata\Toolbox\Classes\Parser;

/**
 * Class PluginVersionFile
 * @package Lovata\Toolbox\Classes\Parser
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class PluginVersionFile extends CommonFile
{
    /** @var string */
    protected $sFile = 'version.yaml';
    /** @var string */
    protected $sPathFolder = '/{{lower_author}}/{{lower_plugin}}/updates/';
    /** @var string */
    protected $sPathTemplate = '/lovata/toolbox/classes/parser/templates/version.stub';
}
