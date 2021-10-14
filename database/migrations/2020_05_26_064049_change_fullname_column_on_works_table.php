<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeFullnameColumnOnWorksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('works', function (Blueprint $table) {
            $table->string('owner')->nullable()->after('name');
        });

        DB::table('works')->update([
            'owner' => DB::raw('fullname')
        ]);

        Schema::table('works', function (Blueprint $table) {
            $table->dropColumn('fullname');
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
            Schema::table('works', function (Blueprint $table) {
                $table->string('fullname')->after('git');
            });

            DB::table('works')->update([
                'fullname' => DB::raw('owner')
            ]);

            Schema::table('works', function (Blueprint $table) {
                $table->dropColumn('owner');
            });
        });
    }
}
