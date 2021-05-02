<?php namespace Lovata\Toolbox\Tests;

include_once __DIR__.'/../../toolbox/vendor/autoload.php';
include_once __DIR__.'/../../../../tests/PluginTestCase.php';

use PluginTestCase;
use System\Classes\PluginManager;

/**
 * Class CommonTest
 * @package Lovata\Toolbox\Tests
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @mixin \PHPUnit\Framework\Assert
 */
abstract class CommonTest extends PluginTestCase
{
    /**
     * Set up test method
     */
    public function setUp(): void
    {
        parent::setUp();

        $obManager = PluginManager::instance();
        $arPluginList = array_keys($obManager->getPlugins());
        foreach ($arPluginList as $sPluginKey) {
            if(!preg_match('%^lovata.*%i', $sPluginKey)) {
                continue;
            }

            $obManager->refreshPlugin($sPluginKey);
        }

        $obManager->bootAll(true);
        $obManager->registerAll(true);
    }

    public function tearDown(): void
    {
        parent::tearDown();

        // Get the plugin manager
        $obManager = PluginManager::instance();

        // Ensure that plugins are registered again for the next test
        $obManager->unregisterAll();
    }
}
