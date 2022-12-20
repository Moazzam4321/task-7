<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;
    protected $table = 'image';
    public $timestamps = false;
    protected $fillable = [
        'name',
        'date',
        'time',
        'extension',
        'path',

    ];
    public function client()
    {
        return $this->belongsToMany(Client::class ,'image_user', 'user_id', ' image_id')
        ->withTimestamps();
    }
}
