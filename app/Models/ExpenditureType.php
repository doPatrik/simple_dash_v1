<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ExpenditureType
 * @package App\Models
 * @property string name
 * @property string color_code
 */
class ExpenditureType extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_type';

    protected $fillable = [
        'name',
        'color_code',
        'id_user',
    ];

    public function expenditures()
    {
        return  $this->hasMany(Expenditure::class, 'id_type', 'id_type');
    }


}
