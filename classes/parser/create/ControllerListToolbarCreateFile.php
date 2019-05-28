<?php namespace Lovata\Toolbox\Classes\Parser\Create;

/**
 * Class ControllerListToolbarCreateFile
 * @package Lovata\Toolbox\Classes\Parser\Create
 * @author Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class ControllerListToolbarCreateFile extends CommonCreateFile
{
    /** @var string */
    protected $sFile = '_list_toolbar.htm';
    /** @var string */
    protected $sFolderPath = '/{{lower_author}}/{{lower_plugin}}/controllers/{{lower_controller}}/';
    /** @var string */
    protected $sTemplatePath = '/lovata/toolbox/classes/parser/templates/controller_list_toolbar.stub';
}
