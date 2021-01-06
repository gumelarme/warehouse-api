<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StorageOutTest extends TestCase
{
    use WithoutMiddleWare;

    /**
     * Show storage in by id
     * @dataProvider data_can_show
     * @test
     */
    public function can_show($id)
    {
        $resp = $this->get('/api/storage_outs/' . $id);
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
    public function can_create($storage_id, $user_id, $description, $quantity, $updatedQuantity)
    {
        $storageOut = [
            'storage_id' => $storage_id,
            'user_id' => $user_id,
            'description' => $description,
            'quantity' => $quantity,
        ];

        $resp = $this->post('/api/storage_outs', $storageOut);
        $resp->assertStatus(201);

        $this->assertDatabaseHas('storage_outs', $storageOut);
        $resp->assertJson([
            'storage_out' => $storageOut,
            'message' => 'CREATED'
        ]);

        $storage = $this->get('/api/storages/' . $storage_id);
        $storage->assertStatus(200)
            ->assertJson([
                'storage' => [
                    'quantity' => $updatedQuantity
                ]
            ]);
    }

    public function data_can_create()
    {
        return [
            [1, 2, 'Some text here', 10, 100 - 10],
            [2, 2, 'Something wrong', 340, 500 - 340],
            [2, 2, null, 400, 500 - 400],
        ];
    }

    /**
     * Update storage data
     * @dataProvider data_can_update
     * @test
     */
    public function can_update($id, $storage_id, $user_id, $description, $quantity, $qtyAfter)
    {
        $storageOut = [
            'storage_id' => $storage_id,
            'user_id' => $user_id,
            'description' => $description,
            'quantity' => $quantity,
        ];

        $get_storageOut_before = $this->get('/api/storage_outs/' . $id);
        $get_storageOut_before->assertStatus(200);

        $storageOut_before = $get_storageOut_before['storage_out'];
        $storage = $storageOut_before['storage'];
        $storage['quantity'] = $qtyAfter;

        unset($storageOut_before['storage']);
        unset($storageOut_before['user']);
        unset($storage['warehouse']);
        unset($storage['goods']);

        $resp = $this->put('/api/storage_outs/' . $id, $storageOut);

        $storageOut['id'] = $id;
        $resp->assertStatus(200)
            ->assertJson([
                'storage_out' => $storageOut,
                'message' => 'UPDATED'
            ]);

        $this->assertDatabaseHas('storage_outs', $storageOut);
        $this->assertDatabaseHas('storages', $storage);
    }

    public function data_can_update()
    {
        return [
            [1, 1, 2, 'Change to something', 5, (100 + 10) - 5],
            [2, 2, 1, null, 100, (500 + 30) - 100],
        ];
    }

    /**
     * Update storage data
     * @dataProvider data_can_update_changed_storage_id
     * @test
     */
    public function can_update_changed_storage_id($id, $storage_id, $user_id, $quantity, $qtyAfter1, $qtyAfter2)
    {
        $storageOut = [
            'storage_id' => $storage_id,
            'user_id' => $user_id,
            'quantity' => $quantity,
        ];

        $get_storageOut_before = $this->get('/api/storage_outs/' . $id);
        $get_storageOut_before->assertStatus(200);

        $storageOut_before = $get_storageOut_before['storage_out'];

        $storage1 = $storageOut_before['storage'];
        $storage1['quantity'] = $qtyAfter1;

        $get_otherStorage = $this->get('/api/storages/' . $storage_id);
        $get_otherStorage->assertStatus(200);

        $storage2 = $get_otherStorage['storage'];
        $storage2['quantity'] = $qtyAfter2;

        unset($storage1['warehouse']);
        unset($storage2['warehouse']);
        unset($storage1['goods']);
        unset($storage2['goods']);
        unset($storage1['created_at']);
        unset($storage2['created_at']);
        unset($storage1['updated_at']);
        unset($storage2['updated_at']);

        unset($storageOut_before['storage']);
        unset($storageOut_before['user']);

        $resp = $this->put('/api/storage_outs/' . $id, $storageOut);

        $storageOut['id'] = $id;
        $resp->assertStatus(200)
            ->assertJson([
                'storage_out' => $storageOut,
                'message' => 'UPDATED'
            ]);

        $this->assertDatabaseHas('storage_outs', $storageOut);
        $this->assertDatabaseHas('storages', $storage1);
        $this->assertDatabaseHas('storages', $storage2);
    }

    public function data_can_update_changed_storage_id()
    {
        return [
            [1, 3, 2, 10, 100 + 10, 33 - 10],
            [2, 4, 1, 30, 500 + 30, 198 - 30],
        ];
    }

    /**
     * Delete user without associated data
     * @dataProvider data_can_delete
     * @test
     */
    public function can_delete($id, $qtyAfter)
    {
        $get_StorageOut_before = $this->get('/api/storage_outs/' . $id);
        $get_StorageOut_before->assertStatus(200);

        $storageOut_before = $get_StorageOut_before['storage_out'];

        //for checking qty change inside db 
        $storage = $storageOut_before['storage'];
        $storage['quantity'] = $qtyAfter;

        //remove relationship data before checking db
        unset($storageOut_before['storage']);
        unset($storageOut_before['user']);
        unset($storage['warehouse']);
        unset($storage['goods']);


        $resp = $this->delete('/api/storage_outs/' . $id);
        $resp->assertStatus(204);
        $this->assertDatabaseMissing('storage_outs', $storageOut_before);
        $this->assertDatabaseHas('storages', $storage);
    }

    public function data_can_delete()
    {
        return [
            [1, 100 + 10],
            [2, 500 + 30],
        ];
    }
}
