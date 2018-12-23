<?php namespace Lovata\Toolbox\Classes\Parser;

/**
 * Class MigrationFile
 * @package Lovata\Toolbox\Classes\Parser
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class MigrationFile extends CommonFile
{
    /** @var string */
    protected $sFile = 'create_table_{{lower_controller}}.php';
    /** @var string */
    protected $sPathFolder = '/{{lower_author}}/{{lower_plugin}}/updates/';
    /** @var string */
    protected $sPathTemplate = '/lovata/toolbox/classes/parser/templates/migration.stub';
}
