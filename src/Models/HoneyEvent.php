<?php
namespace ARCyberLab\HoneyGuard\Models; use Illuminate\Database\Eloquent\Model; use Illuminate\Database\Eloquent\Prunable;
class HoneyEvent extends Model{ use Prunable; protected $table='honey_events'; protected $guarded=[]; public function prunable(){ return static::where('created_at','<',now()->subDays((int)config('honeyguard.retention_days',90))); } }