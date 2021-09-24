<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ProviderAddress
 * @package App\Models
 * @property int id_provider_Address
 * @property string postal_code
 * @property string city
 * @property string street_name
 * @property Provider provider
 */
class ProviderAddress extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_provider_address';

    protected $fillable = [
        'postal_code',
        'city',
        'street_name'
    ];

    public function provider()
    {
        return $this->belongsTo(Provider::class, 'id_provider_address', 'id_provider_address', ProviderAddress::class);
    }
}
