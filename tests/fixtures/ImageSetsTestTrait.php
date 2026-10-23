<?php namespace Lovata\Toolbox\Tests\Fixtures;

use DB;
use Event;
use Config;
use Storage;
use System\Models\File;
use October\Rain\Filesystem\Filesystem;

use Lovata\Toolbox\Models\Settings;
use Lovata\Toolbox\Models\ImageSet;
use Lovata\Toolbox\Models\ImageProfile;
use Lovata\Toolbox\Tests\Fixtures\Updates\CreateTableTestImageElements;

/**
 * Trait ImageSetsTestTrait
 * Common setup of image sets tests: test model table, model handler, settings and uploads folder
 * @package Lovata\Toolbox\Tests\Fixtures
 *
 * @mixin \Lovata\Toolbox\Tests\CommonTest
 */
trait ImageSetsTestTrait
{
    /** @var string */
    protected $sUploadsPath;

    /**
     * Init test model table, handlers and default settings
     */
    protected function setUpImageSets()
    {
        //Uploaded files are stored in temp folder, not in project storage
        $this->sUploadsPath = temp_path('toolbox-image-sets-tests-'.uniqid());
        Config::set('filesystems.disks.uploads.root', $this->sUploadsPath);
        Storage::forgetDisk('uploads');

        //Table "system_files" is not rolled back with plugin tables, so files of image sets from previous tests are removed
        $this->removeImageSetFiles();

        $obMigration = new CreateTableTestImageElements();
        $obMigration->down();
        $obMigration->up();

        Event::subscribe(new TestImageElementModelHandler());

        $this->setSettings(false, ImageProfile::APPROACH_DESKTOP_FIRST);
    }

    /**
     * Remove test model table and uploaded files
     */
    protected function tearDownImageSets()
    {
        $this->removeImageSetFiles();
        (new CreateTableTestImageElements())->down();

        if (!empty($this->sUploadsPath)) {
            (new Filesystem())->deleteDirectory($this->sUploadsPath);
        }
    }

    /**
     * Remove file records of image sets
     */
    protected function removeImageSetFiles()
    {
        DB::table('system_files')->where('attachment_type', ImageSet::class)->delete();
    }

    /**
     * Set image settings
     * @param bool   $bEnabled
     * @param string $sApproach
     */
    protected function setSettings($bEnabled, $sApproach)
    {
        Settings::clearInternalCache();
        Settings::set([
            'image_sets_enabled'      => $bEnabled,
            'image_profiles_approach' => $sApproach,
        ]);
        Settings::clearInternalCache();
    }

    /**
     * Create image profile
     * @param string $sName
     * @param int    $iWidth
     * @param bool   $bActive
     * @return ImageProfile
     */
    protected function createProfile($sName, $iWidth, $bActive = true)
    {
        $obProfile = new ImageProfile();
        $obProfile->name = $sName;
        $obProfile->width = $iWidth;
        $obProfile->active = $bActive;
        $obProfile->save();

        return $obProfile;
    }

    /**
     * Create test element
     * @param string $sName
     * @return TestImageElement
     */
    protected function createElement($sName = 'Element')
    {
        return TestImageElement::create(['name' => $sName]);
    }

    /**
     * Create image set with files
     * @param TestImageElement $obElement
     * @param ImageProfile     $obProfile
     * @param array            $arFileNameList
     * @return ImageSet
     */
    protected function createImageSet($obElement, $obProfile, $arFileNameList = ['image.jpg'])
    {
        $obImageSet = new ImageSet();
        $obImageSet->profile_id = $obProfile->id;
        $obImageSet->imageable_type = get_class($obElement);
        $obImageSet->imageable_id = $obElement->id;
        $obImageSet->save();

        foreach ($arFileNameList as $sFileName) {
            $obImageSet->images()->add($this->createFile($sFileName));
        }

        return $obImageSet;
    }

    /**
     * Create file
     * @param string $sFileName
     * @return File
     */
    protected function createFile($sFileName)
    {
        $obFile = new File();
        $obFile->fromData('test-'.$sFileName, $sFileName);
        $obFile->is_public = true;
        $obFile->title = 'Title '.$sFileName;
        $obFile->description = 'Description '.$sFileName;
        $obFile->save();

        return $obFile;
    }
}
