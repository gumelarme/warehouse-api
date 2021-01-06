<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Warehouse;

class WarehouseController extends Controller
{
    public function index()
    {
        return [
            'warehouses' => Warehouse::paginate(20),
        ];
    }

    public function show($id)
    {
        return [
            'warehouse' => Warehouse::findOrFail($id),
        ];
    }

    //TODO try catch?
    public function store(Request $request)
    {
        $this->validate($request, Warehouse::$createRules);
        $wh = Warehouse::create($request->all());
        return response()->json([
            'warehouse' => $wh,
            'message' => 'CREATED'
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, Warehouse::$updateRules);
        $wh = Warehouse::findOrFail($id);
        $wh->update($request->all());

        return response()->json([
            'warehouse' => $wh,
            'message' => 'UPDATED'
        ], 200);
    }

    public function destroy($id)
    {
        try{
            $wh = Warehouse::findOrFail($id);
            $wh->delete();
            return response()->json(['message' => 'DELETED',], 204);
        }catch(\Illuminate\Database\QueryException $ex){
            return response()->json([
                'message' => 'There are data still associated with this warehouse, unable to delete.',
            ], 409);
        }
    }

    public function showStorages($id){
        $wh = Warehouse::findOrFail($id);
        return response()->json([
            'goods' => $wh->storages,
        ], 200);
    }
}
