<?php namespace Lovata\Toolbox\Tests\Fixtures\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

/**
 * Class CreateTableTestImageElements
 * Table of test model with image sets
 * @package Lovata\Toolbox\Tests\Fixtures\Updates
 */
class CreateTableTestImageElements extends Migration
{
    const TABLE_NAME = 'lovata_toolbox_test_image_elements';

    /**
     * Apply migration
     */
    public function up()
    {
        if (Schema::hasTable(self::TABLE_NAME)) {
            return;
        }

        Schema::create(self::TABLE_NAME, function (Blueprint $obTable) {
            $obTable->engine = 'InnoDB';
            $obTable->increments('id')->unsigned();
            $obTable->string('name')->nullable();
            $obTable->timestamps();
        });
    }

    /**
     * Rollback migration
     */
    public function down()
    {
        Schema::dropIfExists(self::TABLE_NAME);
    }
}
