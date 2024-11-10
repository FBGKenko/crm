<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class personaDomicilio extends Model
{
    use HasFactory;
    protected $fillable = [
        'tipo',
        'persona_id',
        'domicilio_id',
    ];

    public function persona(){
        return $this->belongsTo(persona::class);
    }

    public function domicilio(){
        return $this->belongsTo(domicilio::class);
    }
}
