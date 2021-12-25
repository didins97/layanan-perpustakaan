<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    protected $table = 'buku';

    protected $guarded = [];
    

    public function transaksi(){

        return $this->belongsToMany(Transaksi::class);
    }

    public function rak(){

        return $this->belongsTo(Rak::class, 'rak_id', 'id');
    }
}
