<?php
return [
 'profile'=>env('HONEYGUARD_PROFILE','minimal'),
 'profiles'=>['minimal'=>['decoys'=>true,'canary'=>true,'tarpit'=>true],'strict'=>['decoys'=>true,'canary'=>true,'tarpit'=>true],'stealth'=>['decoys'=>false,'canary'=>true,'tarpit'=>true]],
 'honeypot_field'=>env('HONEYGUARD_HONEYPOT_FIELD','website'),'timestamp_field'=>env('HONEYGUARD_TIMESTAMP_FIELD','hp_ts'),
 'min_submit_seconds'=>env('HONEYGUARD_MIN_SUBMIT_SECONDS',3),'tarpit_min_ms'=>env('HONEYGUARD_TARPIT_MIN_MS',1000),'tarpit_max_ms'=>env('HONEYGUARD_TARPIT_MAX_MS',3000),
 'decoys'=>['wp-login.php'=>true,'phpmyadmin'=>true,'server-status'=>true,'admin'=>true],'canary'=>['health'=>true,'well_known'=>true],
 'notify_mail_to'=>env('HONEYGUARD_NOTIFY_MAIL_TO', env('MAIL_FROM_ADDRESS')),'notify_slack_webhook'=>env('HONEYGUARD_SLACK_WEBHOOK'),'queue_notifications'=>env('HONEYGUARD_QUEUE_NOTIFICATIONS',true),
 'log_channel'=>env('HONEYGUARD_LOG_CHANNEL','honey'),'retention_days'=>env('HONEYGUARD_RETENTION_DAYS',90),
 'autoblock'=>['enabled'=>env('HONEYGUARD_AUTOBLOCK',false),'threshold'=>env('HONEYGUARD_AUTOBLOCK_THRESHOLD',10),'window_minutes'=>env('HONEYGUARD_AUTOBLOCK_WINDOW',15),'driver'=>env('HONEYGUARD_AUTOBLOCK_DRIVER','logonly'),
   'cloudflare'=>['api_token'=>env('HONEYGUARD_CF_API_TOKEN'),'zone_id'=>env('HONEYGUARD_CF_ZONE_ID'),'mode'=>env('HONEYGUARD_CF_MODE','block'),'notes'=>env('HONEYGUARD_CF_NOTES','HoneyGuard autoblock')]],
 'metrics_token'=>env('HONEYGUARD_METRICS_TOKEN'), 'panel_enabled'=>env('HONEYGUARD_PANEL',true),'panel_route'=>env('HONEYGUARD_PANEL_ROUTE','/honeyguard/panel'),'panel_middleware'=>['web','auth','can:honeyguard.view'],
 'telemetry'=>['endpoint'=>env('HONEYGUARD_TELEMETRY_ENDPOINT'),'token'=>env('HONEYGUARD_TELEMETRY_TOKEN'),'enabled'=>env('HONEYGUARD_TELEMETRY',false)],
];