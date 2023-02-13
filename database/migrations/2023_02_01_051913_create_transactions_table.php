<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sender_account_id')->index();
            $table->foreign('sender_account_id')->references('id')->on('accounts');
            $table->unsignedBigInteger('receiver_account_id')->index();
            $table->foreign('receiver_account_id')->references('id')->on('accounts');
            $table->double('amount', 8, 2);
            $table->integer('is_active')->index();
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
        Schema::dropIfExists('transactions');
    }
};
