<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'address'];
    public static $createRules = [
        'name' => 'required|unique:warehouses|max:200|min:4',
        'address' => 'required|max:500|min:5',
    ];

    public static $updateRules= [
        'name' => 'unique:warehouses|max:200|min:4',
        'address' => 'max:500|min:5',
    ];

    public function storage(){
        return $this->hasMany(Storage::class);
    }
}