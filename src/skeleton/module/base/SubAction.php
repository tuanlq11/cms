<?php
namespace tuanlq11\cms\skeleton\module\base;

use Kris\LaravelFormBuilder\Form;

/**
 * Created by Mr.Tuan.
 * User: tuanlq
 * Date: 1/21/16
 * Time: 9:29 AM
 */
trait SubAction
{

    /**
     * @param $form Form
     * @param $config
     */
    protected function toListAction(&$form, $config)
    {
        $form->add('toList', 'link', [
            'url'     => $this->getPreviousUrl('index') ? $this->getPreviousUrl('index') : $this->getGeneratedUrl('index'),
            'label'   => array_get($config, 'label', 'back-to-list'),
            'attr'    => ['class' => array_get($config, 'class', 'btn btn-primary')],
            'wrapper' => false,
        ]);
    }

    /**
     * @param $form
     * @param $config
     */
    protected function saveAction(&$form, $config)
    {
        $form->add('save', 'submit', [
            'label' => array_get($config, 'label', 'Save'),
            'attr'  => [
                'name'  => "_save",
                'class' => array_get($config, 'class', 'btn btn-primary'),
            ],
        ]);
    }

    /**
     * @param $form
     * @param $config
     */
    protected function saveAndCreateAction(&$form, $config)
    {
        $form->add('saveAndCreate', 'submit', [
            'label' => array_get($config, 'label', 'Save and Create'),
            'attr'  => [
                'name'  => "_saveAndCreate",
                'class' => array_get($config, 'class', 'btn btn-warning'),
            ],
        ]);
    }

    /**
     * Save and redirect to list
     *
     * @param $form
     * @param $config
     */
    protected function saveAndRedirectAction(&$form, $config)
    {
        $form->add('saveAndRedirect', 'submit', [
            'label' => array_get($config, 'label', 'Save and Redirect'),
            'attr'  => [
                'name'  => "_saveAndRedirect",
                'class' => array_get($config, 'class', 'btn btn-warning'),
            ],
        ]);
    }

    /**
     * @param $form
     * @param $config
     */
    protected function deleteAction(&$form, $config)
    {
        $form->add('delete', 'submit', [
            'label' => array_get($config, 'label', 'Delete'),
            'attr'  => [
                'name'    => "_delete",
                'class'   => array_get($config, 'class', 'btn btn-primary'),
                'onclick' => 'return confirm("Are you sure?")',
            ],
        ]);
    }
}