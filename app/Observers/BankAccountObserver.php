<?php

namespace App\Observers;

use App\Models\bankAccount;
use Illuminate\Support\Facades\Cache;

class BankAccountObserver
{
    /**
     * Handle the bankAccount "created" event.
     *
     * @param  \App\Models\bankAccount  $bankAccount
     * @return void
     */
    public function created(bankAccount $bankAccount)
    {
        $this->clearCache();
    }

    /**
     * Handle the bankAccount "updated" event.
     *
     * @param  \App\Models\bankAccount  $bankAccount
     * @return void
     */
    public function updated(bankAccount $bankAccount)
    {
        $this->clearCache();
    }

    /**
     * Handle the bankAccount "deleted" event.
     *
     * @param  \App\Models\bankAccount  $bankAccount
     * @return void
     */
    public function deleted(bankAccount $bankAccount)
    {
        $this->clearCache();
    }

    protected function clearCache() 
    {
        Cache::forget('bank_account_count');
        Cache::forget('shop_config');
        Cache::forget('admin_config');
    }

}
