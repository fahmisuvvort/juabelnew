<?php

namespace App\Console\Commands;

use App\Models\Province;
use Illuminate\Console\Command;
use Rajaongkir;

class UpdateProvince extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rajaongkir:update_province';

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
        $provinces = Rajaongkir::province();

        $data = json_decode($provinces, true);

        Province::insert($data['results']);
    }
}
