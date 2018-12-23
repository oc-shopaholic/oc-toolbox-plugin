<?php namespace Lovata\Toolbox\Classes\Parser;

/**
 * Class ModelColumnFile
 * @package Lovata\Toolbox\Classes\Parser
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class ModelColumnFile extends CommonFile
{
    /** @var string */
    protected $sFile = 'columns.yaml';
    /** @var string */
    protected $sPathFolder = '/{{lower_author}}/{{lower_plugin}}/models/{{lower_model}}/';
    /** @var string */
    protected $sPathTemplate = '/lovata/toolbox/classes/parser/templates/columns.stub';
}
