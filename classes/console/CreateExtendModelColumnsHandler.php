<?php namespace Lovata\Toolbox\Classes\Console;

use Lovata\Toolbox\Classes\Parser\ExtendModelColumnsHandlerFile;
use Lovata\Toolbox\Traits\Console\Logo;

/**
 * Class CreateExtendModelColumnsHandler
 * @package Lovata\Toolbox\Classes\Console
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class CreateExtendModelColumnsHandler extends CommonCreateFile
{
    use Logo;

    /** @var string The console command name. */
    protected $name = 'toolbox:create.event.columns';
    /** @var string The console command description. */
    protected $description = 'Create a new extend model columns.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->logoToolBox();
        $this->setAuthor();
        $this->setPlugin();
        $this->setModel();
        $this->setController();

        $this->createFile(ExtendModelColumnsHandlerFile::class);
    }
}