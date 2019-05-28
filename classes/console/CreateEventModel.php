<?php namespace Lovata\Toolbox\Classes\Console;

use Lovata\Toolbox\Classes\Parser\Create\EventModelCreateFile;

/**
 * Class CreateEventModel
 * @package Lovata\Toolbox\Classes\Console
 * @author Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class CreateEventModel extends CommonCreateFile
{
    /** @var string The console command name. */
    protected $name = 'toolbox:create.event.model';
    /** @var string The console command description. */
    protected $description = 'Create a new event model.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        parent::handle();

        $this->setAuthor(true);
        $this->setPlugin(true);
        $this->setModel();
        $this->setFieldList(null, [self::CODE_ACTIVE, self::CODE_VIEW_COUNT, self::CODE_DEFAULT]);
        $this->setSorting();
        $this->createFile(EventModelCreateFile::class);
    }
}
