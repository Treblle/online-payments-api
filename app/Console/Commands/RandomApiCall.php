<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RandomApiCall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:random-api-call';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Call a random API endpoint with random data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $baseUrl = config('app.url');
        $endpoints = $this->getRandomEndpoints();
        
        $randomEndpoint = $endpoints[array_rand($endpoints)];
        $url = $baseUrl . '/api/v2' . $randomEndpoint['path'];
        
        $this->info("Calling endpoint: {$randomEndpoint['method']} {$url}");
        
        try {
            $response = Http::withHeaders([
                'Request-ID' => uniqid('random_'),
                'Merchant-ID' => rand(pow(10, 12 - 1), pow(10, 12) - 1),
                'Content-Type' => 'application/json'
            ])->timeout(30);
            
            if ($randomEndpoint['method'] === 'GET') {
                $response = $response->get($url);
            } else {
                $response = $response->post($url, $randomEndpoint['data']);
            }
            
            if ($response->successful()) {
                $this->info("✓ Request successful: {$response->status()}");
                Log::info("Random API call successful", [
                    'endpoint' => $randomEndpoint['path'],
                    'method' => $randomEndpoint['method'],
                    'status' => $response->status()
                ]);
            } else {
                $this->error("✗ Request failed: {$response->status()}");
                Log::warning("Random API call failed", [
                    'endpoint' => $randomEndpoint['path'],
                    'method' => $randomEndpoint['method'],
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
            }
            
        } catch (\Exception $e) {
            $this->error("✗ Exception occurred: {$e->getMessage()}");
            Log::error("Random API call exception", [
                'endpoint' => $randomEndpoint['path'],
                'method' => $randomEndpoint['method'],
                'error' => $e->getMessage()
            ]);
        }
    }
    
    private function getRandomEndpoints(): array
    {
        return [
            [
                'path' => '/payments/txn_'.uniqid(),
                'method' => 'GET',
                'data' => []
            ],
            [
                'path' => '/refunds/txn_'.uniqid(),
                'method' => 'GET',
                'data' => []
            ],
            [
                'path' => '/payments',
                'method' => 'GET',
                'data' => []
            ],
            [
                'path' => '/payments',
                'method' => 'POST',
                'data' => [
                    'amount' => rand(100, 10000),
                    'currency' => 'USD',
                    'captureMethod' => rand(0, 1) ? 'NOW' : 'LATER',
                    'paymentMethodType' => [
                        'card' => [
                            'accountNumber' => '4111111111111111',
                            'expiryMonth' => '12',
                            'expiryYear' => '2025',
                            'cvv' => '123'
                        ]
                    ]
                ]
            ],
            [
                'path' => '/refunds',
                'method' => 'POST',
                'data' => [
                    'amount' => rand(100, 5000),
                    'currency' => 'USD',
                    'parentTransactionId' => 'txn_' . uniqid()
                ]
            ],
            [
                'path' => '/verifications',
                'method' => 'POST',
                'data' => [
                    'currency' => 'USD',
                    'paymentMethodType' => [
                        'card' => [
                            'accountNumber' => '4111111111111111',
                            'expiryMonth' => '12',
                            'expiryYear' => '2025',
                            'cvv' => '123'
                        ]
                    ]
                ]
            ],
            [
                'path' => '/fraudcheck',
                'method' => 'POST',
                'data' => [
                    'amount' => rand(100, 10000),
                    'currency' => 'USD',
                    'paymentMethodType' => [
                        'card' => [
                            'accountNumber' => '4111111111111111'
                        ]
                    ],
                    'customerInfo' => [
                        'email' => 'test@example.com',
                        'ipAddress' => '192.168.1.' . rand(1, 255)
                    ]
                ]
            ]
        ];
    }
}
