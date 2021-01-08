<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'name', 'contact'
    ];

    public static $createRules = [
        'name' => 'required|unique:providers|max:200',
        'contact' => 'required|max:200',
    ];

    public static $updateRules = [
        'name' => 'max:200',
        'contact' => 'max:200',
    ];

    public function goods(){
        return $this->hasMany(Goods::class);
    }
}
