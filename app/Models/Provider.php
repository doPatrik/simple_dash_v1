<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Provider
 * @package App\Models
 * @property int id_provider
 * @property string label
 * @property string name
 * @property string number
 * @property string owner_name
 * @property string color_code
 * @property int id_provider_address
 * @property int id_user
 * @property int id_address
 * @property User user;
 * @property ProviderAddress providerAddress
 * @property Collection|NewBill[] bills
 */
class Provider extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_provider';

    protected $fillable = [
        'name',
        'number',
        'owner_name',
        'label',
        'color_code',
        'id_provider_address',
        'id_user',
        'id_address'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id', Provider::class);
    }

    public function providerAddress()
    {
        return $this->hasOne(ProviderAddress::class, 'id_provider_address', 'id_provider_address');
    }

    public function userAddress()
    {
        return $this->hasOne(Address::class, 'id_address', 'id_address');
    }

    public function bills()
    {
        return $this->hasMany(NewBill::class, 'id_provider', 'id_provider');
    }
}
