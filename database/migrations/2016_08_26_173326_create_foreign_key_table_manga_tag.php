<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateForeignKeyTableMangaTag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('manga_tag', function ($table) {
            $table
                ->foreign('manga_id')
                ->references('id')
                ->on('manga')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('tag_id')
                ->references('id')
                ->on('tag')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('manga_tag', function ($table) {
            $table->dropForeign(['manga_id']);
            $table->dropForeign(['tag_id']);
        });
    }
}
