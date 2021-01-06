<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
    use WithoutMiddleWare;

    /**
     * Show user by id
     * @dataProvider userData_show
     * @test
     */
    public function user_show($id, $name, $isManager)
    {
        // $this->setUp();
        $user = [
            'id' => $id,
            'username' => $name,
            'is_manager' => $isManager,
        ];

        $resp = $this->get('/api/users/'.$id);
        $resp->assertStatus(200)
             ->assertJson([
                'user' => $user,
        ]);
    }

    public function userData_show()
    {
        return [
            [1, 'octopus', true],
            [2, 'someguy', true],
            [3, 'humbleworker', false],
        ];
    }

    /**
     * Create user with complete and good data
     * @dataProvider userData_create_complete_ok
     * @test
     */
    public function user_create_complete_ok($name, $password, $isManager)
    {
        $user = [
            // 'id' => $id,
            'username' => $name,
            'password' => $password,
            'is_manager' => $isManager,
        ];

        $resp = $this->post('/api/users', $user);
        $resp->assertStatus(201);

        // delete password because its hidden
        unset($user['password']);

        $this->assertDatabaseHas('users', $user);
        $resp->assertJson([
            'user' => $user,
            'message' => 'CREATED'
        ]);
    }

    public function userData_create_complete_ok()
    {
        return [
            ['worker_1', 'eightcharpass', false],
            ['new_manager', 'eightcharpass', true],
            ['worker_3', 'eightcharpass', false],
        ];
    }

    /**
     * Update user 
     * @dataProvider data_can_update_username
     * @test
     */
    public function can_update_username($id, $name)
    {
        $this->setUp();
        $user = [
            'username' => $name,
        ];

        $user_before = $this->get('/api/users/'.$id);
        $user_before->assertStatus(200);

        $resp = $this->patch('/api/users/'.$id, $user);

        $user['id'] = $id;
        $resp->assertStatus(200)
             ->assertJson([
                'user' => $user,
                'message' => 'UPDATED'
        ]);

        $this->assertDatabaseHas('users', $user);
        $this->assertDatabaseMissing('users', $user_before['user']);

    }

    public function data_can_update_username()
    {
        return [
            [1, 'new_octopus'],
            [2, 'new_username'],
        ];
    }

    /**
     * Delete user without associated data
     * @dataProvider data_can_delete_user
     * @test
     */
    public function can_delete_user($id)
    {
        $this->setUp();
        $user_before = $this->get('/api/users/'.$id);
        $user_before->assertStatus(200);

        $resp = $this->delete('/api/users/'.$id);
        $resp->assertStatus(204);
        $this->assertDatabaseMissing('users', $user_before['user']);
    }

    public function data_can_delete_user()
    {
        return [
            [3],
            [2],
        ];
    }

    /**
     * Delete user with associated data, expected to fail
     * @dataProvider data_can_delete_user_with_storage_log
     * @test
     */
    public function cannot_delete_user_with_storage_log($id)
    {
        $user_before = $this->get('/api/users/'.$id);
        $user_before->assertStatus(200);

        $resp = $this->delete('/api/users/'.$id);
        $resp->assertStatus(409);
    }

    public function data_can_delete_user_with_storage_log()
    {
        return [
            [1],
        ];
    }

}
