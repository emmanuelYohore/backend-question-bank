<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    public function test_can_create_user()
{
    $data = [
        'name' => 'Emmanuel',
        'surname' => 'okkk',
        'email' => 'emma@test.com',
        'password' => 'secret123'
    ];

    $response = $this->post('/api/v1/auth/register', $data);

    $response->assertStatus(201);
}



}
