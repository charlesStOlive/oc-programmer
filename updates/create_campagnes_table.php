<?php namespace Waka\Programer\Updates;

use Winter\Storm\Database\Schema\Blueprint;
use Winter\Storm\Database\Updates\Migration;
use Schema;

class CreateCampagnesTable extends Migration
{
    public function up()
    {
        Schema::create('waka_programer_campagnes', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('state')->nullable();
            $table->string('subject')->nullable();
            $table->boolean('has_programmation')->nullable();
            $table->string('selection_mode')->nullable();
            $table->string('selection_name')->nullable();
            $table->text('tests_ids')->nullable();
            $table->text('pjs')->nullable();
            $table->boolean('is_scope')->nullable()->default(false);
            $table->text('scopes')->nullable();
            $table->string('programation_mode')->nullable();
            $table->string('programation_day')->nullable();
            $table->string('programation_hour')->nullable();
            $table->boolean('is_mjml')->nullable()->default(false);
            $table->text('mjml')->nullable();
            $table->text('html')->nullable();
            $table->text('mjml_html')->nullable();
            $table->string('data_source')->nullable();
            $table->string('name');
            $table->string('slug');
            $table->integer('layout_id')->unsigned()->nullable();
            $table->integer('waka_mail_id')->unsigned();
            $table->boolean('use_key')->nullable();
            $table->string('key_duration')->nullable();
            $table->boolean('has_log')->nullable()->default(true);
            $table->boolean('open_log')->nullable()->default(false);
            $table->boolean('click_log')->nullable()->default(false);
            $table->boolean('has_sender')->nullable()->default(false);
            $table->string('sender')->nullable();
            $table->string('reply_to')->nullable();
            $table->integer('nb_use')->nullable();
            $table->text('versions')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('waka_programer_campagnes');
    }
}