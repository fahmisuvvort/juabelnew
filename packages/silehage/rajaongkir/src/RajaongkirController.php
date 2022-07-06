<?php

namespace Silehage\Rajaongkir;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Silehage\Rajaongkir\Facades\Rajaongkir;

class RajaongkirController extends Controller
{

    public function getProvince()
    {
        $result = Rajaongkir::province();

        return $result;
    }
    public function getCity($provinceId)
    {
        $result = Rajaongkir::city($provinceId);

        return $result;
    }
    public function getSubdistrict($provinceId)
    {
        $result = Rajaongkir::subdistric($provinceId);

        return $result;
    }
    public function getCost(Request $request)
    {
       $data = $request->validate([
           'origin' => ['required', 'string'],
           'destination' => ['required', 'string'],
           'weight' => ['required'],
           'courier' => ['required', 'string'],
       ]);

        $result = Rajaongkir::cost($data);

        return $result;
    }
}
