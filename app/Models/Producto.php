<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'productos'; 

    protected $fillable = [
        'num_caja',
        'codigo',
        'camas',
        'cajas_por_cama',
        'total_cajas'
    ];

    // Si necesitas definir relaciones en el futuro, aquí puedes agregarlas
}
