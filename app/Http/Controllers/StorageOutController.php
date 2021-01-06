<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StorageOut;

class StorageOutController extends Controller
{
    public function index()
    {
        return [
            'storage_outs' => StorageOut::with('user')
                ->with(
                    'storage',
                    'storage.warehouse',
                    'storage.goods',
                    'storage.goods.provider'
                )
                ->paginate(100)
        ];
    }

    public function show($id)
    {
        return [
            'storage_out' => StorageOut::with('user')
                ->with(
                    'storage',
                    'storage.warehouse',
                    'storage.goods',
                    'storage.goods.provider'
                )
                ->findOrFail($id)
        ];
    }

    //TODO try catch?
    public function store(Request $request)
    {
        $this->validate($request, StorageOut::$createRules);
        // $storageIn = StorageOut::create($request->all());
        $storageOut = StorageOut::newStorageOut($request->all());
        return response()->json([
            'storage_out' => $storageOut,
            'message' => 'CREATED'
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, StorageOut::$createRules);
        $storageOut = StorageOut::findOrFail($id);
        $storageOut->updateStorageOut($request->all());

        return response()->json([
            'storage_out' => $storageOut,
            'message' => 'UPDATED'
        ], 200);
    }

    public function destroy($id)
    {
        try {
            $storageOut = StorageOut::findOrFail($id);
            $storageOut->deleteStorageOut();
            return response()->noContent();
        } catch (\Illuminate\Database\QueryException $ex) {
            return response()->json([
                'message' => 'There are data still associated with this storage in log, unable to delete.',
            ], 409);
        }
    }
}
