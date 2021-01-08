<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Storage;
use App\Models\StorageIn;
use App\Models\StorageOut;

class StorageController extends Controller
{
    public function index()
    {
        return [
            'storages' => Storage::with('warehouse')->with('goods', 'goods.provider')->paginate(100),
        ];
    }

    public function show($id)
    {
        return [
            'storage' => Storage::with('warehouse')->with('goods', 'goods.provider')->find($id)
        ];
    }


    //TODO try catch?
    public function store(Request $request)
    {
        $this->validate($request, Storage::$createRules);
        $storage = Storage::create($request->all());
        return response()->json([
            'storage' => $storage,
            'message' => 'CREATED'
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, Storage::$createRules);
        $storage = Storage::findOrFail($id);
        $storage->update($request->all());

        return response()->json([
            'storage' => $storage,
            'message' => 'UPDATED'
        ], 200);
    }

    public function destroy($id)
    {
        try{
            $storage = Storage::findOrFail($id);
            $storage->delete();
            return response()->noContent();
        }catch(\Illuminate\Database\QueryException $ex){
            return response()->json([
                'message' => 'There are data still associated with this storage, unable to delete.',
            ], 409);
        }
    }

    public function showStorageLogs($id){
        $storage = Storage::with('warehouse')->with('goods', 'goods.provider')->findOrFail($id);
        $in = StorageIn::with('user')->where('storage_id', $id)->get();
        for ($i = 0; $i < count($in); $i++) {
            $in[$i]['storage'] = $storage;
        }

        $out = StorageOut::with('user')->where('storage_id', $id)->get();
        for ($i = 0; $i < count($out); $i++) {
            $out[$i]['storage'] = $storage;
        }
        return response()->json([
            'storage_ins' => $in,
            'storage_outs' => $out,
        ], 200);
    }
}
