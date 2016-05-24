<?php
namespace Core\Bases\Module\Base;

/**
 * Class Form. To generate Form data
 *
 * @traitUses Core\Bases\Module\BaseActions
 */
trait Language
{
    /**
     *
     */
    public function supportedLang()
    {
        $data = \Core\Bases\Module\Model\Language::all()->toArray();
        return array_pluck($data, 'name', 'locale');
    }
}