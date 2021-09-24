<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class NewBill
 * @package App\Models
 * @property int id_bill
 * @property string start_date
 * @property string end_date
 * @property int price
 * @property string dead_line
 * @property boolean is_paid
 * @property int id_provider
 * @property int id_user
 * @property Provider provider
 * @property User user
 */
class NewBill extends Model
{
    use HasFactory;

    protected $table = 'bills';
    protected $primaryKey = 'id_bill';

    protected $fillable = [
        'start_date',
        'end_date',
        'price',
        'dead_line',
        'is_paid',
        'id_provider',
        'id_user'
    ];

    protected static function booted()
    {
        if(auth()->check() && session('address_id')) {
            static::addGlobalScope('bill', function (Builder $builder) {
                $builder->whereHas('provider', function ($q) {
                    $q->where('id_address', session('address_id'));
                });
            });
        }
    }
    public function provider()
    {
        return $this->belongsTo(Provider::class, 'id_provider', 'id_provider', NewBill::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id', NewBill::class);
    }
}
