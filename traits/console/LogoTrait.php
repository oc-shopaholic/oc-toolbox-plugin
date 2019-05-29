<?php namespace Lovata\Toolbox\Traits\Console;

/**
 * Trait LogoTrait
 * @package Lovata\Toolbox\Traits\Console
 * @author Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
trait LogoTrait
{
    /** @var array */
    protected $arLogoToolBox = [
        '<info>███──████──████──█────████───████──██─██</info>',
        '<info>─█───█──█──█──█──█────█──██──█──█───███</info>',
        '<info>─█───█──█──█──█──█────████───█──█────█</info>',
        '<info>─█───█──█──█──█──█────█──██──█──█───███</info>',
        '<info>─█───████──████──███──████───████──██─██</info>',
    ];
    /** @var array */
    protected $arLogoLovata = [
        '█──────████───█───█───████──███████──████',
        '█──────█──█───█───█───█──█─────█─────█──█',
        '█──────█──█───█───█───████─────█─────████',
        '█──────█──█────███────█──█─────█─────█──█',
        '█████──████─────█─────█──█─────█─────█──█',
    ];

    /**
     * Write logo toolbox
     */
    protected function logoToolBox()
    {
        $this->output->newLine(1);
        $this->output->writeln($this->arLogoLovata);
        $this->output->newLine(1);
        $this->output->writeln($this->arLogoToolBox);
        $this->output->newLine(1);
    }
}
