<?php
namespace Core\Bases\Validators;

/**
 * Created by Mr.Tuan.
 * User: tuanlq
 * Date: 1/26/16
 * Time: 4:05 PM
 */
use DB;

class core
{
    /**
     * Validator Field == another field
     *
     * @param $attribute
     * @param $value
     * @param $parameters
     * @param $validator
     *
     * @return bool
     */
    public function equal_field($attribute, $value, $parameters, $validator)
    {
        return isset($value) && \Input::get($parameters[0]) == $value;
    }

    /**
     * @param $attribute
     * @param $value
     * @param $parameters
     * @param $validator
     *
     * @return bool
     */
    public function arr_exists($attribute, $value, $parameters, $validator)
    {
        list($table, $field) = $parameters;
        $value = (array)$value;
        $results = DB::table($table)->whereIn($field, $value)->get();

        return count($results) == count($value);
    }
}