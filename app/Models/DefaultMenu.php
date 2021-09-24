<?php

namespace App\Models;

use Cassandra\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DefaultMenu
 * @package App\Models
 * @property string name
 * @property string icon
 * @property string route
 * @property string type
 * @property boolean is_active
 * @property boolean is_default_menu
 * @property boolean is_admin_menu
 */
class DefaultMenu extends Model
{
    use HasFactory;

    protected $table = 'default_menu';
    protected  $primaryKey = 'id_default_menu';

    protected $fillable = [
        'name',
        'icon',
        'route',
        'type',
        'is_active',
    ];

    protected static function booted()
    {
        if(auth()->check() && !auth()->user()->is_admin) {
            static::addGlobalScope('menu', function (Builder $builder) {
                $builder->where('is_active', true);
            });
        }
    }

    public function getIsDefaultMenuAttribute()
    {
        return $this->type == 'default';
    }

    public function getIsAdminMenuAttribute()
    {
        return $this->type == 'admin';
    }
}
