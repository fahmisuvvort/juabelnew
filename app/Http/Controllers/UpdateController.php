<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;

class UpdateController extends Controller
{
    protected  $migrations, $dbMigrations;

    public function __construct()
    {
        $this->migrations = $this->getMigrations();
        $this->dbMigrations = $this->getExecutedMigrations();
    }

    public function clearCache()
    {

        Cache::flush();

        try {

            Artisan::call('cache:clear');
            Artisan::call('config:clear');

            return response()->json(['success' => true ], 200);


        } catch (\Throwable $th) {

            return response()->json([
                'sucess' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function overview()
    {
       return response()->json([
           'suceess' => true,
           'results' => count($this->migrations) - count($this->dbMigrations)
       ]);
    }
    
    public function update()
    {
        DB::beginTransaction();
        
        try {
            
            Artisan::call('migrate', ['--force' => true]);

            DB::commit();
            
            return response()->json([
                'sucess' => true
                ], 200);

        } catch (\Throwable $th) {

            DB::rollBack();

            return response()->json([
                'sucess' => false,
                'message' => $th->getMessage()
                ], 500);
        }
    }
    
    public function getMigrations()
    {
        $migrations = glob(database_path().DIRECTORY_SEPARATOR.'migrations'.DIRECTORY_SEPARATOR.'*.php');

        return str_replace('.php', '', $migrations);
    }

    /**
     * Get the migrations that have already been ran.
     *
     * @return \Illuminate\Support\Collection List of migrations
     */
    public function getExecutedMigrations()
    {
        return DB::table('migrations')->get()->pluck('migration');
    }
}
