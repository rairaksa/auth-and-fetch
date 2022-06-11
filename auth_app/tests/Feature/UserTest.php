<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_register()
    {
        $response = $this->post('/api/auth/register', [
            'nik'   => $this->random_string(16),
            'role'  => 'admin'
        ]);

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function it_can_login()
    {
        // generate user
        $response = $this->post('/api/auth/register', [
            'nik'   => $this->random_string(16),
            'role'  => 'admin'
        ]);

        $nik = $response["data"]["nik"];
        $password = $response["data"]["password"];

        // login
        $response = $this->post('/api/auth/login', [
            'nik'   => $nik,
            'password'  => $password
        ]);

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function is_valid_token()
    {
        // generate user
        $response = $this->post('/api/auth/register', [
            'nik'   => $this->random_string(16),
            'role'  => 'admin'
        ]);

        $nik = $response["data"]["nik"];
        $password = $response["data"]["password"];

        // login
        $response = $this->post('/api/auth/login', [
            'nik'       => $nik,
            'password'  => $password
        ]);

        $token = $response["data"]["token"];

        // validate token
        $response = $this->withToken($token)->post('/api/auth/check');

        $response->assertStatus(200);
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
