<?php
namespace ARCyberLab\HoneyGuard\Tests\Feature; use Orchestra\Testbench\TestCase; use ARCyberLab\HoneyGuard\HoneyGuardServiceProvider;
class HoneyGuardTest extends TestCase{ protected function getPackageProviders($app){ return [HoneyGuardServiceProvider::class]; } public function test_metrics_endpoint_returns_ok(){ $res=$this->get('/metrics/honeyguard'); $res->assertStatus(200); } }