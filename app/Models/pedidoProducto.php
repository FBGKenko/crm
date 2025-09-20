<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pedidoProducto extends Model
{
    use HasFactory;
    protected $fillable = [
        'pedido_id',
        'producto_id',
        'variante_id',
        'precioUnitario',
        'cantidad',
        'estatus',
        'observacion',
    ];

    public function pedido(){
        return $this->belongsTo(pedido::class, 'pedido_id');
    }
    public function producto(){
        return $this->belongsTo(producto::class, 'producto_id');
    }
}
