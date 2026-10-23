<?php namespace Lovata\Toolbox\Tests\Unit;

use System\Models\File;
use October\Rain\Exception\ValidationException;

use Lovata\Toolbox\Tests\CommonTest;
use Lovata\Toolbox\Models\ImageSet;
use Lovata\Toolbox\Models\ImageProfile;
use Lovata\Toolbox\Tests\Fixtures\TestImageElement;
use Lovata\Toolbox\Tests\Fixtures\ImageSetsTestTrait;

/**
 * Class ImageSetTest
 * @package Lovata\Toolbox\Tests\Unit
 *
 * @mixin \PHPUnit\Framework\Assert
 */
class ImageSetTest extends CommonTest
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
     * Second set for the same profile and element is rejected
     */
    public function testSecondSetForProfileIsRejected()
    {
        $obProfile = $this->createProfile('Tablet', 768);
        $obElement = $this->createElement();
        $this->createImageSet($obElement, $obProfile);

        try {
            $this->createImageSet($obElement, $obProfile);
            $this->fail('Second set for the same profile must not be saved');
        } catch (ValidationException $obException) {
            $this->assertStringContainsString('Tablet', $obException->getMessage());
        }

        $this->assertEquals(1, ImageSet::count());

        //Set for the same profile of other element and set for other profile are saved
        $this->createImageSet($this->createElement('Other'), $obProfile);
        $this->createImageSet($obElement, $this->createProfile('Mobile', 480));
        $this->assertEquals(3, ImageSet::count());
    }

    /**
     * Repeater draft is saved with "force" option, set without profile fails validation on normal save
     */
    public function testDraft()
    {
        $obDraft = new ImageSet();
        $obDraft->save(['force' => true]);
        $this->assertNotEmpty($obDraft->id);

        //Several drafts with NULL values do not conflict with unique index
        $obSecondDraft = new ImageSet();
        $obSecondDraft->save(['force' => true]);
        $this->assertNotEmpty($obSecondDraft->id);

        $obProfile = $this->createProfile('Tablet', 768);
        $obThirdDraft = new ImageSet();
        $obThirdDraft->profile_id = $obProfile->id;
        $obThirdDraft->save(['force' => true]);
        $obFourthDraft = new ImageSet();
        $obFourthDraft->profile_id = $obProfile->id;
        $obFourthDraft->save(['force' => true]);
        $this->assertEquals(4, ImageSet::count());

        $obSet = new ImageSet();
        $obSet->imageable_type = TestImageElement::class;
        $obSet->imageable_id = $this->createElement()->id;
        try {
            $obSet->save();
            $this->fail('Set without profile must not be saved');
        } catch (ValidationException $obException) {
            $this->assertArrayHasKey('profile_id', $obException->getErrors()->toArray());
        }

        $this->assertEquals(4, ImageSet::count());
    }

    /**
     * Relations "profile", "imageable" and "images" work
     */
    public function testRelations()
    {
        $obProfile = $this->createProfile('Tablet', 768);
        $obElement = $this->createElement();
        $obImageSet = $this->createImageSet($obElement, $obProfile, ['first.jpg', 'second.jpg']);

        $obImageSet = ImageSet::find($obImageSet->id);
        $this->assertInstanceOf(ImageProfile::class, $obImageSet->profile);
        $this->assertEquals($obProfile->id, $obImageSet->profile->id);

        $this->assertInstanceOf(TestImageElement::class, $obImageSet->imageable);
        $this->assertEquals($obElement->id, $obImageSet->imageable->id);

        $this->assertCount(2, $obImageSet->images);
        $this->assertInstanceOf(File::class, $obImageSet->images->first());
        $this->assertEquals(['first.jpg', 'second.jpg'], $obImageSet->images->pluck('file_name')->all());

        $this->assertCount(1, $obElement->image_sets);
        $this->assertEquals($obImageSet->id, $obElement->image_sets->first()->id);
        $this->assertCount(1, $obProfile->image_sets);
    }

    /**
     * Deleting element deletes its sets and their files
     */
    public function testDeleteElement()
    {
        $obProfile = $this->createProfile('Tablet', 768);
        $obElement = $this->createElement();
        $obOtherElement = $this->createElement('Other');

        $obImageSet = $this->createImageSet($obElement, $obProfile, ['first.jpg', 'second.jpg']);
        $obOtherImageSet = $this->createImageSet($obOtherElement, $obProfile);

        $arFileIDList = $obImageSet->images->pluck('id')->all();
        $arFilePathList = [];
        foreach ($obImageSet->images as $obFile) {
            $arFilePathList[] = $obFile->getLocalPath();
            $this->assertFileExists($obFile->getLocalPath());
        }

        $obElement->delete();

        $this->assertNull(ImageSet::find($obImageSet->id));
        $this->assertEquals(0, File::whereIn('id', $arFileIDList)->count());
        foreach ($arFilePathList as $sFilePath) {
            $this->assertFileDoesNotExist($sFilePath);
        }

        $this->assertNotNull(ImageSet::find($obOtherImageSet->id));
        $this->assertCount(1, ImageSet::find($obOtherImageSet->id)->images);

        //Profile without sets can be deleted after elements are deleted
        $obOtherElement->delete();
        $obProfile->delete();
        $this->assertNull(ImageProfile::find($obProfile->id));
    }
}
