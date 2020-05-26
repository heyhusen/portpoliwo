<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_tags', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('work_id')->index();
            $table->foreign('work_id')->references('id')->on('works')->onDelete('cascade')->onUpdate('cascade');
            $table->uuid('tag_id')->index();
            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('work_tags');
    }
}
