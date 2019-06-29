<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserWeekNorms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_week_norms', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('week');
            $table->bigInteger('user_id');
            $table->integer('points');
            $table->boolean('failed')->nullable()->default(null);
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
        Schema::dropIfExists('user_week_norms');
    }
}
