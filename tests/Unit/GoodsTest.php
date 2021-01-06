<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class GoodsTest extends TestCase
{
    use WithoutMiddleWare;

    /**
     * Show goods by id
     * @dataProvider data_can_show
     * @test
     */
    public function can_show($id)
    {
        $resp = $this->get('/api/goods/' . $id);
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
     * Create goods with complete and good data
     * @dataProvider data_can_create
     * @test
     */
    public function can_create($name, $provider_id)
    {
        $wh = [
            'name' => $name,
            'provider_id' => $provider_id,
        ];

        $resp = $this->post('/api/goods', $wh);
        $resp->assertStatus(201);

        $this->assertDatabaseHas('goods', $wh);
        $resp->assertJson([
            'goods' => $wh,
            'message' => 'CREATED'
        ]);
    }

    public function data_can_create()
    {
        return [
            ['goods_1', 3],
            ['goods_2', 4],
        ];
    }

    /**
     * Update goods data
     * @dataProvider data_can_update
     * @test
     */
    public function can_update($id, $name, $provider_id)
    {
        $goods = [
            'name' => $name,
            'provider_id' => $provider_id,
        ];

        $get_goods_before = $this->get('/api/goods/' . $id);
        $get_goods_before->assertStatus(200);

        $goods_before = $get_goods_before['goods'];
        unset($goods_before['provider']);


        $resp = $this->patch('/api/goods/' . $id, $goods);

        $goods['id'] = $id;
        $resp->assertStatus(200)
            ->assertJson([
                'goods' => $goods,
                'message' => 'UPDATED'
            ]);

        $this->assertDatabaseHas('goods', $goods);
        $this->assertDatabaseMissing('goods', $goods_before);
    }

    public function data_can_update()
    {
        return [
            [3, 'new_goods_name', 2],
            [4, 'some_goods_name', 2],
        ];
    }

    /**
     * Delete goods without associated data
     * @dataProvider data_can_delete
     * @test
     */
    public function can_delete($id)
    {
        $this->setUp();
        $get_goods_before = $this->get('/api/goods/' . $id);
        $get_goods_before->assertStatus(200);
        $get_goods_before = $get_goods_before['goods'];

        $resp = $this->delete('/api/goods/' . $id);
        $resp->assertStatus(204);
        unset($get_goods_before['provider']);
        $this->assertDatabaseMissing('goods', $get_goods_before);
    }

    public function data_can_delete()
    {
        return [
            [5],
        ];
    }

    /**
     * Delete goods with associated data, expected to fail
     * @dataProvider data_can_delete_with_storage
     * @test
     */
    public function cannot_delet_with_storage($id)
    {
        $wh_before = $this->get('/api/goods/' . $id);
        $wh_before->assertStatus(200);

        $resp = $this->delete('/api/goods/' . $id);
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
