<?php

namespace App\Console\Commands;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

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

        $merchant_id = rand(pow(10, 12 - 1), pow(10, 12) - 1);

        $treblleMetadata = [
            'user-id' => Arr::random(['PayPal', 'Rippling', 'Sephora', 'Lyft', 'Domino\'s', 'Macy\'s']),
            'Plan' => Arr::random(['Platinum Plan', 'Titanium Plan', 'Gold Plan']),
            'Region' => Arr::random(['US', 'EU', 'APAC']),
            'Merchant ID' => $merchant_id,
        ];

        $ips = [
            '23.81.13.142', '24.114.50.83', '131.100.7.248', '151.236.22.87',
            '80.216.0.228', '13.55.70.207', '60.240.241.135', '85.203.34.243',
            '106.198.15.213', '217.149.172.85', '5.157.50.151', '174.198.0.163',
            '106.198.15.213', '217.149.172.85', '5.157.50.151', '20.102.199.208',
            '193.93.253.70', '75.99.242.149', '188.121.41.131', '172.56.42.194'
        ];

        $userAgents = [
            'Mozilla/5.0 (X11; U; Linux armv7l like Android; en-us) AppleWebKit/531.2+ (KHTML, like Gecko) Version/5.0 Safari/533.2+ Kindle/3.0+',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Edg/91.0.864.48',
            'Mozilla/5.0 (X11; Linux x86_64; rv:109.0) Gecko/20100101 Firefox/109.0',
            'Mozilla/5.0 (compatible; ClaudeBot/1.0; +https://anthropic.com/bot)',
            'Mozilla/5.0 (compatible; ChatGPT-User/1.0; +https://openai.com/bot)',
            'Mozilla/5.0 (compatible; PerplexityBot/1.0; +https://www.perplexity.ai/bot)'
        ];
        
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Request-ID' => uniqid('random_'),
                'Merchant-ID' => $merchant_id,
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . Str::random(24),
                'user-agent' => Arr::random($userAgents),
                'X-Forwarded-For' => Arr::random($ips),
                'treblle-metadata' => json_encode($treblleMetadata)
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
                    'merchant' => [
                        'merchantCategoryCode' => '4111111111111111',
                        'merchantSoftware' => [
                            'companyName' => 'Treblle Inc.',
                            'productName' => 'Treblle',
                            'version' => '1.0.0'
                        ]
                    ],
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
