<?php namespace Lovata\Toolbox\Classes\Console;

use Lovata\Toolbox\Traits\Console\TraitLogo;
use Lovata\Toolbox\Classes\Parser\PluginPHPFile;
use Lovata\Toolbox\Classes\Parser\PluginYAMLFile;
use Lovata\Toolbox\Classes\Parser\PluginVersionFile;
use Lovata\Toolbox\Classes\Parser\PluginLangENFile;
use Lovata\Toolbox\Classes\Parser\PluginLangRUFile;
use Lovata\Toolbox\Classes\Parser\PluginLangFRFile;

/**
 * Class CreatePlugin
 * @package Lovata\Toolbox\Classes\Console
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class CreatePlugin extends CommonCreateFile
{
    use TraitLogo;

    /** @var string The console command name. */
    protected $name = 'toolbox.create.plugin';
    /** @var string The console command description. */
    protected $description = 'Create a new plugin.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        parent::handle();

        if (empty($this->arInoutData)) {
            $this->logoToolBox();
            $this->setAuthor();
            $this->setPlugin();
        }

        $this->createFile(PluginPHPFile::class);
        $this->createFile(PluginYAMLFile::class);
        $this->createFile(PluginVersionFile::class);
        $this->createFile(PluginLangENFile::class);
        $this->createFile(PluginLangRUFile::class);
        $this->createFile(PluginLangFRFile::class);
    }
}
