<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StorageInTest extends TestCase
{
    use WithoutMiddleWare;

    /**
     * Show storage in by id
     * @dataProvider data_can_show
     * @test
     */
    public function can_show($id)
    {
        $resp = $this->get('/api/storage_ins/' . $id);
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
        $storageIn = [
            'storage_id' => $storage_id,
            'user_id' => $user_id,
            'description' => $description,
            'quantity' => $quantity,
        ];

        $resp = $this->post('/api/storage_ins', $storageIn);
        $resp->assertStatus(201);

        $this->assertDatabaseHas('storage_ins', $storageIn);
        $resp->assertJson([
            'storage_in' => $storageIn,
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
            [1, 2, 'Some text here', 300, 100 + 300],
            [2, 2, 'Something wrong', 400, 500 + 400],
            [2, 2, null, 400, 500 + 400],
        ];
    }

    /**
     * Update storage data
     * @dataProvider data_can_update
     * @test
     */
    public function can_update($id, $storage_id, $user_id, $description, $quantity, $qtyAfter)
    {
        $storageIn = [
            'storage_id' => $storage_id,
            'user_id' => $user_id,
            'description' => $description,
            'quantity' => $quantity,
        ];

        $get_storageIn_before = $this->get('/api/storage_ins/' . $id);
        $get_storageIn_before->assertStatus(200);

        $storageIn_before = $get_storageIn_before['storage_in'];
        $storage = $storageIn_before['storage'];
        $storage['quantity'] = $qtyAfter;


        unset($storage['warehouse']);
        unset($storage['goods']);

        $resp = $this->put('/api/storage_ins/' . $id, $storageIn);

        $storageIn['id'] = $id;
        $resp->assertStatus(200)
            ->assertJson([
                'storage_in' => $storageIn,
                'message' => 'UPDATED'
            ]);

        $this->assertDatabaseHas('storage_ins', $storageIn);
        $this->assertDatabaseHas('storages', $storage);
    }

    public function data_can_update()
    {
        return [
            [1, 1, 2, 'Change to something', 99, (99 - 100) + 100],
            [2, 2, 1, null, 200, (200 - 40) + 500],
        ];
    }

    /**
     * Update storage data
     * @dataProvider data_can_update_changed_storage_id
     * @test
     */
    public function can_update_changed_storage_id($id, $storage_id, $user_id, $quantity, $qtyAfter1, $qtyAfter2)
    {
        $storageIn = [
            'storage_id' => $storage_id,
            'user_id' => $user_id,
            'quantity' => $quantity,
        ];

        $get_storageIn_before = $this->get('/api/storage_ins/' . $id);
        $get_storageIn_before->assertStatus(200);

        $storageIn_before = $get_storageIn_before['storage_in']; // // //

        $storage1 = $storageIn_before['storage'];
        $storage1['quantity'] = $qtyAfter1;

        $get_otherStorage = $this->get('/api/storages/' . $storage_id);
        $get_otherStorage->assertStatus(200);

        $storage2 = $get_otherStorage['storage'];
        $storage2['quantity'] = $qtyAfter2;

        unset($storage1['warehouse']);
        unset($storage2['warehouse']);
        unset($storage1['goods']);
        unset($storage2['goods']);

        unset($storageIn['storage']);
        unset($storageIn['user']);

        $resp = $this->put('/api/storage_ins/' . $id, $storageIn);

        $storageIn['id'] = $id;
        $resp->assertStatus(200)
            ->assertJson([
                'storage_in' => $storageIn,
                'message' => 'UPDATED'
            ]);

        $this->assertDatabaseHas('storage_ins', $storageIn);
        $this->assertDatabaseHas('storages', $storage1);
        $this->assertDatabaseHas('storages', $storage2);
    }

    public function data_can_update_changed_storage_id()
    {
        return [
            [1, 3, 2, 100, 100 - 100, 133],
            [2, 4, 1, 200, 500 - 40, 198 + 200],
        ];
    }

    /**
     * Delete user without associated data
     * @dataProvider data_can_delete
     * @test
     */
    public function can_delete($id, $qtyAfter)
    {
        $get_StorageIn_before = $this->get('/api/storage_ins/' . $id);
        $get_StorageIn_before->assertStatus(200);

        $storageIn = $get_StorageIn_before['storage_in'];

        //for checking qty change inside db 
        $storage = $storageIn['storage'];
        $storage['quantity'] = $qtyAfter;

        //remove relationship data before checking db
        unset($storageIn['storage']);
        unset($storageIn['user']);

        unset($storage['warehouse']);
        unset($storage['goods']);
        unset($storage['created_at']);
        unset($storage['updated_at']);


        $resp = $this->delete('/api/storage_ins/' . $id);
        $resp->assertStatus(204);
        $this->assertDatabaseMissing('storage_ins', $storageIn);
        $this->assertDatabaseHas('storages', $storage);
    }

    public function data_can_delete()
    {
        return [
            [1, 100 - 100],
            [2, 500 - 40],
        ];
    }
}
