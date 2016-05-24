<?php
namespace Core\Bases\FieldType;

use Illuminate\Support\Str;
use Kris\LaravelFormBuilder\Fields\SelectType;
use Kris\LaravelFormBuilder\Form;

/**
 * Created by Mr.Tuan.
 * User: tuanlq
 * Date: 3/16/16
 * Time: 7:46 AM
 */
class TwoSelectBox extends SelectType
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
        $options['attr']['extend'] = false;
        $options['attr']['multiple'] = false;
        $options['selected'] = (array)(isset($options['selected']) ? $options['selected'] : []);

        $options['btnSelect'] = array_merge([
            'class' => 'btn',
            'id'    => 'btnSelect',
        ], isset($options['btnSelect']) ? $options['btnSelect'] : []);

        $options['btnUnSelect'] = array_merge([
            'class' => 'btn',
            'id'    => 'btnUnSelect',
        ], isset($options['btnUnSelect']) ? $options['btnUnSelect'] : []);

        $options['container_id'] = Str::studly(Str::slug($name, '_')) . "TwoSelectBoxContainer";

        parent::__construct($name, $type, $parent, $options);
    }


    protected function getTemplate()
    {
        return "two_selectbox";
    }

}