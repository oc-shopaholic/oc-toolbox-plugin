<?php namespace Lovata\Toolbox\Classes\Parser;

/**
 * Class ModelFieldFile
 * @package Lovata\Toolbox\Classes\Parser
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class ModelFieldFile extends CommonFile
{
    /** @var string */
    protected $sFile = 'fields.yaml';
    /** @var string */
    protected $sPathFolder = '/{{lower_author}}/{{lower_plugin}}/models/{{lower_model}}/';
    /** @var string */
    protected $sPathTemplate = '/lovata/toolbox/classes/parser/templates/fields.stub';
}
