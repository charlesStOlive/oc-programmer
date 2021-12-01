<?php namespace Wcli\Crm\Updates;

use Winter\Storm\Database\Updates\Migration;
use Schema;

class ExtendMailerTable extends Migration
{
    public function up()
    {
        Schema::table('waka_mailer_waka_mails', function ($table) {
            $table->integer('is_campagner')->unsigned()->nullable();
        });
    }

    public function down()
    {
        if (Schema::hasColumn('waka_mailer_waka_mails', 'is_campagner')) {
            Schema::table('waka_mailer_waka_mails', function ($table) {
                $table->dropColumn('is_campagner');
            });
        }
    }
}
