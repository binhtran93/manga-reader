<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateForeignKeyTableFavoriteManga extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('favorite_manga', function ($table) {
            $table
                ->foreign('manga_id')
                ->references('id')
                ->on('manga')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            
            $table
                ->foreign('user_id')
                ->references('id')
                ->on('user')
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
        Schema::table('favorite_manga', function ($table) {
            $table->dropForeign(['manga_id']);
            $table->dropForeign(['user_id']);
        });
    }
}
