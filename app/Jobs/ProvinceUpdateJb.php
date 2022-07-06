<?php

namespace App\Jobs;

use App\Models\Province;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class ProvinceUpdateJb implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Province::truncate();
        
        $provinces = DB::connection('rajaongkir')->table('provinces')->get();
        $provinces = collect($provinces)->map(function($x){ return (array) $x; })->toArray(); 

        if(count($provinces) > 0) {

            Province::insert($provinces);

            // foreach($provinces as $p) {
            //     $province = Province::firstOrNew(['province_id' => $p->province_id]);
    
            //     $province->province_id = $p->province_id;
            //     $province->province = $p->province;
            //     $province->save();
            // }
        }
    }
}
