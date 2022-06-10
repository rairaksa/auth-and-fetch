<?php

namespace App\Http\Controllers;

use App\Support\CurrencyManager;
use App\Support\JWTManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProductController extends Controller
{
    private const PRODUCT_ENDPOINT = "https://60c18de74f7e880017dbfd51.mockapi.io/api/v1/jabar-digital-services/product";

    public function fetch()
    {
        $products = $this->get_products();

        return response()->json([
            'status'    => 'success',
            'data'      => $products
        ]);
    }

    public function aggregate()
    {
        // check role must admin
        $credentials = JWTManager::decode_from_request();

        if($credentials->data->role != "admin") {
            return response()->json([
                'status'    => 'failed',
                'message'   => 'Only for admin'
            ], 400);
        }

        $products = $this->get_products();

        return response()->json([
            'status'    => 'success',
            'data'      => $products
        ]);
    }

    public function check()
    {
        $credentials = JWTManager::decode_from_request();

        $data = $credentials->data;

        return response()->json([
            'status'    => 'success',
            'data'      => [
                'id'        => $data->id,
                'nik'       => $data->nik,
                'role'      => $data->role
            ]
        ]);
    }

    private function get_products()
    {
        $products = Http::get(self::PRODUCT_ENDPOINT);

        $products = $products->json();

        $usd_to_idr = CurrencyManager::usd_to_idr();

        foreach($products as $key => $value) {
            $products[$key]["idr_price"] = $value["price"] * $usd_to_idr;
        }

        return $products;
    }
}
