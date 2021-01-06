<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class Storage extends Model
{
    use HasFactory;
    protected $fillable = [
        'warehouse_id', 'goods_id', 'quantity'
    ];

    public static $createRules = [
        'warehouse_id' => 'required',
        'goods_id' => 'required',
        'quantity' => 'required',
    ];

    public function validateOperation($quantity){
        $res = $this['quantity'] + $quantity;
        if($res >= 0){
            return;
        }

        $message = sprintf(
            'Incorrect value, operation with this value will result in negative quantity. (%d + (%d) = %d)',
            $this['quantity'],
            $quantity,
            $res
        );

        throw ValidationException::withMessages(
            ['quantity' => $message]
        );
    }


    public function updateStorageQuantity($quantity){
        $this->validateOperation($quantity);
        $this['quantity'] += $quantity;
        $this->save();
    }

    public function warehouse(){
        return $this->belongsTo(Warehouse::class);
    }

    public function goods(){
        return $this->belongsTo(Goods::class);
    }

    public function storageIns(){
        return $this->hasMany(StorageIn::class);
    }

    public function storageOuts(){
        return $this->hasMany(StorageOut::class);
    }
}
