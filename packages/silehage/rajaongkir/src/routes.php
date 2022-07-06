<?php 
use Illuminate\Support\Facades\Route;

Route::get('rajaongkir/province',[Silehage\Rajaongkir\RajaongkirController::class, 'getProvince']);
Route::get('rajaongkir/city/{provinceId}',[Silehage\Rajaongkir\RajaongkirController::class, 'getCity']);
Route::get('rajaongkir/subdistrict/{cityId}',[Silehage\Rajaongkir\RajaongkirController::class, 'getSubdistrict']);
Route::get('rajaongkir/cost',[Silehage\Rajaongkir\RajaongkirController::class, 'getCost']);

?>