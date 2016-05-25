<?php
namespace tuanlq11\cms\skeleton\module\base;

use Form, Html;

/**
 * Created by Fallen
 * User: tuanlq
 * Date: 1/21/16
 * Time: 9:49 AM
 */
trait ObjectAction
{
    /**
     * Generate HTML for object action Show
     *
     * @param $object Model
     *
     * @return string
     */
    protected function showObjectAction($object, $config)
    {
        if(!$this->isActionTrusted('show')) return '';

        $actionURL =  \Html::link($this->getGeneratedUrl('show', [$object->id]), array_get($config, 'label'));
        return sprintf('<li>%s</li>', $actionURL);
    }

    /**
     * Generate HTML for object action Edit
     *
     * @param $object Model
     *
     * @return string
     */
    protected function editObjectAction($object, $config)
    {
        if(!$this->isActionTrusted('edit')) return '';

        $actionURL =  \Html::link($this->getGeneratedUrl('edit', [$object->id]), array_get($config, 'label'));
        return sprintf('<li>%s</li>', $actionURL);
    }

    /**
     * Generate HTML for object action Edit
     *
     * @param $object Model
     *
     * @return string
     */
    protected function DeleteObjectAction($object, $config)
    {
        if(!$this->isActionTrusted('destroy')) return '';

        return sprintf("<li>%s%s%s</li>",
            Form::open(['method' => 'post', 'url' => $this->getGeneratedUrl('destroy', [$object->id])]),
            Form::button(array_get($config, 'label'), [
                'type'    => 'submit',
                'onclick' => 'return confirm("Are you sure?")',
            ]),
            Form::close()
        );
    }
}