<?php

class Group extends Model
{
    use AuditingTrait;
    use I18NDBTrait;

    protected $i18n_fillable       = ['name', 'description'];
    protected $i18n_attribute_name = "i18n";
    protected $i18n_default_locale = "en";
    protected $i18n_primary        = "id";
    protected $i18n_class          = GroupI18N::class;
    protected $i18n_field          = "locale";

    protected function bootIfNotBooted()
    {
        $this->i18n_default_locale = \Session::get("language", "en");
        parent::bootIfNotBooted();
    }

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = "groups";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ["is_active"];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany('App\Models\Role', 'role_group_relation', 'group_id', 'role_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany('App\Models\User', 'user_group_relation', 'user_id', 'group_id');
    }

    public function groupTranslations()
    {
        return $this->hasMany('App\Models\GroupI18N', 'groups_i18n', 'user_id', 'group_id');
    }

    /**
     * @return array
     */
    public function getRole()
    {
        $result = $this->roles()->get(['id'])->toArray();
        return array_pluck($result, 'id');
    }

    /** Publish the auditing actions */
    public function storeAuditing($new_value = [], $old_value = [], $action)
    {
        $this->auditing($new_value, $old_value, $action);
    }
}