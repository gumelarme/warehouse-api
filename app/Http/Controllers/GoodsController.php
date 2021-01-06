<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Goods;

class GoodsController extends Controller
{
    public function index()
    {
        return [
            'goods' => Goods::with('provider')->paginate(20),
        ];
    }

    public function show($id)
    {
        return [
            'goods' => Goods::with('provider')->findOrFail($id),
        ];
    }

    public function store(Request $req)
    {
        $this->validate($req, Goods::$createRules);
        $goods = Goods::create($req->all());
        $goods->save();

        return response()->json([
            'goods' => $goods,
            'message' => 'CREATED'
        ], 201);
    }

    public function update(Request $req, $id)
    {
        $this->validate($req, Goods::$createRules);
        $goods = Goods::findOrFail($id);
        $goods->update($req->all());

        return response()->json([
            'goods' => $goods,
            'message' => 'UPDATED'
        ], 200);
    }

    public function destroy($id)
    {
        try {
            $goods = Goods::findOrFail($id);
            $goods->delete();
            return response()->json(['message' => 'DELETED',], 204);
        } catch (\Illuminate\Database\QueryException $ex) {
            return response()->json([
                'message' => 'There are data still associated with this goods, unable to delete.',
            ], 409);
        }
    }
}
