<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    protected $table = 'users';
    //protected $hidden = ['pivot'];
    public $timestamps = false;
    protected $fillable = [
        'name',
        'email',
        'password',
        'age',
        'profile_photo',
        'created_on',

    ];
    public function client_verify()
    {
       return $this->hasMany(ClientVerify::class);
    }
    public function image()
    {
        return $this->belongsToMany(image::class, 'image_user', 'user_id', 'image_id')
        ->withTimestamps();
    }

}
