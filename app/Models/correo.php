<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class correo extends Model
{
    use HasFactory;
    protected $fillable = [
        'correo',
        'etiqueta',
        'principal',
        'persona_id',
    ];
    protected function persona(){
        return $this->belongsTo(persona::class);
    }
}
