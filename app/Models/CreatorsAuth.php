<?php

namespace App\Models;

use App\Notifications\CustomResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class CreatorsAuth extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    public function sendPasswordResetNotification($token)
    {
        $userType = 'creator_users';
        $this->notify(new CustomResetPasswordNotification($token, $userType));
    }

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'creator_users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'phone_number',
        'email',
        'country',
        'location',
        'password',
        'status',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
