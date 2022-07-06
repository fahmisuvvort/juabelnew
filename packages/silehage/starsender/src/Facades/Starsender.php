<?php

namespace Silehage\Starsender\Facades;

use Illuminate\Support\Facades\Facade;

class Starsender extends Facade
{
  public static function getFacadeAccessor()
  {
    return 'starsender';
  }
}

?>