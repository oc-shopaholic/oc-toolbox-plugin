<?php namespace Lovata\Toolbox\Classes\Parser\Create;

/**
 * Class ModelCreateFile
 * @package Lovata\Toolbox\Classes\Parser\Create
 * @author Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class ModelCreateFile extends CommonCreateFile
{
    /** @var string */
    protected $sFile = '{{studly_model}}.php';
    /** @var string */
    protected $sFolderPath = '/{{lower_author}}/{{lower_plugin}}/models/';
    /** @var string */
    protected $sTemplatePath = '/lovata/toolbox/classes/parser/templates/model.stub';
}
