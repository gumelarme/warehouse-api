<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Provider;

class ProviderController extends Controller
{
    public function index(){
        return [
            'providers' => Provider::paginate(20),
        ];
    }

    public function show($id){
        return [
            'provider' => Provider::findOrFail($id),
        ];
    }

    public function store(Request $req){
        $this->validate($req, Provider::$createRules);
        $provider = Provider::create($req->all());
        $provider->save();

        return response()->json([
            'provider' => $provider,
            'message' => 'CREATED'
        ], 201);
    }

    public function update(Request $req, $id){
        $this->validate($req, Provider::$updateRules);
        $provider = Provider::findOrFail($id);
        $provider->update($req->all());

        return response()->json([
            'provider' => $provider,
            'message' => 'UPDATED'
        ], 200);
    }

    public function destroy($id)
    {
        try{
            $provider = Provider::findOrFail($id);
            $provider->delete();
            return response()->json(['message' => 'DELETED',], 204);
        }catch(\Illuminate\Database\QueryException $ex){
            return response()->json([
                'message' => 'There are data still associated with this provider, unable to delete.',
            ], 409);
        }
    }


    public function showGoods($id){
        $provider = Provider::findOrFail($id);

        return response()->json([
            'goods' => $provider->goods,
        ], 200);
    }
}
