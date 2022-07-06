<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\ProductVarian;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\ProductVariantValue;
use Illuminate\Support\Facades\Log;

class ResetStockByOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stock:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset product stock if order axpired';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $orderExpireds = Order::where('created_at', '<', Carbon::now()->subdays(2))->where('order_status', 'UNPAID')->get();

        if(count($orderExpireds) > 0) {

            
            foreach($orderExpireds as $order) {

                $orderItems = OrderItem::where('order_id', $order->id)->get();

                foreach($orderItems as $item) {

                    $productData = Product::where('sku', $item->sku)->first();
                    if($productData) {
                        $productData->stock += $item->quantity;
                        $productData->save();

                    } else {

                        $variantData = ProductVarian::where('sku', $item->sku)->first();
                        if($variantData) {
                            $productData->stock += $item->quantity;
                            $variantData->save();
                        }
                    }
                }

                $order->update(['order_status' => 'CANCELED']);
            }

        }

        $this->info(count($orderExpireds));
    }
}
