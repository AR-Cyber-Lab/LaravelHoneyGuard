<?php
namespace ARCyberLab\HoneyGuard\Services;
use Illuminate\Support\Facades\{Log,DB,Notification,Http,RateLimiter};
use ARCyberLab\HoneyGuard\Notifications\HoneyTrapNotification; use ARCyberLab\HoneyGuard\Jobs\SendSlackWebhookJob; use ARCyberLab\HoneyGuard\Jobs\SendTelemetryJob;
class HoneyService{
 public function record(array $p): void{ $ch=config('honeyguard.log_channel'); ($ch?Log::channel($ch):Log::getLogger())->info('HoneyTrap',$p);
  try{ DB::table('honey_events')->insert(['type'=>$p['type']??null,'vector'=>$p['vector']??null,'ip'=>$p['ip']??null,'ua'=>$p['ua']??null,'request'=>json_encode($p['extra']??[]),'created_at'=>now(),'updated_at'=>now()]); }catch(\Throwable $e){ Log::warning('HoneyGuard DB insert failed: '.$e->getMessage()); } }
 public function notify(array $p): void{
  $queued=(bool)config('honeyguard.queue_notifications',true);
  if($to=config('honeyguard.notify_mail_to')){ $n=new HoneyTrapNotification($p); Notification::route('mail',$to)->notify($queued?$n->onQueue('mail'):$n); }
  if($hook=config('honeyguard.notify_slack_webhook')){ if($queued){ dispatch(new SendSlackWebhookJob($hook,$p))->onQueue('default'); } else { try{ Http::timeout(3)->post($hook,['text'=>sprintf(':rotating_light: HoneyGuard %s on `%s` from %s (%s)',$p['type']??'-',$p['vector']??'-',$p['ip']??'-',$p['ua']??'-')]); }catch(\Throwable $e){ Log::warning('HoneyGuard Slack post failed: '.$e->getMessage()); } } }
  if(config('honeyguard.telemetry.enabled') && ($ep=config('honeyguard.telemetry.endpoint'))){ dispatch(new SendTelemetryJob($ep, config('honeyguard.telemetry.token'), $p))->onQueue('default'); } }
 public function tarpit(): void{ $min=(int)config('honeyguard.tarpit_min_ms',1000); $max=(int)config('honeyguard.tarpit_max_ms',3000); if($max>0&&$max>=$min) usleep(random_int($min,$max)*1000); }
 public function countAndMaybeBlock(string $ip): void{ if(!config('honeyguard.autoblock.enabled')) return; $thr=(int)config('honeyguard.autoblock.threshold',10); $win=(int)config('honeyguard.autoblock.window_minutes',15);
  $key='honeyguard:abuse:'.$ip; RateLimiter::hit($key,$win*60); if(\Illuminate\Support\Facades\RateLimiter::tooManyAttempts($key,$thr)) $this->autoBlock($ip); }
 public function autoBlock(string $ip): void{ $drv=config('honeyguard.autoblock.driver','logonly'); Log::warning('HoneyGuard autoblock triggered',['ip'=>$ip,'driver'=>$drv]);
  if($drv==='cloudflare'){ $api=config('honeyguard.autoblock.cloudflare'); try{ Http::withToken($api['api_token'])->post('https://api.cloudflare.com/client/v4/user/firewall/access_rules/rules',['mode'=>$api['mode']??'block','configuration'=>['target'=>'ip','value'=>$ip],'notes'=>$api['notes']??'HoneyGuard autoblock']); }catch(\Throwable $e){ Log::warning('Cloudflare autoblock failed: '.$e->getMessage()); } } }
}