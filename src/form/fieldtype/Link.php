<?php
namespace tuanlq11\cms\form\fieldtype;

use \Kris\LaravelFormBuilder\Fields\FormField;

/**
 * Created by Mr.Tuan.
 * User: tuanlq
 * Date: 1/21/16
 * Time: 8:52 AM
 */
class Link extends FormField
{
    /**
     *
     */
    protected function getTemplate()
    {
        return 'cms::field.link';
    }

    /**
     * @param $value
     *
     * @return mixed
     */
    protected function isValidValue($value)
    {
        return true;
    }

    /**
     * @param array $options
     * @param bool  $showLabel
     * @param bool  $showField
     * @param bool  $showError
     *
     * @return string
     */
    public function render(array $options = [], $showLabel = true, $showField = true, $showError = true)
    {
        $options = array_merge($this->getOptions(), $options);

        $options['url'] = array_get($options, 'url', '#');
        $options['label'] = array_get($options, 'label', 'Link');
        $options['attr'] = array_get($options, 'attr', ['class' => '']);

        return parent::render($options, $showLabel, $showField, $showError);
    }
}