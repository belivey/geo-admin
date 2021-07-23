<?php
namespace Belivey\LaravelGeoAdmin;

use Illuminate\Support\ServiceProvider;

class LaravelGeoAdminServiceProvider extends ServiceProvider
{
  /**
  * Publishes configuration file.
  *
  * @return  void
  */
  public function boot()
  {
    $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    // $this->publishes([
    //   __DIR__.'/../config/laravel_geo_admin.php' => config_path('laravel_geo_admin.php'),
    // ], 'laravel-geo-admin-config');
  }
  /**
  * Make config publishment optional by merging the config from the package.
  *
  * @return  void
  */
  public function register()
  {
    // $this->mergeConfigFrom(
    //   __DIR__.'/../config/laravel_geo_admin.php', 'laravel_geo_admin'
    // );
  }
}