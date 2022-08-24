<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoryTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_transfers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('trans_id')->unique();
            $table->bigInteger('system_id')->unique();
            $table->bigInteger('amount');
            $table->string('receiver_account')->nullable();
            $table->integer('receiver_bank_id')->nullable();
            $table->string('reason')->nullable();
            $table->string('message')->nullable();
            $table->string('otp')->nullable();
            $table->tinyInteger('status')->default(\App\Enums\TransactionStatus::PENDING);
            $table->foreignId('bank_id')->constrained('banks');
            $table->string('account_number')->nullable();
            $table->string('callback_url')->nullable();
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
        Schema::dropIfExists('history_transfers');
    }
}
