<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_registration_redirects_to_login(): void
    {
        $response = $this->get('/register');
        $response->assertRedirect(route('login'));

        $responseDaftar = $this->get('/daftar');
        $responseDaftar->assertRedirect(route('login'));
    }
}
