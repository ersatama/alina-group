<?php

namespace Tests\Unit;

use Tests\TestCase;

class TaskTest extends TestCase
{
    protected string $token = '';
    public function setUp(): void
    {
        parent::setUp();
        $response = $this->postJson('/api/v1/login', [
            'email' => 'email@test.com',
            'password' => 'password'
        ]);
        $content = $response->decodeResponseJson();
        $this->token = $content['data']['token'];
    }

    public function testGet(): void
    {
        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token
        ])->postJson('/api/v1/task/get');
        $response->assertStatus(200);
        $data = json_decode($response->content(), true);
        $this->assertArrayHasKey('data', $data);
    }

    /**
     * @dataProvider dataGetByIdValidData
     */
    public function testGetByIdValidData($id): void
    {
        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token
        ])->getJson('/api/v1/task/getById/' . $id);
        $response->assertStatus(200);
        $data = json_decode($response->content(), true);
        $this->assertArrayHasKey('data', $data);
    }

    public static function dataGetByIdValidData(): array
    {
        return [
            [10],
            [11]
        ];
    }

    /**
     * @dataProvider dataGetByIdInvalidData
     */
    public function testGetByIdInvalidData($id, $code, $message): void
    {
        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token
        ])->getJson('/api/v1/task/getById/' . $id);
        $response->assertStatus($code);
        $response->assertJson([
            'message' => $message
        ]);
    }

    public static function dataGetByIdInvalidData(): array
    {
        return [
            [1345345345345345345345345, 404, 'Task not found'],
            [1243123, 404, 'Task not found'],
        ];
    }

    /**
     * @dataProvider dataCreate
     */
    public function testCreate(array $data): void
    {
        $response = $this->postJson('/api/v1/task/create', $data);
        $response->assertStatus(201);
        $response->assertJson([
            'data' => [
                'title' => $data['title'],
                'description' => $data['description'],
                'priority' => $data['priority']
            ]
        ]);
    }

    public static function dataCreate(): array
    {
        $priority = ['low', 'medium', 'high'];
        $data = [];
        for ($i = 1; $i < 10; $i++) {
            $data[] = [
                [
                    'title' => 'Test Title ' . $i,
                    'description' => 'Test Description ' . $i,
                    'priority' => $priority[array_rand($priority, 1)],
                    'status' => 'active',
                    'expired_at' => '2024-04-24 12:00:00'
                ]
            ];
        }
        return $data;
    }

    /**
     * @dataProvider dataUpdate
     */
    public function testUpdate(int $id, array $data): void
    {
        $response = $this->putJson('/api/v1/task/update/' . $id, $data);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'title' => $data['title'],
                'description' => $data['description'],
                'priority' => $data['priority']
            ]
        ]);
    }

    public static function dataUpdate(): array
    {
        $priority = ['low', 'medium', 'high'];
        $data = [];
        for ($i = 10; $i < 15; $i++) {
            $data[] = [
                $i,
                [
                    'title' => 'Test Title ' . $i,
                    'description' => 'Test Description ' . $i,
                    'priority' => $priority[array_rand($priority, 1)],
                    'status' => 'active',
                    'expired_at' => '2024-04-24 12:00:00'
                ]
            ];
        }
        return $data;
    }

    public function testDelete(): void
    {
        $id = 1;
        $response = $this->deleteJson('/api/v1/task/delete/' . $id);
        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Task deleted'
        ]);
    }
}
