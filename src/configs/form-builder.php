<?php
/**
 * Created by Mr.Tuan.
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
    'form'              => 'System::field.form',
    'text'              => 'System::field.text',
    'textarea'          => 'System::field.textarea',
    'button'            => 'System::field.button',
    'radio'             => 'System::field.radio',
    'checkbox'          => 'System::field.checkbox',
    'select'            => 'System::field.select',
    'choice'            => 'System::field.choice',
    'repeated'          => 'System::field.repeated',
    'child_form'        => 'System::field.child_form',
    'collection'        => 'System::field.collection',
    'static'            => 'System::field.static',
    'link'              => 'System::field.link',
    'two_selectbox'     => 'System::field.two_selectbox',
    'yes_no_checkbox'   => 'System::field.yes_no_checkbox',
    'group_buttons'     => 'System::field.group_buttons',

    // Remove the laravel-form-builder:: prefix above when using template_prefix
    'template_prefix'   => '',
    'default_namespace' => '',
    'custom_fields'     => [
        'link'                 => Core\Bases\FieldType\Link::class,
        'selectBox'            => Core\Bases\FieldType\SelectBox::class,
        'relationSelectBox'    => Core\Bases\FieldType\RelationSelectBox::class,
        'relationChoiceBox'    => Core\Bases\FieldType\RelationChoiceBox::class,
        'twoSelectBox'         => \Core\Bases\FieldType\TwoSelectBox::class,
        'relationTwoSelectBox' => \Core\Bases\FieldType\RelationTwoSelectBox::class,
        'yesNoCheckBox'        => \Core\Bases\FieldType\YesNoCheckBox::class,
        'groupButtons'         => \Core\Bases\FieldType\GroupButtons::class,
    ],
];