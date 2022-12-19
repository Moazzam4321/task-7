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
        return $this->belongsToMany(Client::class)->using(image_user::class)
        ->withTimestamps();
    }
}
