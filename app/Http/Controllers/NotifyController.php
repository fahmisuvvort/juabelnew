<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Notifications\OrderNotification;
use Illuminate\Support\Facades\Notification;
use App\Notifications\AdminNotificationOrder;
use App\Notifications\AdminNotificationTelegram;

class NotifyController extends Controller
{
    protected $config;
    public function __construct()
    {
        $this->config =  Config::first();
    }
    public function sendNotify(Request $request)
    {
        if(config('app.env') != 'production') return;

        $order = Order::with('transaction')->where('order_ref', $request->order_ref)->first();

        if(!$order) {
            return response([
                'success' => false,
                'message' => 'No order found.'
            ], 200);
        }

        $order['url'] = $request->url;

        $adminMsg = '';
        $userMsg = '';

        if(!$this->config->is_demo_mode) {

            if($this->sendAdminNotificationOrder($order)) {
    
                $adminMsg = 'OK';
            };

            if($this->sendUserNotificationOrder($order)) {
    
                $userMsg = 'OK';
            };
        }


        return response([
            'adminMsg' => $adminMsg,
            'userMsg' => $userMsg,
        ], 200);
        
    }

    protected function sendAdminNotificationOrder($order)
    {

        if($this->config->is_telegram_ready) {

            try {
                Notification::route('telegram', config('telegram.user_id'))
                    ->notify(new AdminNotificationTelegram($order));
      
                return true;
    
            } catch (\Throwable $th) {
    
                Log::error($th->getMessage());
                return false;
            }

            return false;
            
        }
        if($this->config->is_mail_ready) {
            
            try {
                
                $admin = User::where('role', 'admin')->first();
                
                $admin->notify(new AdminNotificationOrder($order));
                
                return true;
                
            } catch (\Throwable $th) {
                
                Log::error($th->getMessage());
                return false;
            }
        }
        return false;
        
        
    }
    
    protected function sendUserNotificationOrder($order)
    {
        
        if($this->config->is_mail_ready) {
            
            try {
                
                Notification::route('mail', $order->customer_email)
                ->notify(new OrderNotification($order));
                
                return true;
                
            } catch (\Throwable $th) {
                
                Log::error($th->getMessage());
                return false;
            }
        }

        return false;
  
    }
}
