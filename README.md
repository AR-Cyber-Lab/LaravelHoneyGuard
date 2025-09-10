# ARCyberLab HoneyGuard (Laravel 10/11/12)
Honeypot + decoy routes + canary + Livewire panel + queued alerts + export/purge + IP autoblock + profiles + Prometheus metrics + canary rotator + telemetry webhook.
Install:
composer require arcyberlab/honeyguard
php artisan vendor:publish --provider="ARCyberLab\HoneyGuard\HoneyGuardServiceProvider" --tag=honeyguard-config --tag=honeyguard-views --tag=honeyguard-migrations
php artisan migrate
Middleware (Laravel 12/11, bootstrap/app.php):
->withMiddleware(fn ($m) => $m->append(\ARCyberLab\HoneyGuard\Http\Middleware\HoneyPot::class))
Blade:
<x-honeyguard::components.honeypot />
Env: HONEYGUARD_LOG_CHANNEL=honey, HONEYGUARD_NOTIFY_MAIL_TO=alerts@domain.tld, HONEYGUARD_SLACK_WEBHOOK=..., HONEYGUARD_QUEUE_NOTIFICATIONS=true, HONEYGUARD_AUTOBLOCK=true
Commands: honeyguard:export, honeyguard:purge, honeyguard:rotate-canary
Metrics: GET /metrics/honeyguard
Panel: /honeyguard/panel (web,auth,can:honeyguard.view)
