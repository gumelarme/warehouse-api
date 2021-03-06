<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(){
        return [
            'users' => User::paginate(20),
        ];
    }

    public function show($id){
        return [
            'user' => User::findOrFail($id),
        ];
    }

    public function login(Request $request){
       $this->validate($request, [
           'username' => 'required',
           'password' => 'required|min:8',
       ]);

       $user = User::where('username', $request['username'])->firstOrFail();
       if(Hash::check($request['password'], $user->password)){
           return $user;
       }

       return response()->json(['error' => 'Unauthenticated.'], 401);
    }

    public function store(Request $req){
        $this->validate($req, User::$createRules);
        $user = User::make($req->all());
        $user->save();

        return response()->json([
            'user' => $user,
            'message' => 'CREATED'
        ], 201);
    }

    public function update(Request $req, $id){
        $user = User::findOrFail($id);

        $rules = User::$updateRules;
        $rules['username'] .= $user->id;

        $this->validate($req, $rules);
        $data = $req->all();

        if(isset($data['password'])){
            $data['password'] = Hash::make($data['password']);
        }
        
        $user->update($data);

        return response()->json([
            'user' => $user,
            'message' => 'UPDATED'
        ], 200);
    }

    public function destroy($id)
    {
        try{
            $user = User::findOrFail($id);
            $user->delete();
            return response()->noContent();

        }catch(\Illuminate\Database\QueryException $ex){
            return response()->json([
                'message' => 'There are data still associated with this user, unable to delete.',
            ], 409);
        }
    }

}
