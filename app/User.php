<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements HasMedia
{
    use Notifiable, HasRoles, HasPermissions, HasMediaTrait;

    protected $guard_name = 'web';

    protected $table = 'users';

    public function empresa() {
        return $this->belongsTo('App\Models\Company','empresa_id', 'id');
    }

    protected $fillable = [
        'name', 'email', 'password', 'empresa_id',
    ];

    protected $hidden = [
        'password', 'remember_token',
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

}
