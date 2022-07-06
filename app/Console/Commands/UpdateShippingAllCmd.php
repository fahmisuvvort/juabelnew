<?php

namespace App\Console\Commands;

use App\Jobs\CityUpdateJb;
use App\Jobs\ProvinceUpdateJb;
use App\Jobs\SubdistrictUpdateJb;
use App\Models\Subdistrict;
use Illuminate\Console\Command;

class UpdateShippingAllCmd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shipping:update-all';

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
        ProvinceUpdateJb::dispatch();
        CityUpdateJb::dispatch();
        SubdistrictUpdateJb::dispatch();

        $this->info('update running');
    }
}
