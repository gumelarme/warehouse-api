<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class User extends Model
{
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'password',
        'is_manager',
    ];

    /**
     * Set default value of attributes
     *
     * @var array
     */
    protected $attributes = [
        'is_manager' => false
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];


    public static $createRules = [
        'username' => 'required|unique:users|min:4',
        'password' => 'required|min:8',
    ];

    public static $updateRules = [
        'username' => 'min:4|unique:users,username,',
        'password' => 'min:8',
    ];


    public static function make($user){
        $user['password'] = Hash::make($user['password']);
        return User::create($user);
    }

    public function storageIns(){
        return $this->hasMany(StorageIn::class);
    }

    public function storageOuts(){
        return $this->hasMany(StorageOut::class);
    }
}
