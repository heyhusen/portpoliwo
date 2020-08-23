<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogPostCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_post_categories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('blog_post_id')->index();
            $table->uuid('blog_category_id')->index();
            $table->foreign('blog_post_id')->references('id')->on('blog_posts')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('blog_category_id')->references('id')->on('blog_categories')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('blog_post_categories');
    }
}
