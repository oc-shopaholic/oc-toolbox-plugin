<?php namespace Lovata\Toolbox\Classes\Parser\Create;

/**
 * Class ControllerConfirmListCreateFile
 * @package Lovata\Toolbox\Classes\Parser\Create
 * @author Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class ControllerConfirmListCreateFile extends CommonCreateFile
{
    /** @var string */
    protected $sFile = 'config_list.yaml';
    /** @var string */
    protected $sFolderPath = '/{{lower_author}}/{{lower_plugin}}/controllers/{{lower_controller}}/';
    /** @var string */
    protected $sTemplatePath = '/lovata/toolbox/classes/parser/templates/controller_config_list.stub';
}
