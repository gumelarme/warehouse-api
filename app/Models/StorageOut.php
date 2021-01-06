<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StorageOut extends Model
{
    use HasFactory;

    protected $fillable = ['storage_id', 'user_id', 'description', 'quantity'];
    public static $createRules = [
        'storage_id' => 'required',
        'user_id' => 'required',
        'quantity' => 'required|gt:0',
    ];

    public static function newStorageOut($data)
    {
        $storage = Storage::findOrFail($data['storage_id']);
        $stOut = StorageOut::create($data);
        $storage->updateStorageQuantity(-$data['quantity']);
        return $stOut;
    }

    public function updateStorageOut($newData)
    {
        $storage = Storage::findOrFail($this['storage_id']);
        if ($newData['storage_id'] == $this['storage_id']) {
            // if new qty < old qty, then add as much
            // new - old; 400 - 600 = -(-200)
            // if new qty > old qty, then reduce as much
            // new - old; 600 - 400 = -(200)
            $amount = $newData['quantity'] - $this['quantity'];
            $storage->updateStorageQuantity(-$amount);
        } else {
            //reset to the original qty
            $storage->updateStorageQuantity($this['quantity']);

            //add new qty
            $otherStorage = Storage::findOrFail($newData['storage_id']);
            $otherStorage->updateStorageQuantity(-$newData['quantity']);
        }


        $this->update($newData);
    }

    public function deleteStorageOut()
    {
        $storage = Storage::findOrFail($this['storage_id']);
        $storage->updateStorageQuantity($this['quantity']);
        $this->delete();
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function storage(){
        return $this->belongsTo(Storage::class);
    }
}
