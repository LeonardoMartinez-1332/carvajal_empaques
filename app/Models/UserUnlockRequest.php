<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserUnlockRequest extends Model
{
    protected $table = 'user_unlock_requests';

    protected $fillable = [
        'usuario_id',
        'email',
        'motivo',
        'status',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}
