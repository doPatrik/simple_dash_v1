<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Class User
 * @package App\Models
 * @property int id
 * @property string name
 * @property string last_name
 * @property string first_name
 * @property string email
 * @property int id_address
 * @property string password
 * @property int id_role
 * @property boolean is_admin
 * @property boolean is_active
 * @property Collection|Address[] addresses
 * @property Collection|Provider[] providers
 * @property Collection|NewBill[] bills
 * @property Collection|ExpenditureType[] expenditureTypes
 * @property Collection|Expenditure[] expenditures
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'last_name',
        'first_name',
        'email',
        'id_address',
        'password',
        'id_role',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected static function booted()
    {
        if(auth()->check() && !auth()->user()->is_admin) {
            static::addGlobalScope('user', function (Builder $builder) {
                $builder->where('is_active', true);
            });
        }
    }

    public function getIsAdminAttribute()
    {
        return $this->id_role == 2;
    }

    public function getSelectedAddressAttribute()
    {
        if (session('address_id')) {
            return session('address_id');
        }

        return NULL;
    }

    public function addresses()
    {
        return $this->hasMany('App\Models\Address', 'id_user', 'id');
    }

    public function providers()
    {
        return $this->hasMany(Provider::class, 'id_user', 'id');
    }

    public function bills()
    {
        return $this->hasMany(NewBill::class, 'id_user', 'id');
    }

    public function expenditureTypes()
    {
        return $this->hasMany(ExpenditureType::class, 'id_user', 'id');
    }

    public function expenditures()
    {
        return $this->hasMany(Expenditure::class, 'id_user', 'id');
    }
}
