<?php namespace Lovata\Toolbox\Classes\Console;

use Lovata\Toolbox\Classes\Parser\ExtendBackendMenuHandlerFile;
use Lovata\Toolbox\Traits\Console\Logo;

/**
 * Class CreateExtendBackendMenuHandler
 * @package Lovata\Toolbox\Classes\Console
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class CreateExtendBackendMenuHandler extends CommonCreateFile
{
    use Logo;

    /** @var string The console command name. */
    protected $name = 'toolbox.create.event.menu';
    /** @var string The console command description. */
    protected $description = 'Create a new extend backend menu.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->logoToolBox();
        $this->setAuthor();
        $this->setPlugin();

        $this->createFile(ExtendBackendMenuHandlerFile::class);
    }
}