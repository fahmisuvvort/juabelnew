<?php

namespace App\Console\Commands;

use Rajaongkir;
use App\Models\City;
use App\Models\Subdistrict;
use Illuminate\Console\Command;

class UpdateRajaongkirSubdistrict extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rajaongkir:update_subdistrict';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $cities = City::all();

        foreach($cities as $city) {
            sleep(2);

            $subdistricts = Rajaongkir::subdistrict($city->city_id);
    
            $data = json_decode($subdistricts, true);
    
            Subdistrict::insert($data['results']);
        }
    }
}
