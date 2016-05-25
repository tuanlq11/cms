<?php
/**
 Created by Fallen
 * User: tuanlq
 * Date: 1/18/16
 * Time: 11:00 AM
 */

return [
    'defaults'          => [
        'wrapper_class'       => 'form-group',
        'wrapper_error_class' => 'has-error',
        'label_class'         => 'control-label',
        'field_class'         => 'form-control',
        'help_block_class'    => 'help-block',
        'error_class'         => 'text-danger',
        'required_class'      => 'required',
    ],
    // Templates
    'form'              => 'cms::field.form',
    'text'              => 'cms::field.text',
    'textarea'          => 'cms::field.textarea',
    'button'            => 'cms::field.button',
    'radio'             => 'cms::field.radio',
    'checkbox'          => 'cms::field.checkbox',
    'select'            => 'cms::field.select',
    'choice'            => 'cms::field.choice',
    'repeated'          => 'cms::field.repeated',
    'child_form'        => 'cms::field.child_form',
    'collection'        => 'cms::field.collection',
    'static'            => 'cms::field.static',
    'link'              => 'cms::field.link',
    'two_selectbox'     => 'cms::field.two_selectbox',
    'yes_no_checkbox'   => 'cms::field.yes_no_checkbox',
    'group_buttons'     => 'cms::field.group_buttons',

    // Remove the laravel-form-builder:: prefix above when using template_prefix
    'template_prefix'   => '',
    'default_namespace' => '',
    'custom_fields'     => [
        'link'                 => tuanlq11\cms\form\fieldtype\Link::class,
        'selectBox'            => tuanlq11\cms\form\fieldtype\SelectBox::class,
        'relationSelectBox'    => tuanlq11\cms\form\fieldtype\RelationSelectBox::class,
        'relationChoiceBox'    => tuanlq11\cms\form\fieldtype\RelationChoiceBox::class,
        'twoSelectBox'         => tuanlq11\cms\form\fieldtype\TwoSelectBox::class,
        'relationTwoSelectBox' => tuanlq11\cms\form\fieldtype\RelationTwoSelectBox::class,
        'yesNoCheckBox'        => tuanlq11\cms\form\fieldtype\YesNoCheckBox::class,
        'groupButtons'         => tuanlq11\cms\form\fieldtype\GroupButtons::class,
    ],
];