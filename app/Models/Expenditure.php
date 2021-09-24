<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Expenditure
 * @package App\Models
 * @property int id_type
 * @property int price
 * @property string description
 * @property int id_user
 * @property string purchase_date
 */
class Expenditure extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_expenditure';

    protected $fillable = [
        'id_type',
        'price',
        'description',
        'id_user',
        'purchase_date',
    ];

    public function type()
    {
        return $this->belongsTo(ExpenditureType::class, 'id_type', 'id_type');
    }
}
