<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class producto extends Model
{
    use HasFactory;
    protected $fillable = [
        'claveCamarena',
        'nombreCorto',
        'descripcion',
        'identificadorUrl',
        'nombreWeb',
        'videoUsoUrl',
        'fichaTecnicaUrl',
        'descripcionWeb',
        'categoria_id',
        'fechaBorrado',
    ];

    public function categorias(){
        return $this->belongsTo(categoria::class, 'categoria_id');
    }
}
