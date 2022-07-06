<?php

use Faker\Provider\Medical;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
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
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->string('payment_ref')->nullable();
            $table->string('payment_type')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_name')->nullable();
            $table->string('payment_code')->nullable();
            $table->integer('amount')->default(0);
            $table->integer('amount_received')->default(0);
            $table->integer('total_fee')->default(0);
            $table->integer('expired_time')->nullable();
            $table->text('instructions')->nullable();
            $table->string('status')->default('UNPAID');
            $table->timestamp('paid_at')->nullable();
            $table->mediumText('note')->nullable();
            $table->string('payment_proof')->nullable();
            $table->string('qr_url')->nullable();
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
}
