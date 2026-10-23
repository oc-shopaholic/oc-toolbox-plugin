<?php namespace Lovata\Toolbox\Tests\Fixtures;

use Model;

use Lovata\Toolbox\Models\ImageSet;
use Lovata\Toolbox\Traits\Helpers\TraitCached;

/**
 * Class TestImageElement
 * Test model with image sets configuration from task #137, section 4
 * @package Lovata\Toolbox\Tests\Fixtures
 *
 * @property int                                         $id
 * @property string                                      $name
 * @property \October\Rain\Database\Collection|ImageSet[] $image_sets
 */
class TestImageElement extends Model
{
    use TraitCached;

    public $table = 'lovata_toolbox_test_image_elements';

    public $morphMany = [
        'image_sets' => [ImageSet::class, 'name' => 'imageable', 'delete' => true],
    ];

    public $fillable = [
        'name',
    ];

    public $cached = [
        'id',
        'name',
        'image_sets',
    ];
}
