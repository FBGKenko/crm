<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pedido extends Model
{
    use HasFactory;
    protected $fillable = [
        'folio',
        'estatus',
        'persona_id',
    ];

    public function persona(){
        return $this->belongsTo(persona::class, 'persona_id');
    }

    public function productos(){
        return $this->hasMany(pedidoProducto::class, 'pedido_id');
    }
}
