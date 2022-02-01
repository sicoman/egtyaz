<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTablesPackagesAndTests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->BigInteger('points')->default(0)->comment('Package price for points')->after('sale_amount') ;
        });

        Schema::table('exams', function (Blueprint $table) {
            $table->string('title',150)->nullable()->comment('Exam Title')->after('type') ;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
