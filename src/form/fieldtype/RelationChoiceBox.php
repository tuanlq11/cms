<?php
namespace tuanlq11\cms\form\fieldtype;

use Illuminate\Database\Eloquent\Model;
use Kris\LaravelFormBuilder\Fields\ChoiceType;
use Kris\LaravelFormBuilder\Form;

/**
 * Created by Mr.Tuan.
 * User: tuanlq
 * Date: 1/22/16
 * Time: 9:56 AM
 */
class RelationChoiceBox extends ChoiceType
{
    /**
     * RelationChoiceBox constructor.
     */
    public function __construct($name, $type, Form $parent, array $options = [])
    {
        /** @var Model $model */
        $model = $options['model'];
        $primary = $options['primary'];
        $show = $options['show'];

        $data = $model::query()->select($primary, $show)->get()->toArray();
        $options['choices'] = array_pluck($data, $show, $primary);
        $options['selected'] = (array)array_get($options, 'value', null);

        parent::__construct($name, $type, $parent, $options);
    }

    /**
     * @inheritdoc
     */
    protected function getTemplate()
    {
        return 'choice';
    }

    public function render(array $options = [], $showLabel = true, $showField = true, $showError = true)
    {
        return parent::render($options, $showLabel, $showField, $showError); // TODO: Change the autogenerated stub
    }


}