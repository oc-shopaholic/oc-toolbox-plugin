<?php namespace Lovata\Toolbox\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

/**
 * Class CreateTableImageProfiles
 * @package Lovata\Toolbox\Updates
 */
class CreateTableImageProfiles extends Migration
{
    /**
     * Apply migration
     */
    public function up()
    {
        if (Schema::hasTable('lovata_toolbox_image_profiles')) {
            return;
        }

        Schema::create('lovata_toolbox_image_profiles', function (Blueprint $obTable) {
            $obTable->engine = 'InnoDB';
            $obTable->increments('id')->unsigned();
            $obTable->boolean('active')->default(1);
            $obTable->string('name');
            $obTable->integer('width')->unsigned()->unique();
            $obTable->timestamps();
        });
    }

    /**
     * Rollback migration
     */
    public function down()
    {
        Schema::dropIfExists('lovata_toolbox_image_profiles');
    }
}
