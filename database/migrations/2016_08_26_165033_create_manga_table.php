<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMangaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manga', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('manga_name', 255);
            $table->string('translator', 255)->nullable();
            $table->enum('status', ['full', 'continue']);
            $table->string('slug', 255);
            $table->mediumText('description');
            $table->string('thumbnail_uri', 100)->nullable();
            $table->integer('view_count')->unsigned()->default(0);
            $table->integer('like_count')->unsigned()->default(0);
            $table->tinyInteger('is_deleted')->default(0);
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
        Schema::drop('manga');
    }
}
