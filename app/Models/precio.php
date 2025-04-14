<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class precio extends Model
{
    use HasFactory;

    public function variante(){
        return $this->belongsTo(variante::class, 'variante_id');
    }
}
