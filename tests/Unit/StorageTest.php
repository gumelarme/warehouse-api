<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StorageTest extends TestCase
{
     use WithoutMiddleWare;

    /**
     * Show storage by id
     * @dataProvider data_can_show
     * @test
     */
    public function can_show($id)
    {
        $resp = $this->get('/api/storages/'.$id);
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
    public function can_create($wh_id, $goods_id, $quantity)
    {
        $storage = [
            'warehouse_id' => $wh_id,
            'goods_id' => $goods_id,
            'quantity' => $quantity,
        ];

        $resp = $this->post('/api/storages', $storage);
        $resp->assertStatus(201);

        $this->assertDatabaseHas('storages', $storage);
        $resp->assertJson([
            'storage' => $storage,
            'message' => 'CREATED'
        ]);
    }

    public function data_can_create()
    {
        return [
            [1, 2, 300],
            [2, 2, 400],
        ];
    }

    /**
     * Update storage data
     * @dataProvider data_can_update
     * @test
     */
    public function can_update($id, $wh_id, $goods_id, $quantity)
    {
        $storage = [
            'warehouse_id' => $wh_id,
            'goods_id' => $goods_id,
            'quantity' => $quantity,
        ];

        $get_storage_before = $this->get('/api/storages/'.$id);
        $get_storage_before->assertStatus(200);

        $storage_before = $get_storage_before['storage'];

        unset($storage_before['warehouse']);
        unset($storage_before['goods']);

        $resp = $this->put('/api/storages/'.$id, $storage);

        $storage['id'] = $id;
        $resp->assertStatus(200)
             ->assertJson([
                'storage' => $storage,
                'message' => 'UPDATED'
        ]);

        $this->assertDatabaseHas('storages', $storage);
        $this->assertDatabaseMissing('storages', $storage_before);

    }

    public function data_can_update()
    {
        return [
            [3, 2, 3, 99],
            [4, 1, 4, 200],
        ];
    }

    /**
     * Delete user without associated data
     * @dataProvider data_can_delete
     * @test
     */
    public function can_delete($id)
    {
        $get_storage_before = $this->get('/api/storages/'.$id);
        $get_storage_before->assertStatus(200);

        $storage_before = $get_storage_before['storage'];

        unset($storage_before['warehouse']);
        unset($storage_before['goods']);


        $resp = $this->delete('/api/storages/'.$id);
        $resp->assertStatus(204);
        $this->assertDatabaseMissing('storages', $storage_before);
    }

    public function data_can_delete()
    {
        return [
            [3],
            [4],
        ];
    }

    /**
     * Delete user with associated data, expected to fail
     * @dataProvider data_cannot_delete_with_storage_log
     * @test
     */
    public function cannot_delete_with_storage_log($id)
    {
        $storage_before = $this->get('/api/storages/'.$id);
        $storage_before->assertStatus(200);


        $resp = $this->delete('/api/storages/'.$id);
        $resp->assertStatus(409);
    }

    public function data_cannot_delete_with_storage_log()
    {
        return [
            [1],
        ];
    }  
}
