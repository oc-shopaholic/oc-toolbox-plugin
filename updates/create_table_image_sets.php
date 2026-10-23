<?php namespace Lovata\Toolbox\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

/**
 * Class CreateTableImageSets
 * @package Lovata\Toolbox\Updates
 */
class CreateTableImageSets extends Migration
{
    /**
     * Apply migration
     */
    public function up()
    {
        if (Schema::hasTable('lovata_toolbox_image_sets')) {
            return;
        }

        Schema::create('lovata_toolbox_image_sets', function (Blueprint $obTable) {
            $obTable->engine = 'InnoDB';
            $obTable->increments('id')->unsigned();
            //Columns are nullable: the repeater in "useRelation" mode saves an empty set before it is bound to the element
            $obTable->integer('profile_id')->unsigned()->nullable();
            $obTable->nullableMorphs('imageable');
            $obTable->timestamps();

            //Generated index name is longer than 64 characters (MySQL limit)
            $obTable->unique(['profile_id', 'imageable_type', 'imageable_id'], 'lovata_toolbox_image_sets_unique');
            $obTable->foreign('profile_id')
                ->references('id')
                ->on('lovata_toolbox_image_profiles')
                ->onDelete('restrict');
        });
    }

    /**
     * Rollback migration
     */
    public function down()
    {
        Schema::dropIfExists('lovata_toolbox_image_sets');
    }
}
