<?php

namespace tuanlq11\cms\model;

use Illuminate\Database\Eloquent\Model;

class GroupI18N extends Model
{
    protected $table      = 'groups_i18ns';
    protected $fillable   = ['id', 'name', 'description', 'locale'];
    public    $timestamps = false;

}
