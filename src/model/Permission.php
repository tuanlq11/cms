<?php

namespace tuanlq11\cms\model;

use Illuminate\Database\Eloquent\Model;
use tuanlq11\auditing\AuditingTrait;

/**
 * App\Models\Permission
 *
 * @property integer $id
 * @property string $module
 * @property string $action
 * @property integer $rule_id
 */
class Permission extends Model
{
    use AuditingTrait;

    protected $table = 'permissions';

    protected $fillable = [
        'module',
        'action',
        'rule_id',
    ];

    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function role()
    {
        return $this->hasOne('tuanlq11\cms\model\Role', 'id', 'role_id');
    }
}
