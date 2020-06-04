<?php

namespace Messtechnik\Models;

use Messtechnik\Notifications\ResetPassword;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{
    use Notifiable, HasRoles;

    protected $guard_name = 'web';

    protected $table = 'users';

    protected $connection = 'mysql'; 

    public function empresa() {
        return $this->belongsTo('Messtechnik\Models\Cliente','clicodigo', 'CODIGO');
    }

    protected $fillable = [
        'name', 'email', 'password', 'clicodigo',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // public function registerMediaCollections()
    // {
    //     $this
    //         ->addMediaCollection('profile')
    //         ->singleFile();
    // }

    // public function registerMediaConversions(Media $media = null)
    // {
    //     $this->addMediaConversion('avatar')
    //         ->width(50)
    //         ->performOnCollections('profile');

    //     $this->addMediaConversion('preview')
    //         ->width(400)
    //         ->performOnCollections('profile');
    // }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }
}
