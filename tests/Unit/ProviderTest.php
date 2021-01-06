<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProviderTest extends TestCase
{
    use WithoutMiddleWare;


    /**
     * Show provider by id
     * @dataProvider data_can_show
     * @test
     */
    public function can_show($id)
    {
        $resp = $this->get('/api/providers/'.$id);
        $resp->assertStatus(200);
    }

    public function data_can_show()
    {
        return [
            [1],
            [2],
        ];
    }

    /**
     * Create user with complete and good data
     * @dataProvider data_can_create
     * @test
     */
    public function can_create($name, $contact)
    {
        $provider = [
            'name' => $name,
            'contact' => $contact,
        ];

        $resp = $this->post('/api/providers', $provider);
        $resp->assertStatus(201);

        $this->assertDatabaseHas('providers', $provider);
        $resp->assertJson([
            'provider' => $provider,
            'message' => 'CREATED'
        ]);
    }

    public function data_can_create()
    {
        return [
            ['provider_1', '128821201239'],
            ['provider_2', '192823123919'],
        ];
    }

    /**
     * Update provider data
     * @dataProvider data_can_update
     * @test
     */
    public function can_update($id, $name, $contact)
    {
        $provider = [
            'name' => $name,
            'contact' => $contact,
        ];

        $wh_before = $this->get('/api/providers/'.$id);
        $wh_before->assertStatus(200);

        $resp = $this->patch('/api/providers/'.$id, $provider);

        $provider['id'] = $id;
        $resp->assertStatus(200)
             ->assertJson([
                'provider' => $provider,
                'message' => 'UPDATED'
        ]);

        $this->assertDatabaseHas('providers', $provider);
        $this->assertDatabaseMissing('providers', $wh_before['provider']);

    }

    public function data_can_update()
    {
        return [
            [1, 'new_provider_name', '19213912311'],
            [2, 'some_provider_name', '12391239123'],
        ];
    }

    /**
     * Delete user without associated data
     * @dataProvider data_can_delete
     * @test
     */
    public function can_delete($id)
    {
        $this->setUp();
        $user_before = $this->get('/api/providers/'.$id);
        $user_before->assertStatus(200);

        $resp = $this->delete('/api/providers/'.$id);
        $resp->assertStatus(204);
        $this->assertDatabaseMissing('providers', $user_before['provider']);
    }

    public function data_can_delete()
    {
        return [
            [4],
        ];
    }

    /**
     * Delete user with associated data, expected to fail
     * @dataProvider data_cannot_delete_with_goods
     * @test
     */
    public function cannot_delete_with_goods($id)
    {
        $wh_before = $this->get('/api/providers/'.$id);
        $wh_before->assertStatus(200);

        $resp = $this->delete('/api/providers/'.$id);
        $resp->assertStatus(409);
    }

    public function data_cannot_delete_with_goods()
    {
        return [
            [1],
            [2],
        ];
    }
}
