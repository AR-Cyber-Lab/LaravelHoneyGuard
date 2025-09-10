<?php
namespace ARCyberLab\HoneyGuard\Console; use Illuminate\Console\Command; use Illuminate\Support\Facades\DB;
class ExportCommand extends Command{ protected $signature='honeyguard:export {--format=json : json|csv} {--path=storage/app/honeyguard-export}'; protected $description='Export HoneyGuard events to JSON or CSV';
 public function handle(): int{ $format=strtolower($this->option('format')); $path=base_path($this->option('path')); $events=DB::table('honey_events')->orderBy('id')->get(); if(!is_dir(dirname($path))) @mkdir(dirname($path),0777,true);
  if($format==='csv'){ $file=$path.(str_ends_with($path,'.csv')?'':'.csv'); $fp=fopen($file,'w'); fputcsv($fp,['id','type','vector','ip','ua','request','created_at','updated_at']); foreach($events as $e){ fputcsv($fp,[$e->id,$e->type,$e->vector,$e->ip,$e->ua,$e->request,$e->created_at,$e->updated_at]); } fclose($fp); }
  else{ $file=$path.(str_ends_with($path,'.json')?'':'.json'); file_put_contents($file, json_encode($events, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES)); }
  $this->info('Exported to: '.$file); return self::SUCCESS; } }