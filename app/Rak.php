<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rak extends Model
{
    protected $table = 'rak';
    protected $guarded = []; 

    public function buku()
    {
        return $this->hasMany(Buku::class, 'rak_id', 'id');
    }
}
