## Laravel Starsender

Starsender packages for laravel

### Installation:

If you don't use auto-discovery, add the ServiceProvider to the providers array in config/app.php

```php
Silehage\Starsender\StarsenderServiceProvider::class,
```

If you want to use the facade, add this to your facades in app.php:

```php
'Starsender' => Silehage\Starsender\Facades\Starsender::class,