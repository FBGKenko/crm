<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class telefono extends Model
{
    use HasFactory;
    protected $fillable = [
        'telefono',
        'etiqueta',
        'principal',
        'persona_id',
    ];

    protected function persona(){
        return $this->belongsTo(persona::class);
    }
}
