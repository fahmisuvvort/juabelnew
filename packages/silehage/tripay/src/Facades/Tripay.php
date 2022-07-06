<?php

namespace Silehage\Tripay\Facades;

use Illuminate\Support\Facades\Facade;

class Tripay extends Facade
{
  public static function getFacadeAccessor()
  {
    return 'Tripay';
  }
}

?>