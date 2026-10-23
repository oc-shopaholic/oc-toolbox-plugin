<?php namespace Lovata\Toolbox\Tests\Unit;

use October\Rain\Exception\ApplicationException;
use October\Rain\Exception\ValidationException;

use Lovata\Toolbox\Tests\CommonTest;
use Lovata\Toolbox\Models\ImageProfile;
use Lovata\Toolbox\Tests\Fixtures\ImageSetsTestTrait;

/**
 * Class ImageProfileTest
 * @package Lovata\Toolbox\Tests\Unit
 *
 * @mixin \PHPUnit\Framework\Assert
 */
class ImageProfileTest extends CommonTest
{
    use ImageSetsTestTrait;

    /**
     * Set up test method
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->setUpImageSets();
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
     * Name is unique case-insensitively, including among inactive profiles
     */
    public function testNameIsUniqueCaseInsensitively()
    {
        $this->createProfile('Mobile', 480, false);
        $this->createProfile('Телефон', 360);

        $this->assertValidationFails('mobile', 768);
        $this->assertValidationFails(' MOBILE ', 768);
        $this->assertValidationFails('ТЕЛЕФОН', 768);

        $obProfile = $this->createProfile('Tablet', 1024);
        $this->assertNotEmpty($obProfile->id);

        //Profile can be saved with its own name
        $obProfile->name = 'TABLET';
        $obProfile->save();
        $this->assertEquals('TABLET', ImageProfile::find($obProfile->id)->name);
    }

    /**
     * Width is unique and must be integer greater than 0
     */
    public function testWidthValidation()
    {
        $this->createProfile('Mobile', 480, false);

        $this->assertValidationFails('Tablet', 480);
        $this->assertValidationFails('Tablet', 0);
        $this->assertValidationFails('Tablet', -768);
        $this->assertValidationFails('Tablet', '768.5');
        $this->assertValidationFails('Tablet', '');

        $obProfile = $this->createProfile('Tablet', '768');
        $this->assertEquals(768, ImageProfile::find($obProfile->id)->width);
    }

    /**
     * Media condition depends on approach
     */
    public function testMediaAttribute()
    {
        $obProfile = $this->createProfile('Tablet', 768);

        $this->setSettings(false, ImageProfile::APPROACH_DESKTOP_FIRST);
        $this->assertEquals('(width < 768px)', $obProfile->media);

        $this->setSettings(false, ImageProfile::APPROACH_MOBILE_FIRST);
        $this->assertEquals('(width >= 768px)', $obProfile->media);

        $this->setSettings(false, '');
        $this->assertEquals('(width < 768px)', $obProfile->media);
    }

    /**
     * Profile with image sets can not be deleted, profile without sets is deleted
     */
    public function testDelete()
    {
        $obProfile = $this->createProfile('Tablet', 768);
        $this->createImageSet($this->createElement(), $obProfile);

        try {
            $obProfile->delete();
            $this->fail('Profile with image sets must not be deleted');
        } catch (ApplicationException $obException) {
            $this->assertStringContainsString('Deactivate', $obException->getMessage());
        }

        $this->assertNotNull(ImageProfile::find($obProfile->id));

        $obEmptyProfile = $this->createProfile('Mobile', 480);
        $obEmptyProfile->delete();
        $this->assertNull(ImageProfile::find($obEmptyProfile->id));
    }

    /**
     * Check, that profile is not saved
     * @param string     $sName
     * @param int|string $iWidth
     */
    protected function assertValidationFails($sName, $iWidth)
    {
        $iCount = ImageProfile::count();
        try {
            $this->createProfile($sName, $iWidth);
            $this->fail('Profile "'.$sName.'" with width "'.$iWidth.'" must not be saved');
        } catch (ValidationException $obException) {
            $this->assertNotEmpty($obException->getMessage());
        }

        $this->assertEquals($iCount, ImageProfile::count());
    }
}
