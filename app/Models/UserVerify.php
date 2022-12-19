<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserVerify extends Model
{
    use HasFactory;
    protected $table='verify_token';
    protected $fillable=[
        'user_id',
        'token',
    ];
    public function client()
    {
        return $this->belongsTo(Client::class, 'user_id');
    }
}
