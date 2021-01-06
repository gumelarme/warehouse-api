<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StorageIn;

class StorageInController extends Controller
{
    public function index()
    {
        return [
            'storage_ins' => StorageIn::with('user')
                ->with(
                    'storage',
                    'storage.warehouse',
                    'storage.goods',
                    'storage.goods.provider')
                ->paginate(100)
        ];
    }

    public function show($id)
    {
        return [
            'storage_in' => StorageIn::with('user')
                ->with(
                    'storage',
                    'storage.warehouse',
                    'storage.goods',
                    'storage.goods.provider')
                ->findOrFail($id)
        ];
    }

    //TODO try catch?
    public function store(Request $request)
    {
        $this->validate($request, StorageIn::$createRules);
        // $storageIn = StorageIn::create($request->all());
        $storageIn = StorageIn::newStorageIn($request->all());
        return response()->json([
            'storage_in' => $storageIn,
            'message' => 'CREATED'
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, StorageIn::$createRules);
        $storageIn = StorageIn::findOrFail($id);
        $storageIn->updateStorageIn($request->all());

        return response()->json([
            'storage_in' => $storageIn,
            'message' => 'UPDATED'
        ], 200);
    }

    public function destroy($id)
    {
        try{
            $storageIn = StorageIn::findOrFail($id);
            $storageIn->deleteStorageIn();
            return response()->noContent();
        }catch(\Illuminate\Database\QueryException $ex){
            return response()->json([
                'message' => 'There are data still associated with this storage in log, unable to delete.',
            ], 409);
        }
    }
}
