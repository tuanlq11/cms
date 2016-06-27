<?php

namespace tuanlq11\cms\model;

use Illuminate\Database\Eloquent\Model;

class RoleI18N extends Model
{
    protected $table      = 'roles_i18ns';
    protected $fillable   = ['id', 'name', 'description', 'locale'];
    public    $timestamps = false;

}
