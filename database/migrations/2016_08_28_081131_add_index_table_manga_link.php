<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexTableMangaLink extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('manga_link', function ($table) {
           $table->unique(['link', 'manga_name', 'domain', 'is_deleted']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('manga_link', function ($table) {
           $table->dropUnique(['link', 'manga_name', 'domain', 'is_deleted']);
        });
    }
}
