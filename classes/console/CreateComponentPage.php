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
    protected $name = 'toolbox.create.component.page';
    /** @var string The console command description. */
    protected $description = 'Create a new component page.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        parent::handle();

        $sModelLower  = array_get($this->arInoutData, 'replace.' . self::PREFIX_LOWER . self::CODE_MODEL);
        $sModelStudly = array_get($this->arInoutData, 'replace.' . self::PREFIX_STUDLY . self::CODE_MODEL);

        if (empty($this->arInoutData)) {
            $this->logoToolBox();
            $this->setAuthor();
            $this->setPlugin();
        }

        if (empty($sModelLower) || empty($sModelStudly)) {
            $this->setModel();
        }

        if (!$this->checkAddition(self::CODE_ACTIVE)) {
            $this->arData['enable'][] = self::CODE_ACTIVE;
            $sMessage = Lang::get('lovata.toolbox::lang.message.component_page_active');
            if (!$this->confirm($sMessage, true)) {
                $this->arData['disable'][] = self::CODE_ACTIVE;
            }
        };

        $this->createFile(ComponentPage::class);
    }
}