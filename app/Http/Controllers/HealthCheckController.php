<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class HealthCheckController extends Controller
{
    /**
     * Check health status of all services
     */
    public function __invoke(): JsonResponse
    {
        $response = [
            'status' => 'healthy',
            'timestamp' => now()->toIso8601String(),
            'services' => [
                'app' => $this->checkApp(),
                'database' => $this->checkDatabase(),
                'redis' => $this->checkRedis(),
            ],
            'versions' => [
                'php' => phpversion(),
                'laravel' => app()->version(),
                'node' => $this->getNodeVersion(),
            ],
        ];

        // Determine overall status
        $allHealthy = collect($response['services'])->every(fn($service) => $service['status'] === 'healthy');
        $response['status'] = $allHealthy ? 'healthy' : 'degraded';

        return response()->json($response, $allHealthy ? 200 : 503);
    }

    /**
     * Check application health
     */
    private function checkApp(): array
    {
        return [
            'status' => 'healthy',
            'message' => 'Application is running',
        ];
    }

    /**
     * Check database connection
     */
    private function checkDatabase(): array
    {
        try {
            DB::connection()->getPdo();
            return [
                'status' => 'healthy',
                'database' => env('DB_DATABASE', 'unknown'),
                'host' => env('DB_HOST', 'unknown'),
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Check Redis connection
     */
    private function checkRedis(): array
    {
        try {
            Redis::ping();
            return [
                'status' => 'healthy',
                'host' => env('REDIS_HOST', 'unknown'),
                'port' => env('REDIS_PORT', 'unknown'),
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get Node.js version from environment
     */
    private function getNodeVersion(): string
    {
        return env('NODE_VERSION', 'unknown');
    }
}
