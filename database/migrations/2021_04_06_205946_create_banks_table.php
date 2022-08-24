<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banks', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('password');
            $table->string('imei');
            $table->string('account_number')->unique();
            $table->string('stkname')->nullable();
            $table->string('token')->index()->unique();
            $table->tinyInteger('status')->default(\App\Enums\BankStatus::ENABLE);
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
        Schema::dropIfExists('banks');
    }
}
