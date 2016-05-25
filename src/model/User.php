<?php

namespace tuanlq11\cms\model;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use tuanlq11\auditing\AuditingTrait;

/**
 * App\Models\User
 *
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property boolean $super_admin
 * @property string $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class User extends Model implements AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;
    use AuditingTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['first_name', 'last_name', 'email', 'password', 'is_active'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    protected static function boot()
    {
        parent::boot();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany('tuanlq11\cms\model\Role', 'role_user_relation', 'user_id', 'role_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groups()
    {
        return $this->belongsToMany('tuanlq11\cms\model\Group', 'user_group_relation', 'user_id', 'group_id');
    }

    /**
     * @return array
     */
    public function getRole()
    {
        $result = $this->roles()->get(['id'])->toArray();
        return array_pluck($result, 'id');
    }

    /**
     * @return array
     */
    public function getGroup()
    {
        $result = $this->groups()->get(['id'])->toArray();
        return array_pluck($result, 'id');
    }

    /** Publish the auditing actions */
    public function storeAuditing($new_value = [], $old_value = [], $action)
    {
        $this->auditing($new_value, $old_value, $action);
    }
}
