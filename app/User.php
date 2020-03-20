<?php

namespace Messtechnik;

use Messtechnik\Notifications\ResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{
    use Notifiable;

    protected $guard_name = 'web';

    protected $table = 'USERS';

    public function empresa() {
        return $this->belongsTo('Messtechnik\Models\Company','cliente_codigo', 'CODIGO');
    }

    protected $fillable = [
        'name', 'email', 'password', 'cliente_codigo',
    ];

    protected $hidden = [
        'PASSWORD', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function registerMediaCollections()
    {
        $this
            ->addMediaCollection('profile')
            ->singleFile();
    }

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('avatar')
            ->width(50)
            ->performOnCollections('profile');

        $this->addMediaConversion('preview')
            ->width(400)
            ->performOnCollections('profile');
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }
}
