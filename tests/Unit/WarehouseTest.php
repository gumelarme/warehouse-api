<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class WarehouseTest extends TestCase
{
    use WithoutMiddleWare;

    /**
     * Show warehouse by id
     * @dataProvider data_can_show
     * @test
     */
    public function can_show($id)
    {
        $resp = $this->get('/api/warehouses/'.$id);
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
    public function can_create($name, $address)
    {
        $wh = [
            'name' => $name,
            'address' => $address,
        ];

        $resp = $this->post('/api/warehouses', $wh);
        $resp->assertStatus(201);

        $this->assertDatabaseHas('warehouses', $wh);
        $resp->assertJson([
            'warehouse' => $wh,
            'message' => 'CREATED'
        ]);
    }

    public function data_can_create()
    {
        return [
            ['warehouse_1', 'Some place, in some city'],
            ['warehouse_2', 'someplace'],
        ];
    }

    /**
     * Update warehouse data
     * @dataProvider data_can_update
     * @test
     */
    public function can_update($id, $name, $address)
    {
        $wh = [
            'name' => $name,
            'address' => $address,
        ];

        $wh_before = $this->get('/api/warehouses/'.$id);
        $wh_before->assertStatus(200);

        $resp = $this->patch('/api/warehouses/'.$id, $wh);

        $wh['id'] = $id;
        $resp->assertStatus(200)
             ->assertJson([
                'warehouse' => $wh,
                'message' => 'UPDATED'
        ]);

        $this->assertDatabaseHas('warehouses', $wh);
        $this->assertDatabaseMissing('warehouses', $wh_before['warehouse']);

    }

    public function data_can_update()
    {
        return [
            [1, 'new_warehouse_name', 'Someplace'],
            [2, 'some_warehouse_name', 'Someplace'],
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
        $user_before = $this->get('/api/warehouses/'.$id);
        $user_before->assertStatus(200);

        $resp = $this->delete('/api/warehouses/'.$id);
        $resp->assertStatus(204);
        $this->assertDatabaseMissing('warehouses', $user_before['warehouse']);
    }

    public function data_can_delete()
    {
        return [
            [3],
        ];
    }

    /**
     * Delete user with associated data, expected to fail
     * @dataProvider data_can_delete_with_storage
     * @test
     */
    public function cannot_delet_with_storage($id)
    {
        $wh_before = $this->get('/api/warehouses/'.$id);
        $wh_before->assertStatus(200);

        $resp = $this->delete('/api/warehouses/'.$id);
        $resp->assertStatus(409);
    }

    public function data_can_delete_with_storage()
    {
        return [
            [1],
            [2],
        ];
    }
}
