<?php
namespace tuanlq11\cms\skeleton\module\base;

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
        $data = \tuanlq11\cms\model\Language::all()->toArray();
        return array_pluck($data, 'name', 'locale');
    }
}