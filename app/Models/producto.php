<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class producto extends Model
{
    use HasFactory;
    protected $fillable = [
        'claveCamarena',
        'codigo',
        'nombreCorto',
        'descripcion',
        'identificadorUrl',
        'nombreWeb',
        'videoUsoUrl',
        'fichaTecnicaUrl',
        'descripcionWeb',
        'categoria_id',
        'fechaBorrado',
        'presentacion'
    ];

    public function categorias(){
        return $this->belongsTo(categoria::class, 'categoria_id');
    }

    public function precios(){
        return $this->hasMany(precio::class, 'producto_id');
    }

    public function variantes(){
        return $this->hasMany(variante::class, 'producto_id');
    }
}
