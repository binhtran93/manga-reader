<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUniqueMangaIdAndChapterNumber extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chapter', function ($table) {
            $table->unique(['manga_id', 'chapter_number', 'is_deleted']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chapter', function ($table) {
            $table->dropUnique(['manga_id', 'chapter_number', 'is_deleted']);
        });
    }
}
