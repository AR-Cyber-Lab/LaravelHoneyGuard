<?php
use Illuminate\Support\Facades\Route; use ARCyberLab\HoneyGuard\Http\Controllers\HoneypotController; use Illuminate\Support\Facades\DB;
Route::middleware([])->group(function(){
 if(config('honeyguard.decoys.wp-login.php',true)) Route::any('/wp-login.php',[HoneypotController::class,'decoy'])->name('honeyguard.decoy.wp');
 if(config('honeyguard.decoys.phpmyadmin',true)) Route::any('/phpmyadmin',[HoneypotController::class,'decoy'])->name('honeyguard.decoy.pma');
 if(config('honeyguard.decoys.server-status',true)) Route::any('/server-status',[HoneypotController::class,'decoy'])->name('honeyguard.decoy.status');
 if(config('honeyguard.decoys.admin',true)) Route::any('/admin',[HoneypotController::class,'decoy'])->name('honeyguard.decoy.admin');
 if(config('honeyguard.canary.health',true)) Route::get('/__health',[HoneypotController::class,'canary'])->name('honeyguard.canary.health');
 if(config('honeyguard.canary.well_known',true)) Route::get('/.well-known/honey-{token}.txt',[HoneypotController::class,'canary'])->where('token','[A-Za-z0-9\-]+')->name('honeyguard.canary.wellknown');
 Route::get('/metrics/honeyguard', function(){ $token=request()->bearerToken(); $cfg=config('honeyguard.metrics_token'); if($cfg && $token!==$cfg) return response('unauthorized',401);
  $total=DB::table('honey_events')->count(); $last24=DB::table('honey_events')->where('created_at','>=',now()->subDay())->count(); $ips=DB::table('honey_events')->select('ip', DB::raw('count(*) as c'))->groupBy('ip')->orderByDesc('c')->limit(10)->get();
  $lines=[]; $lines[]='# HELP honeyguard_events_total Total events'; $lines[]='# TYPE honeyguard_events_total counter'; $lines[]='honeyguard_events_total '.$total; $lines[]='# HELP honeyguard_events_24h Events in last 24h'; $lines[]='# TYPE honeyguard_events_24h gauge'; $lines[]='honeyguard_events_24h '.$last24; foreach($ips as $i){ $ip=$i->ip?:'unknown'; $lines[]='honeyguard_top_ip{ip="'.addslashes($ip).'"} '.$i->c; } return response(implode("\n",$lines)."\n",200)->header('Content-Type','text/plain; version=0.0.4'); })->name('honeyguard.metrics');
 if(config('honeyguard.panel_enabled',true)){ Route::middleware(config('honeyguard.panel_middleware',['web','auth','can:honeyguard.view']))->get(config('honeyguard.panel_route','/honeyguard/panel'), fn()=>view('honeyguard::panel.index'))->name('honeyguard.panel'); }
});