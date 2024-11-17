<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class empresaDomicilio extends Model
{
    use HasFactory;
    protected $fillable = [
        'tipo',
        'empresa_id',
        'domicilio_id',
    ];


    public function empresa(){
        return $this->belongsTo(empresa::class);
    }

    public function domicilio(){
        return $this->belongsTo(domicilio::class);
    }
}
