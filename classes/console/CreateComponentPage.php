<?php namespace Lovata\Toolbox\Classes\Console;

use Lang;
use Lovata\Toolbox\Classes\Parser\ComponentPage;

/**
 * Class CreateComponentPage
 * @package Lovata\Toolbox\Classes\Console
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class CreateComponentPage extends CommonCreateFile
{
    /** @var string The console command name. */
    protected $name = 'toolbox:create.component.page';
    /** @var string The console command description. */
    protected $description = 'Create a new component page.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        parent::handle();

        $this->setModel();
        $this->setFieldList(null, [self::CODE_ACTIVE, self::CODE_VIEW_COUNT, self::CODE_DEFAULT]);
        $this->createFile(ComponentPage::class);
    }
}
