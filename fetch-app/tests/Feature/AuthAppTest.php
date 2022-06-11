<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AuthAppTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_login()
    {
        $auth_app_url = env("AUTH_APP_URL", "http://localhost:8000");

        // generate user
        $response = Http::post($auth_app_url . '/api/auth/register', [
            'nik'   => $this->random_string(16),
            'role'  => 'admin'
        ]);

        $response = $response->json();

        $nik = $response["data"]["nik"];
        $password = $response["data"]["password"];

        // login
        $response = Http::post($auth_app_url . '/api/auth/login', [
            'nik'   => $nik,
            'password'  => $password
        ]);

        $response = $response->json();

        $this->assertNotEmpty($response["data"]["token"]);
    }

    private function random_string($length = 16) {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
