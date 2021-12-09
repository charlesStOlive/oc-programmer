<?php namespace Waka\Programer\Updates;

use Winter\Storm\Database\Schema\Blueprint;
use Winter\Storm\Database\Updates\Migration;
use Schema;

class CreateCampagnesTableU111 extends Migration
{
    public function up()
    {
        Schema::table('waka_programer_campagnes', function (Blueprint $table) {
            $table->string('lp')->nullable();
        });
    }

    public function down()
    {
        Schema::table('waka_programer_campagnes', function (Blueprint $table) {
            $table->dropColumn('lp');
        });
    }
}