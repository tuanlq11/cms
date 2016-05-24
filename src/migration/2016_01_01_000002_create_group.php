<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $schema = DB::connection()->getSchemaBuilder();

        $schema->blueprintResolver(function ($table, $callback) {
            return new \tuanlq11\dbi18n\I18NBlueprint($table, $callback);
        });

        $schema->dropIfExists('groups');
        $schema->create('groups', function (\tuanlq11\dbi18n\I18NBlueprint $table) {
            $table->increments('id');
            $table->i18n_string('name', 64)->unique();
            $table->i18n_string('description', 255)->nullable();
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $schema = DB::connection()->getSchemaBuilder();

        $schema->blueprintResolver(function ($table, $callback) {
            return new \tuanlq11\dbi18n\I18NBlueprint($table, $callback);
        });


        $schema->dropIfExists('groups');
    }
}
