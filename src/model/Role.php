<?php

namespace tuanlq11\cms\model;

use Illuminate\Database\Eloquent\Model;
use tuanlq11\auditing\AuditingTrait;

/**
 * App\Models\Rule
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Role extends Model
{
    use AuditingTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'roles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description', 'is_active'];

}
