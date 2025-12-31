<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testLogin()
    {
        $response = $this->post('/login', [
            'email' => 'admin@123',
            'password' => '123',
            'remember' => true, // Atur true jika ingin menguji fungsi remember me
        ]);

        $response->assertStatus(302); // Menguji apakah redirect berhasil

        // Menguji apakah cookie remember_token telah diatur
        $this->assertAuthenticated();
        $this->assertTrue($response->headers->getCookies()[0]->getName() === 'remember_token');
    }
}
