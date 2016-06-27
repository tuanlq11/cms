<?php

namespace tuanlq11\cms\model;

use Illuminate\Database\Eloquent\Model;
use tuanlq11\auditing\AuditingTrait;
use tuanlq11\dbi18n\I18NDBTrait;
use Illuminate\Support\Facades\Session;

/**
 * App\Models\Rule
 *
 * @property integer        $id
 * @property string         $name
 * @property string         $description
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Role extends Model
{
    use AuditingTrait, I18NDBTrait;

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


    protected $i18n_fillable       = ['name', 'description'];
    protected $i18n_attribute_name = "i18n";
    protected $i18n_default_locale = "en";
    protected $i18n_primary        = "id";
    protected $i18n_class          = RoleI18N::class;
    protected $i18n_field          = "locale";

    protected function bootIfNotBooted()
    {
        $this->i18n_default_locale = Session::get("language", "en");
        parent::bootIfNotBooted();
    }

}
