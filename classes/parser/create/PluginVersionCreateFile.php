<?php namespace Lovata\Toolbox\Classes\Parser\Create;

/**
 * Class PluginVersionCreateFile
 * @package Lovata\Toolbox\Classes\Parser\Create
 * @author Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class PluginVersionCreateFile extends CommonCreateFile
{
    /** @var string */
    protected $sFile = 'version.yaml';
    /** @var string */
    protected $sFolderPath = '/{{lower_author}}/{{lower_plugin}}/updates/';
    /** @var string */
    protected $sTemplatePath = '/lovata/toolbox/classes/parser/templates/version.stub';
}
