<?php namespace Lovata\Toolbox\Classes\Parser\Create;

/**
 * Class ControllerCreateFile
 * @package Lovata\Toolbox\Classes\Parser\Create
 * @author Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class ControllerCreateFile extends CommonCreateFile
{
    /** @var string */
    protected $sFile = '{{studly_controller}}.php';
    /** @var string */
    protected $sFolderPath = '/{{lower_author}}/{{lower_plugin}}/controllers/';
    /** @var string */
    protected $sTemplatePath = '/lovata/toolbox/classes/parser/templates/controller.stub';
}
