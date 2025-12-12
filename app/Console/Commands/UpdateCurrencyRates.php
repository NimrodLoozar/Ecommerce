<?php

namespace App\Console\Commands;

use App\Models\Currency;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UpdateCurrencyRates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currency:update-rates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update currency exchange rates from external API';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Updating currency exchange rates...');

        try {
            // Use exchangerate-api.com (free tier: 1,500 requests/month)
            // Alternative: fixer.io, currencyapi.com, exchangeratesapi.io
            $apiKey = config('services.exchangerate.key', 'demo');
            $baseCurrency = config('payment.currency', 'EUR');
            
            // If no API key is configured, skip update
            if ($apiKey === 'demo') {
                $this->warn('Exchange rate API key not configured. Using demo mode.');
                $this->info('Add EXCHANGERATE_API_KEY to .env for live updates.');
                return self::SUCCESS;
            }

            $response = Http::timeout(10)
                ->get("https://v6.exchangerate-api.com/v6/{$apiKey}/latest/{$baseCurrency}");

            if (!$response->successful()) {
                $this->error('Failed to fetch exchange rates from API.');
                Log::error('Currency rate update failed', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return self::FAILURE;
            }

            $data = $response->json();

            if ($data['result'] !== 'success') {
                $this->error('API returned error: ' . ($data['error-type'] ?? 'Unknown error'));
                return self::FAILURE;
            }

            $rates = $data['conversion_rates'];
            $updatedCount = 0;

            // Update or create currency records
            foreach ($rates as $code => $rate) {
                Currency::updateOrCreate(
                    ['code' => $code],
                    [
                        'name' => $this->getCurrencyName($code),
                        'symbol' => $this->getCurrencySymbol($code),
                        'exchange_rate' => $rate,
                    ]
                );

                $updatedCount++;
                $this->line("Updated {$code}: {$rate}");
            }

            $this->info("Successfully updated {$updatedCount} currency rate(s).");

            Log::info('Currency rates updated', [
                'base_currency' => $baseCurrency,
                'rates_count' => $updatedCount,
                'timestamp' => now(),
            ]);

            return self::SUCCESS;

        } catch (\Exception $e) {
            $this->error('Error updating currency rates: ' . $e->getMessage());
            Log::error('Currency rate update exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return self::FAILURE;
        }
    }

    /**
     * Get human-readable currency name
     */
    private function getCurrencyName(string $code): string
    {
        $names = [
            'EUR' => 'Euro',
            'USD' => 'US Dollar',
            'GBP' => 'British Pound',
            'JPY' => 'Japanese Yen',
            'CHF' => 'Swiss Franc',
            'CAD' => 'Canadian Dollar',
            'AUD' => 'Australian Dollar',
            'CNY' => 'Chinese Yuan',
            'INR' => 'Indian Rupee',
            'BRL' => 'Brazilian Real',
            'ZAR' => 'South African Rand',
            'MXN' => 'Mexican Peso',
            'SGD' => 'Singapore Dollar',
            'HKD' => 'Hong Kong Dollar',
            'NOK' => 'Norwegian Krone',
            'SEK' => 'Swedish Krona',
            'DKK' => 'Danish Krone',
            'PLN' => 'Polish Zloty',
            'THB' => 'Thai Baht',
            'IDR' => 'Indonesian Rupiah',
            'HUF' => 'Hungarian Forint',
            'CZK' => 'Czech Koruna',
            'ILS' => 'Israeli Shekel',
            'CLP' => 'Chilean Peso',
            'PHP' => 'Philippine Peso',
            'AED' => 'UAE Dirham',
            'COP' => 'Colombian Peso',
            'SAR' => 'Saudi Riyal',
            'MYR' => 'Malaysian Ringgit',
            'RON' => 'Romanian Leu',
        ];

        return $names[$code] ?? $code;
    }

    /**
     * Get currency symbol
     */
    private function getCurrencySymbol(string $code): string
    {
        $symbols = [
            'EUR' => '€',
            'USD' => '$',
            'GBP' => '£',
            'JPY' => '¥',
            'CHF' => 'Fr',
            'CAD' => 'C$',
            'AUD' => 'A$',
            'CNY' => '¥',
            'INR' => '₹',
            'BRL' => 'R$',
            'ZAR' => 'R',
            'MXN' => 'Mex$',
            'SGD' => 'S$',
            'HKD' => 'HK$',
            'NOK' => 'kr',
            'SEK' => 'kr',
            'DKK' => 'kr',
            'PLN' => 'zł',
            'THB' => '฿',
            'IDR' => 'Rp',
            'HUF' => 'Ft',
            'CZK' => 'Kč',
            'ILS' => '₪',
            'CLP' => '$',
            'PHP' => '₱',
            'AED' => 'د.إ',
            'COP' => '$',
            'SAR' => '﷼',
            'MYR' => 'RM',
            'RON' => 'lei',
        ];

        return $symbols[$code] ?? $code;
    }
}
