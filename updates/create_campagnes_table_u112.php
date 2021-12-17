<?php namespace Waka\Programer\Updates;

use Winter\Storm\Database\Schema\Blueprint;
use Winter\Storm\Database\Updates\Migration;
use Schema;

class CreateCampagnesTableU112 extends Migration
{
    public function up()
    {
        Schema::table('waka_programer_campagnes', function (Blueprint $table) {
            $table->boolean('is_embed')->nullable()->default(false);
        });
    }

    public function down()
    {
        Schema::table('waka_programer_campagnes', function (Blueprint $table) {
            $table->dropColumn('is_embed');
        });
    }
}