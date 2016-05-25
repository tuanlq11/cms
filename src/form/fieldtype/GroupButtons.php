<?php

namespace tuanlq11\cms\form\fieldtype;

use Kris\LaravelFormBuilder\Fields\FormField;
use Kris\LaravelFormBuilder\Form;

/**
 * Created by PhpStorm.
 * User: arch
 * Date: 4/21/16
 * Time: 2:58 PM
 */
class GroupButtons extends FormField
{
    protected function getTemplate()
    {
        return 'group_buttons';
    }
}