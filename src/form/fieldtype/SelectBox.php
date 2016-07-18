<?php
namespace tuanlq11\cms\form\fieldtype;

use Kris\LaravelFormBuilder\Fields\SelectType;
use Kris\LaravelFormBuilder\Form;
use Session, Config;

/**
 * Created by Mr.Tuan.
 * User: tuanlq
 * Date: 1/22/16
 * Time: 9:56 AM
 */
class SelectBox extends SelectType
{

    protected $valueProperty = 'selected';

    public function __construct($name, $type, Form $parent, array $options)
    {
        $options['selected'] = array_get($options, 'value', null);

        if (key_exists('choices', $options) && !empty($options['choices'])) {
            $locale = Session::get('cms.locale', Config::get('app.locale', 'en'));
            foreach ($options['choices'] as $key => $optionName) {
                $options['choices'][$key] = trans($optionName, [], "messages", $locale);
            }
        }

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