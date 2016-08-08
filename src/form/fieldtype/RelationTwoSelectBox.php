<?php
namespace tuanlq11\cms\form\fieldtype;

use Illuminate\Support\Str;
use Kris\LaravelFormBuilder\Fields\ChoiceType;
use Kris\LaravelFormBuilder\Fields\SelectType;
use Kris\LaravelFormBuilder\Form;

/**
 * Created by Mr.Tuan.
 * User: tuanlq
 * Date: 3/16/16
 * Time: 7:46 AM
 */
class RelationTwoSelectBox extends SelectType
{
    /**
     * TwoSelectBox constructor.
     *
     * @param       $name
     * @param       $type
     * @param Form  $parent
     * @param array $options
     */
    public function __construct($name, $type, Form $parent, array $options)
    {
        $options['attr']['extend']   = true;
        $options['attr']['multiple'] = true;

        $options['btnSelect'] = array_merge([
            'class' => 'btn',
            'id'    => 'btnSelect',
        ], isset($options['btnSelect']) ? $options['btnSelect'] : []);

        $options['btnUnSelect'] = array_merge([
            'class' => 'btn',
            'id'    => 'btnUnSelect',
        ], isset($options['btnUnSelect']) ? $options['btnUnSelect'] : []);

        $options['container_id'] = Str::studly(Str::slug($name, '_')) . "TwoSelectBoxContainer";

        /** Load data from model */
        /** @var Model $model */
        $model   = $options['model'];
        $primary = $options['primary'];
        $show    = $options['show'];

        /** Detect I18N */
        $locale  = \Config::get('app.locale');
        $is_i18n = method_exists($model, 'saveI18N');
        $query   = $is_i18n ? $model::I18N($locale) : $model::query();
        /** END **/

        if (key_exists('filter', $options) && count($options['filter']) > 0) {
            foreach ($options['filter'] as $key => $item) {
                $query->where($key, $item['condition'], $item['value']);
            }
        }

        /** Parse to key-name format {$table}.{$field} */
        $keyName = strpos($options['primary'], '.') ? $options['primary'] : (new $model)->getTable() . "." . $options['primary'];

        $data = $query->select($keyName, $show)->get()->toArray();

        /** Fill model data to choice array */
        $options['choices'] = array_pluck($data, $show, $primary);
        /** END */

        $options['selected'] = array_get($options, 'value', []);

        /** END */

        $options['selected'] = (array)(isset($options['selected']) ? $options['selected'] : []);


        parent::__construct($name, $type, $parent, $options);
    }


    protected function getTemplate()
    {
        return "two_selectbox";
    }

}