<?php namespace Lovata\Toolbox\Classes\Parser\Create;

/**
 * Class ControllerConfirmFilterCreateFile
 * @package Lovata\Toolbox\Classes\Parser\Create
 * @author Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class ControllerConfirmFilterCreateFile extends CommonCreateFile
{
    /** @var string */
    protected $sFile = '_config_filter.yaml';
    /** @var string */
    protected $sFolderPath = '/{{lower_author}}/{{lower_plugin}}/controllers/{{lower_controller}}/';
    /** @var string */
    protected $sTemplatePath = '/lovata/toolbox/classes/parser/templates/controller_config_filter.stub';
}
