<?php

namespace App\Support;

use Illuminate\Support\Facades\Http;

class CurrencyManager
{
    protected const CURRENCY_ENDPOINT = "https://api.apilayer.com/fixer/latest?base=USD&symbols=IDR";
    protected const DEFAULT_IDR = "14500";

    public static function usd_to_idr()
    {
        $currency = Http::withHeaders([
            'apikey' => config('currency.key')
        ])->get(self::CURRENCY_ENDPOINT);

        $currency_data = $currency->json();

        return $currency_data->rates["IDR"] ?? self::DEFAULT_IDR;
    }
}
