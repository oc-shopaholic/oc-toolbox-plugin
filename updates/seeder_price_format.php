<?php namespace Lovata\Toolbox\Updates;

use Seeder;
use Lovata\Toolbox\Models\Settings;

/**
 * Class SeederDefaultStatus
 * @package Lovata\Toolbox\Updates
 */
class SeederDefaultStatus extends Seeder
{
    public function run()
    {
        if (!class_exists('\Lovata\Shopaholic\Models\Settings')) {
            return;
        }

        Settings::set('decimals', \Lovata\Shopaholic\Models\Settings::getValue('decimals'));
        Settings::set('dec_point', \Lovata\Shopaholic\Models\Settings::getValue('dec_point'));
        Settings::set('thousands_sep', \Lovata\Shopaholic\Models\Settings::getValue('thousands_sep'));
    }
}