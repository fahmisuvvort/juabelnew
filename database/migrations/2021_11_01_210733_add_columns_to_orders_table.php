<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('order_ref')->nullable()->after('id'); 
            $table->string('customer_email')->nullable()->after('customer_name');
            $table->string('shipping_type')->nullable();
            $table->string('shipping_courier_code')->nullable();
            $table->string('shipping_courier_name')->nullable();
            $table->string('shipping_courier_service')->nullable();
            $table->integer('shipping_cost')->nullable();
            $table->mediumText('shipping_address')->nullable();
            $table->timestamp('shipping_delivered')->nullable();
            $table->timestamp('shipping_received')->nullable();
            $table->mediumText('note')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('order_reference');
            $table->dropColumn('shipping_type');
            $table->dropColumn('shipping_courier_code');
            $table->dropColumn('shipping_courier_name');
            $table->dropColumn('shipping_courier_service');
            $table->dropColumn('shipping_cost');
            $table->dropColumn('shipping_address');
            $table->dropColumn('shipping_delivered');
            $table->dropColumn('shipping_received');
            $table->dropColumn('note');
        });
    }
}
