<?php namespace Lovata\Toolbox\Classes\Console;

use Lovata\Toolbox\Traits\Console\UpdateLangFile;
use Lovata\Toolbox\Classes\Parser\Create\ComponentDataCreateFile;

/**
 * Class CreateComponentData
 * @package Lovata\Toolbox\Classes\Console
 * @author Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class CreateComponentData extends CommonCreateFile
{
    use UpdateLangFile;

    /** @var string The console command name. */
    protected $name = 'toolbox:create.component.data';
    /** @var string The console command description. */
    protected $description = 'Create a new component data.';
    /** @var array */
    protected $arLangData = [
        'component' => [
            '{{lower_model}}_data_name'        => '{{studly_model}} Data',
            '{{lower_model}}_data_description' => 'Get {{lower_model}} by ID',
        ],
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        parent::handle();

        $this->setModel();
        $this->createFile(ComponentDataCreateFile::class);
        $this->updatePluginLang($this->arLangData);
    }
}
