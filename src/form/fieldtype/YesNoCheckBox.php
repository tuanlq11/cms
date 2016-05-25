<?php
namespace tuanlq11\cms\form\fieldtype;

use Illuminate\Support\Str;
use Kris\LaravelFormBuilder\Fields\CheckableType;
use Kris\LaravelFormBuilder\Form;

/**
 * Created by Tien Nguyen.
 * User: tienexe
 * Date: 3/17/16
 * Time: 11:46 AM
 */
class YesNoCheckBox extends CheckableType
{
    /**
     * YesNoCheckBox constructor.
     *
     * @param       $name
     * @param       $type
     * @param Form  $parent
     * @param array $options
     */
    public function __construct($name, $type, Form $parent, array $options)
    {
         $options['checked'] = (isset($options['checked']) ? $options['checked'] : 0);

         $options['container_id'] = Str::studly(Str::slug($name, '_')) . "YesNoCheckBoxContainer";

        parent::__construct($name, $type, $parent, $options);
    }


    protected function getTemplate()
    {
        return "yes_no_checkbox";
    }

}