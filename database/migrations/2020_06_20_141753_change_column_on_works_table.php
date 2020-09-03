<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnOnWorksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('works', function (Blueprint $table) {
            $table->dropColumn(['owner', 'git']);
        });

        Schema::table('works', function (Blueprint $table) {
            $table->string('url')->nullable()->after('name');
            $table->string('photo')->nullable()->after('name');
            $table->text('description')->nullable()->after('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('works', function (Blueprint $table) {
            $table->dropColumn(['url', 'photo', 'description']);
        });

        Schema::table('works', function (Blueprint $table) {
            $table->string('git')->after('name');
            $table->string('owner')->after('name');
        });
    }
}
