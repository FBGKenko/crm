<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class categoria extends Model
{
    use HasFactory;
    protected $fillable = [
        'nombre',
    ];

    public function productos(){
        return $this->hasMany(producto::class, 'categoria_id');
    }
}
