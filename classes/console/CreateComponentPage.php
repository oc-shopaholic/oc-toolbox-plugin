<?php namespace Lovata\Toolbox\Classes\Console;

use Lovata\Toolbox\Traits\Console\UpdateLangFile;
use Lovata\Toolbox\Classes\Parser\Create\ComponentPageCreateFile;

/**
 * Class CreateComponentPage
 * @package Lovata\Toolbox\Classes\Console
 * @author Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class CreateComponentPage extends CommonCreateFile
{
    use UpdateLangFile;

    /** @var string The console command name. */
    protected $name = 'toolbox:create.component.page';
    /** @var string The console command description. */
    protected $description = 'Create a new component page.';
    /** @var array */
    protected $arLangData = [
        'component' => [
            '{{lower_model}}_page_name'        => '{{studly_model}} Page',
            '{{lower_model}}_page_description' => 'Get {{lower_model}} page data',
        ],
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        parent::handle();

        $this->setModel();
        $this->setFieldList(null, [self::CODE_ACTIVE, self::CODE_VIEW_COUNT, self::CODE_DEFAULT]);
        $this->createFile(ComponentPageCreateFile::class);
        $this->updatePluginLang($this->arLangData);
    }
}
