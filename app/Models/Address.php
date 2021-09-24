<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Address
 * @package App\Models
 * @property int id_address
 * @property string post_code
 * @property string city
 * @property string street_name
 * @property int id_user
 * @property User user
 */
class Address extends Model
{
    use HasFactory;

    protected  $primaryKey = 'id_address';

    protected $fillable = [
        'post_code',
        'city',
        'street_name',
        'id_user'
    ];

    public function user() {
        return $this->belongsTo('App\Models\User', 'id_user', 'id');
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class, 'id_address', 'id_address', Address::class);
    }
}
