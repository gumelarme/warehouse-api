<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Goods extends Model
{
    use HasFactory;
    public $fillable = ['name', 'provider_id'];
    public static $createRules = [
        'name' => 'required|min:2',
        'provider_id' => 'required'
    ];

    public function provider(){
        return $this->belongsTo(Provider::class);
    }

    public function storages(){
        return $this->hasMany(Storage::class);
    }
}
