<?php namespace Lovata\Toolbox\Tests\Unit;

use Event;
use Backend\Widgets\Form;
use Backend\FormWidgets\Repeater;

use Lovata\Toolbox\Tests\CommonTest;
use Lovata\Toolbox\Models\ImageProfile;
use Lovata\Toolbox\Tests\Fixtures\ImageSetsTestTrait;
use Lovata\Toolbox\Tests\Fixtures\TestImageElement;
use Lovata\Toolbox\Tests\Fixtures\TestImageSetsFieldHandler;
use Lovata\Toolbox\Tests\Fixtures\Controllers\TestImageElements;

/**
 * Class ImageSetsFieldHandlerTest
 * @package Lovata\Toolbox\Tests\Unit
 *
 * @mixin \PHPUnit\Framework\Assert
 */
class ImageSetsFieldHandlerTest extends CommonTest
{
    use ImageSetsTestTrait;

    /**
     * Set up test method
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->setUpImageSets();

        Event::subscribe(new TestImageSetsFieldHandler());
    }

    /**
     * Tear down test method
     */
    public function tearDown(): void
    {
        $this->tearDownImageSets();
        parent::tearDown();
    }

    /**
     * Field is added only when image sets are enabled and active profile exists
     */
    public function testFieldIsAddedWhenEnabledAndActiveProfileExists()
    {
        //Disabled, no profiles
        $this->assertNull($this->makeForm()->getField('image_sets'));

        //Enabled, no profiles
        $this->setSettings(true, ImageProfile::APPROACH_DESKTOP_FIRST);
        $this->assertNull($this->makeForm()->getField('image_sets'));

        //Enabled, only inactive profile
        $obProfile = $this->createProfile('Tablet', 768, false);
        $this->assertNull($this->makeForm()->getField('image_sets'));

        //Disabled, active profile
        $obProfile->active = true;
        $obProfile->save();
        $this->setSettings(false, ImageProfile::APPROACH_DESKTOP_FIRST);
        $this->assertNull($this->makeForm()->getField('image_sets'));

        //Enabled, active profile: field is added in "create" and "update" contexts
        $this->setSettings(true, ImageProfile::APPROACH_DESKTOP_FIRST);
        foreach (['create', 'update'] as $sContext) {
            $obForm = $this->makeForm($sContext);
            $obField = $obForm->getField('image_sets');

            $this->assertNotNull($obField, 'Field is not added in context '.$sContext);
            $this->assertInstanceOf(Repeater::class, $obForm->getFormWidget('image_sets'));
            $this->assertTrue((bool) array_get($obField->config, 'useRelation'));
            $this->assertEquals('$/lovata/toolbox/models/imageset/fields.yaml', array_get($obField->config, 'form'));
            $this->assertEquals('lovata.toolbox::lang.tab.images', $obField->tab);
            $this->assertNotNull($obForm->getField('image_sets_section'));
        }
    }

    /**
     * Make form widget for test model
     * @param string $sContext
     * @return Form
     */
    protected function makeForm($sContext = 'update')
    {
        $obElement = $sContext == 'create' ? new TestImageElement() : $this->createElement();

        $obForm = new Form(new TestImageElements(), [
            'model'     => $obElement,
            'context'   => $sContext,
            'arrayName' => 'TestImageElement',
            'tabs'      => [
                'fields' => [
                    'name' => [
                        'label' => 'lovata.toolbox::lang.field.name',
                        'tab'   => 'lovata.toolbox::lang.tab.images',
                    ],
                ],
            ],
        ]);
        $obForm->bindToController();

        return $obForm;
    }
}
