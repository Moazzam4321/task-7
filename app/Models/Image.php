<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;
    protected $table = 'images';
    public $timestamps = false;
    protected $fillable = [
        'name',
        'date',
        'time',
        'extension',
        'path',
        'status',

    ];
    public function client()
    {
        return $this->belongsToMany(Client::class ,'image_user', 'image_id', 'user_id')
        ->withTimestamps();
    }
}
