<?php namespace Lovata\Toolbox\Tests\Unit;

use DB;
use Event;
use System\Models\File;

use Lovata\Toolbox\Tests\CommonTest;
use Lovata\Toolbox\Models\ImageSet;
use Lovata\Toolbox\Models\ImageProfile;
use Lovata\Toolbox\Classes\Item\ItemStorage;
use Lovata\Toolbox\Classes\Item\ItemRegistry;
use Lovata\Toolbox\Tests\Fixtures\TestImageElement;
use Lovata\Toolbox\Tests\Fixtures\ImageSetsTestTrait;
use Lovata\Toolbox\Tests\Fixtures\TestRegistryElement;
use Lovata\Toolbox\Tests\Fixtures\TestImageElementItem;
use Lovata\Toolbox\Tests\Fixtures\TestNoItemModelHandler;
use Lovata\Toolbox\Tests\Fixtures\TestKeyIdentifierModelHandler;

/**
 * Class ImageSetsItemTest
 * @package Lovata\Toolbox\Tests\Unit
 *
 * @mixin \PHPUnit\Framework\Assert
 */
class ImageSetsItemTest extends CommonTest
{
    use ImageSetsTestTrait;

    /** @var TestImageElement */
    protected $obElement;

    /** @var ImageProfile[] */
    protected $arProfileList = [];

    /**
     * Set up test method
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->setUpImageSets();

        //Profiles are created not in width order
        $this->arProfileList = [
            'tablet' => $this->createProfile('Tablet', 1024),
            'mobile' => $this->createProfile('Mobile', 480),
            'laptop' => $this->createProfile('Laptop', 1440),
        ];

        $this->obElement = $this->createElement();
        foreach ($this->arProfileList as $sCode => $obProfile) {
            $this->createImageSet($this->obElement, $obProfile, [$sCode.'-1.jpg', $sCode.'-2.jpg']);
        }
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
     * Sets are ordered by ascending width for "desktop_first"
     */
    public function testOrderDesktopFirst()
    {
        $obItem = TestImageElementItem::make($this->obElement->id);

        $this->assertEquals([480, 1024, 1440], $this->getWidthList($obItem));
        $this->assertEquals('(width < 480px)', $obItem->image_sets[0]['profile']['media']);
        $this->assertEquals([
            'id'    => $this->arProfileList['mobile']->id,
            'name'  => 'Mobile',
            'width' => 480,
            'media' => '(width < 480px)',
        ], $obItem->image_sets[0]['profile']);
        $this->assertEquals(['mobile-1.jpg', 'mobile-2.jpg'], $this->getFileNameList($obItem->image_sets[0]));
    }

    /**
     * Sets are ordered by descending width for "mobile_first"
     */
    public function testOrderMobileFirst()
    {
        $this->setSettings(false, ImageProfile::APPROACH_MOBILE_FIRST);
        $obItem = TestImageElementItem::make($this->obElement->id);

        $this->assertEquals([1440, 1024, 480], $this->getWidthList($obItem));
        $this->assertEquals('(width >= 1440px)', $obItem->image_sets[0]['profile']['media']);
        $this->assertEquals(['laptop-1.jpg', 'laptop-2.jpg'], $this->getFileNameList($obItem->image_sets[0]));
    }

    /**
     * Sets of inactive profiles are skipped
     */
    public function testInactiveProfileIsSkipped()
    {
        $obProfile = $this->arProfileList['tablet'];
        $obProfile->active = false;
        $obProfile->save();

        $obItem = TestImageElementItem::make($this->obElement->id);
        $this->assertEquals([480, 1440], $this->getWidthList($obItem));

        //Element without sets returns empty list
        $obEmptyItem = TestImageElementItem::make($this->createElement('Empty')->id);
        $this->assertSame([], $obEmptyItem->image_sets);
    }

    /**
     * Item built from cache returns the same sets, profiles and files as Item built from model
     */
    public function testItemFromCacheIsEqualToItemFromModel()
    {
        $iElementID = $this->obElement->id;

        $obModelItem = TestImageElementItem::makeNoCache($iElementID);
        $arModelData = $this->getImageSetsSnapshot($obModelItem);

        //Item is built from model and saved in cache
        $obItem = TestImageElementItem::make($iElementID);
        $this->assertEquals($arModelData, $this->getImageSetsSnapshot($obItem));

        //Data is changed without model events: Item must be read from cache
        DB::table('lovata_toolbox_image_profiles')->update(['name' => 'Changed']);
        ItemStorage::clear(TestImageElementItem::class, $iElementID);

        $obCachedItem = TestImageElementItem::make($iElementID);
        $this->assertEquals($arModelData, $this->getImageSetsSnapshot($obCachedItem));
        $this->assertArrayHasKey('imageSets|image_sets', $obCachedItem->toArray());

        foreach ($obCachedItem->image_sets as $arImageSet) {
            $this->assertIsArray($arImageSet['images']);
            $this->assertCount(2, $arImageSet['images']);
            $this->assertContainsOnlyInstancesOf(File::class, $arImageSet['images']);
            $this->assertIsArray($arImageSet['profile']);
        }
    }

    /**
     * Example from documentation is rendered by Twig
     */
    public function testTwigExample()
    {
        $sTemplate = '{% for obSet in obElement.image_sets %}'
            .'<source media="{{ obSet.profile.media }}" srcset="{{ (obSet.images|first).path }}">'
            .'{% endfor %}';

        $obItem = TestImageElementItem::make($this->obElement->id);
        $sResult = app('twig.environment')->createTemplate($sTemplate)->render(['obElement' => $obItem]);

        $sExpectedResult = '';
        foreach ($obItem->image_sets as $arImageSet) {
            $obFile = $arImageSet['images'][0];
            $sExpectedResult .= '<source media="'.e($arImageSet['profile']['media']).'" srcset="'.e($obFile->path).'">';
        }

        $this->assertEquals($sExpectedResult, $sResult);
        $this->assertStringContainsString('media="(width &lt; 480px)"', $sResult);
        $this->assertStringContainsString('.jpg"', $sResult);
    }

    /**
     * Registry skips handlers without Item class and handlers whose identifier is not "id"
     */
    public function testRegistry()
    {
        Event::subscribe(new TestNoItemModelHandler());
        Event::subscribe(new TestKeyIdentifierModelHandler());

        $this->assertEquals(TestImageElementItem::class, ItemRegistry::getItemClass(TestImageElement::class));
        $this->assertNull(ItemRegistry::getItemClass(TestRegistryElement::class));

        //Model class without registered Item class and draft without element are skipped without error
        ItemRegistry::clearCache(TestRegistryElement::class, 1);
        ItemRegistry::clearCache('Lovata\Toolbox\Tests\Fixtures\UnknownModel', 1);
        ItemRegistry::clearCache(null, null);

        $obDraft = new ImageSet();
        $obDraft->save(['force' => true]);
        $obDraft->delete();

        $obSet = new ImageSet();
        $obSet->profile_id = $this->arProfileList['tablet']->id;
        $obSet->imageable_type = TestRegistryElement::class;
        $obSet->imageable_id = 100;
        $obSet->save();
        ImageSet::clearAllItemCache();

        $this->assertNotEmpty($obSet->id);
    }

    /**
     * Item cache is cleared when set is saved or deleted
     */
    public function testCacheIsClearedOnSetChange()
    {
        $iElementID = $this->obElement->id;
        $obProfile = $this->createProfile('Wide', 1920);

        $this->assertCount(3, TestImageElementItem::make($iElementID)->image_sets);

        $obImageSet = $this->createImageSet($this->obElement, $obProfile);
        $this->assertCount(4, TestImageElementItem::make($iElementID)->image_sets);

        //Set is moved to other profile
        $obImageSet->profile_id = $this->createProfile('Small', 320)->id;
        $obImageSet->save();
        $this->assertEquals([320, 480, 1024, 1440], $this->getWidthList(TestImageElementItem::make($iElementID)));

        $obImageSet->delete();
        $this->assertCount(3, TestImageElementItem::make($iElementID)->image_sets);
    }

    /**
     * Item cache of all elements with sets is cleared when profile is changed, deactivated or activated
     */
    public function testCacheIsClearedOnProfileChange()
    {
        $obProfile = $this->arProfileList['tablet'];
        $obOtherElement = $this->createElement('Other');
        $this->createImageSet($obOtherElement, $this->arProfileList['mobile']);

        $this->assertEquals([480, 1024, 1440], $this->getWidthList(TestImageElementItem::make($this->obElement->id)));
        $this->assertEquals('(width < 480px)', TestImageElementItem::make($obOtherElement->id)->image_sets[0]['profile']['media']);

        $obProfile->width = 400;
        $obProfile->name = 'Small tablet';
        $obProfile->save();
        $obItem = TestImageElementItem::make($this->obElement->id);
        $this->assertEquals([400, 480, 1440], $this->getWidthList($obItem));
        $this->assertEquals('Small tablet', $obItem->image_sets[0]['profile']['name']);

        $obProfile->active = false;
        $obProfile->save();
        $this->assertEquals([480, 1440], $this->getWidthList(TestImageElementItem::make($this->obElement->id)));

        $obProfile->active = true;
        $obProfile->save();
        $this->assertEquals([400, 480, 1440], $this->getWidthList(TestImageElementItem::make($this->obElement->id)));

        $obMobileProfile = $this->arProfileList['mobile'];
        $obMobileProfile->width = 360;
        $obMobileProfile->save();
        $this->assertEquals('(width < 360px)', TestImageElementItem::make($obOtherElement->id)->image_sets[0]['profile']['media']);
    }

    /**
     * Item cache of all elements with sets is cleared when approach is changed
     */
    public function testCacheIsClearedOnApproachChange()
    {
        $obOtherElement = $this->createElement('Other');
        $this->createImageSet($obOtherElement, $this->arProfileList['mobile']);

        $this->assertEquals([480, 1024, 1440], $this->getWidthList(TestImageElementItem::make($this->obElement->id)));
        $this->assertEquals('(width < 480px)', TestImageElementItem::make($obOtherElement->id)->image_sets[0]['profile']['media']);

        $this->setSettings(false, ImageProfile::APPROACH_MOBILE_FIRST);
        $this->assertEquals([1440, 1024, 480], $this->getWidthList(TestImageElementItem::make($this->obElement->id)));
        $this->assertEquals('(width >= 480px)', TestImageElementItem::make($obOtherElement->id)->image_sets[0]['profile']['media']);

        $this->setSettings(true, ImageProfile::APPROACH_DESKTOP_FIRST);
        $this->assertEquals([480, 1024, 1440], $this->getWidthList(TestImageElementItem::make($this->obElement->id)));
    }

    /**
     * Get width list of image sets
     * @param TestImageElementItem $obItem
     * @return array
     */
    protected function getWidthList($obItem)
    {
        return array_column(array_column($obItem->image_sets, 'profile'), 'width');
    }

    /**
     * Get file name list of image set
     * @param array $arImageSet
     * @return array
     */
    protected function getFileNameList($arImageSet)
    {
        return array_map(function ($obFile) {
            return $obFile->file_name;
        }, $arImageSet['images']);
    }

    /**
     * Get image sets data for comparison
     * @param TestImageElementItem $obItem
     * @return array
     */
    protected function getImageSetsSnapshot($obItem)
    {
        $arResult = [];
        foreach ($obItem->image_sets as $arImageSet) {
            $arFileList = [];
            foreach ($arImageSet['images'] as $obFile) {
                $arFileList[] = [
                    'id'          => $obFile->id,
                    'disk_name'   => $obFile->disk_name,
                    'file_name'   => $obFile->file_name,
                    'title'       => $obFile->title,
                    'description' => $obFile->description,
                    'path'        => $obFile->path,
                ];
            }

            $arResult[] = [
                'profile' => $arImageSet['profile'],
                'images'  => $arFileList,
            ];
        }

        return $arResult;
    }
}
