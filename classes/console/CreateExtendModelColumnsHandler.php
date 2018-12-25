<?php namespace Lovata\Toolbox\Classes\Console;

use Lovata\Toolbox\Classes\Parser\Create\ExtendModelColumnsHandlerCreateFile;

/**
 * Class CreateExtendModelColumnsHandler
 * @package Lovata\Toolbox\Classes\Console
 * @author Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class CreateExtendModelColumnsHandler extends CommonCreateFile
{
    /** @var string The console command name. */
    protected $name = 'toolbox:create.event.columns';
    /** @var string The console command description. */
    protected $description = 'Create a new extend model columns.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        parent::handle();

        $this->setAuthor(true);
        $this->setPlugin(true);
        $this->setModel();
        $this->setController();
        $this->createFile(ExtendModelColumnsHandlerCreateFile::class);
    }
}
