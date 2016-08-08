<?php
namespace tuanlq11\cms\form\fieldtype;

use Illuminate\Database\Eloquent\Model;
use Kris\LaravelFormBuilder\Fields\SelectType;
use Kris\LaravelFormBuilder\Form;
use Session;

/**
 * Created by Mr.Tuan.
 * User: tuanlq
 * Date: 1/22/16
 * Time: 9:56 AM
 */
class RelationSelectBox extends SelectType
{

    protected $valueProperty = 'selected';

    public function __construct($name, $type, Form $parent, array $options)
    {
        /** @var Model $model */
        $model              = $options['model'];
        $primary            = $options['primary'];
        $show               = $options['show'];
        $none_selected_item = isset($options['none_selected_item']) ? $options['none_selected_item'] : true;

        /** Detect I18N */
        $locale  = Session::get('cms.locale', \Config::get('app.locale', 'en'));
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
        $options['choices'] = [];
        if ($none_selected_item) {
            $options['choices'] = [null => ''];
        }
        $options['choices'] = $options['choices'] + array_pluck($data, $show, $primary);
        /** END */

        $options['selected'] = array_get($options, 'value', null);

        parent::__construct($name, $type, $parent, $options);
    }


    protected function getTemplate()
    {
        return 'select';
    }

    public function getDefaults()
    {
        return [
            'choices'     => [],
            'empty_value' => null,
            'selected'    => null,
        ];
    }

    public function render(array $options = [], $showLabel = true, $showField = true, $showError = true)
    {
        return parent::render($options, $showLabel, $showField, $showError);
    }

}