<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function testRegisterSuccess()
    {
        $this->post('/api/users', [
            'username' => 'john_doe',
            'password' => 'password',
            'name' => 'John Doe',
        ])->assertStatus(201)
            ->assertJson([
                "data" => [
                    "username" => "john_doe",
                    "name" => "John Doe",
                ]
            ]);
    }

    public function testRegisterFailed()
    {
        $this->post('/api/users', [
            'username' => '',
            'password' => '',
            'name' => ''
        ])->assertStatus(400)
            ->assertJson([
                "errors" => [
                    'username' => [
                        "The username field is required."
                    ],
                    'password' => [
                        "The password field is required."
                    ],
                    'name' => [
                        "The name field is required."
                    ]
                ]
            ]);
    }

    public function testRegisterFailedUsernameExists()
    {
        $this->testRegisterSuccess();
        $this->post('/api/users', [
            'username' => 'john_doe',
            'password' => 'password',
            'name' => 'John Doe',
        ])->assertStatus(400)
            ->assertJson([
                "errors" => [
                    "username" => ["Username already exists"]
                ]
            ]);
    }
}
