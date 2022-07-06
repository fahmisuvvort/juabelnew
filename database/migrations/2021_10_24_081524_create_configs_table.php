<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configs', function (Blueprint $table) {
            $table->id();
            $table->string('theme')->nullable()->default('default');
            $table->string('theme_color')->nullable()->default('#1bb90d');
            $table->string('home_view_mode')->nullable()->default('grid');
            $table->string('product_view_mode')->nullable()->default('grid');
            $table->boolean('is_whatsapp_checkout')->default(false);
            $table->boolean('is_guest_checkout')->default(false);
            $table->boolean('is_payment_gateway')->default(false);
            $table->text('cod_list')->nullable();
            $table->boolean('is_notifypro')->default(false);
            $table->tinyInteger('notifypro_interval')->default(20);
            $table->tinyInteger('notifypro_timeout')->default(4);
            $table->string('rajaongkir_type')->nullable();
            $table->string('rajaongkir_apikey')->nullable();
            $table->string('rajaongkir_couriers')->nullable();
            $table->integer('warehouse_id')->nullable();
            $table->text('warehouse_address')->nullable();
            $table->string('telegram_bot_token')->nullable();
            $table->string('telegram_user_id')->nullable();
            $table->string('tripay_api_key')->nullable();
            $table->string('tripay_private_key')->nullable();
            $table->string('tripay_merchant_code')->nullable();
            $table->string('tripay_mode')->nullable()->default('sanbox')->comment('sanbox,production');
       
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('configs');
    }
}
