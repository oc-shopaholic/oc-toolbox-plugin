<?php namespace Lovata\Toolbox\Classes\Parser;

/**
 * Class ModelFile
 * @package Lovata\Toolbox\Classes\Parser
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class ModelFile extends CommonFile
{
    /** @var string */
    protected $sFile = '{{studly_model}}.php';
    /** @var string */
    protected $sPathFolder = '/{{lower_author}}/{{lower_plugin}}/models/';
    /** @var string */
    protected $sPathTemplate = '/lovata/toolbox/classes/parser/templates/model.stub';
}
