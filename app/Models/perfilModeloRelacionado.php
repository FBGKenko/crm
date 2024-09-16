<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class perfilModeloRelacionado extends Model
{
    use HasFactory;
    protected $fillable = [
        'modelo',
        'idAsociado',
        'perfil_id',
    ];

    public function perfil(){
        $this->belongsTo(Perfil::class);
    }
}
