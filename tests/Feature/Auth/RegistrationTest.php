<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_registration_page_can_be_rendered(): void
    {
        $responseDaftar = $this->get('/daftar');
        $responseDaftar->assertStatus(200);
    }
}
