<?php

namespace Tests\Unit;

use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * @dataProvider dataLoginValidData
     */
    public function testLogin(string $email, string $password): void
    {
        $response = $this->postJson('/api/v1/login', [
           'email' => $email,
           'password' => $password
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'email' => $email,
            ]
        ]);
    }

    public static function dataLoginValidData(): array
    {
        return [
            [
                'email@test.com',
                'password'
            ]
        ];
    }

    /**
     * @dataProvider dataLoginInvalidData
     */
    public function testLoginInvalid(string $email, string $password, string $message, int $code)
    {
        $response = $this->postJson('/api/v1/login', [
            'email' => $email,
            'password' => $password
        ]);
        $response->assertStatus($code);
        $response->assertJson([
            'message' => $message
        ]);
    }

    public static function dataLoginInvalidData(): array
    {
        $userNotFound = 'User not found';
        $incorrectLoginOrPassword = 'Incorrect login or password';
        return [
            [
                'emailtest@test.com',
                'password2',
                $userNotFound,
                404
            ],
            [
                'email@test.com',
                'password3',
                $incorrectLoginOrPassword,
                400
            ]
        ];
    }

    /**
     * @dataProvider dataRegisterValidData
     */
    public function testRegister(array $data): void
    {
        $response = $this->postJson('/api/v1/register', $data);
        $response->assertStatus(201);
        $response->assertJson([
            'data' => [
                'email' => $data['email'],
                'name' => $data['name'],
                'surname' => $data['surname']
            ]
        ]);
    }

    public static function dataRegisterValidData(): array
    {
        $data = [];
        for ($i = 1; $i < 10; $i++) {
            $data[] = [
                [
                    'name' => 'Test Name ' . $i,
                    'surname' => 'Test Surname ' . $i,
                    'email' => 'test' . uniqid() . '@' . uniqid() . '.com',
                    'password' => 'password',
                    'password_confirmation' => 'password'
                ]
            ];
        }
        return $data;
    }
}
