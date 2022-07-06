<?php

namespace App\Jobs;

use App\Models\Subdistrict;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SubdistrictUpdateJb implements ShouldQueue
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
        $cities = DB::connection('rajaongkir')->table('cities')->get();
        Subdistrict::truncate();

        foreach($cities as $city) {
            $subdistricts =  DB::connection('rajaongkir')->table('subdistricts')->where('city_id', $city->city_id)->get();
            $subdistricts = collect($subdistricts)->map(function($x){ return (array) $x; })->toArray(); 
            Subdistrict::insert($subdistricts);
        }
    }
}
