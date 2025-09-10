<?php
namespace ARCyberLab\HoneyGuard;
use Illuminate\Support\ServiceProvider; use Illuminate\Support\Facades\Event; use Illuminate\Support\Facades\Gate;
use ARCyberLab\HoneyGuard\Events\HoneyTrapTriggered; use ARCyberLab\HoneyGuard\Listeners\HandleHoneyTrap;
class HoneyGuardServiceProvider extends ServiceProvider{
 public function register(): void{ $this->mergeConfigFrom(__DIR__.'/../config/honeyguard.php','honeyguard'); }
 public function boot(): void{
  $this->loadRoutesFrom(__DIR__.'/../routes/honeyguard.php'); $this->loadViewsFrom(__DIR__.'/../resources/views','honeyguard');
  Event::listen(HoneyTrapTriggered::class, HandleHoneyTrap::class);
  Gate::define('honeyguard.view', fn($user)=>true);
  $this->publishes([__DIR__.'/../config/honeyguard.php'=>config_path('honeyguard.php')],'honeyguard-config');
  $this->publishes([__DIR__.'/../resources/views'=>resource_path('views/vendor/honeyguard')],'honeyguard-views');
  $this->publishes([__DIR__.'/../database/migrations'=>database_path('migrations')],'honeyguard-migrations');
  if($this->app->runningInConsole()){ $this->commands([\ARCyberLab\HoneyGuard\Console\ExportCommand::class, \ARCyberLab\HoneyGuard\Console\PurgeCommand::class, \ARCyberLab\HoneyGuard\Console\RotateCanaryCommand::class]); }
 }}