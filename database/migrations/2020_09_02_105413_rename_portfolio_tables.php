<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenamePortfolioTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (config('database.default') == 'mysql') {
            Schema::table('work_categories', function (Blueprint $table) {
                $table->dropForeign(['work_id']);
                $table->dropForeign(['category_id']);
            });

            Schema::table('work_tags', function (Blueprint $table) {
                $table->dropForeign(['work_id']);
                $table->dropForeign(['tag_id']);
            });
        }

        Schema::rename('works', 'portfolio_works');
        Schema::rename('categories', 'portfolio_categories');
        Schema::rename('tags', 'portfolio_tags');
        Schema::rename('work_categories', 'portfolio_work_categories');
        Schema::rename('work_tags', 'portfolio_work_tags');

        if (config('database.default') == 'mysql') {
            Schema::table('portfolio_work_categories', function (Blueprint $table) {
                $table->renameColumn('work_id', 'portfolio_work_id');
                $table->foreign('portfolio_work_id')->references('id')->on('portfolio_works')->onDelete('cascade')->onUpdate('cascade');
                $table->renameColumn('category_id', 'portfolio_category_id');
                $table->foreign('portfolio_category_id')->references('id')->on('portfolio_categories')->onDelete('cascade')->onUpdate('cascade');
            });

            Schema::table('portfolio_work_tags', function (Blueprint $table) {
                $table->renameColumn('work_id', 'portfolio_work_id');
                $table->foreign('portfolio_work_id')->references('id')->on('portfolio_works')->onDelete('cascade')->onUpdate('cascade');
                $table->renameColumn('tag_id', 'portfolio_tag_id');
                $table->foreign('portfolio_tag_id')->references('id')->on('portfolio_tags')->onDelete('cascade')->onUpdate('cascade');
            });
        }

        if (config('database.default') == 'sqlite') {
            Schema::dropIfExists('portfolio_work_categories');
            Schema::create('portfolio_work_categories', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('portfolio_work_id')->index();
                $table->foreign('portfolio_work_id')->references('id')->on('portfolio_works')->onDelete('cascade')->onUpdate('cascade');
                $table->uuid('portfolio_category_id')->index();
                $table->foreign('portfolio_category_id')->references('id')->on('portfolio_categories')->onDelete('cascade')->onUpdate('cascade');
                $table->timestamps();
            });

            Schema::dropIfExists('portfolio_work_tags');
            Schema::create('portfolio_work_tags', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('portfolio_work_id')->index();
                $table->foreign('portfolio_work_id')->references('id')->on('portfolio_works')->onDelete('cascade')->onUpdate('cascade');
                $table->uuid('portfolio_tag_id')->index();
                $table->foreign('portfolio_tag_id')->references('id')->on('portfolio_tags')->onDelete('cascade')->onUpdate('cascade');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (config('database.default') == 'mysql') {
            Schema::table('portfolio_work_categories', function (Blueprint $table) {
                $table->dropForeign(['portfolio_work_id']);
                $table->dropForeign(['portfolio_category_id']);
            });

            Schema::table('portfolio_work_tags', function (Blueprint $table) {
                $table->dropForeign(['portfolio_work_id']);
                $table->dropForeign(['portfolio_tag_id']);
            });
        }

        Schema::rename('portfolio_work_tags', 'work_tags');
        Schema::rename('portfolio_work_categories', 'work_categories');
        Schema::rename('portfolio_tags', 'tags');
        Schema::rename('portfolio_categories' ,'categories');
        Schema::rename('portfolio_works' ,'works');

        if (config('database.default') == 'mysql') {
            Schema::table('work_categories', function (Blueprint $table) {
                $table->renameColumn('portfolio_work_id' ,'work_id');
                $table->foreign('work_id')->references('id')->on('works')->onDelete('cascade')->onUpdate('cascade');
                $table->renameColumn('portfolio_category_id', 'category_id');
                $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade')->onUpdate('cascade');
            });

            Schema::table('work_tags', function (Blueprint $table) {
                $table->renameColumn('portfolio_work_id', 'work_id');
                $table->foreign('work_id')->references('id')->on('works')->onDelete('cascade')->onUpdate('cascade');
                $table->renameColumn('portfolio_tag_id', 'tag_id');
                $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade')->onUpdate('cascade');
            });
        }
    }
}
