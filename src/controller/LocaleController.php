<?php
namespace tuanlq11\cms\controller;

use Config, Input, Validator, Session, App, Response, URL, Redirect;
use tuanlq11\cms\model\Language;

/**
 * Created by Fallen
 */
class LocaleController extends \App\Http\Controllers\Controller
{
    public function switchLang()
    {
        $supported_lang = Language::all()->toArray();
        $supported_lang = array_pluck($supported_lang, 'name', 'locale');

        $rules = [
            'locale' => 'required|string|in:' . implode(',', array_keys($supported_lang)),
        ];

        $params = array_only(Input::all(), array_keys($rules));

        $valid = Validator::make($params, $rules);

        if ($valid->fails()) {
            abort(404, "Language is not supported");
        }

        Session::put('cms.locale', $params['locale']);
        App::setLocale($params['locale']);

        return Redirect::back();
    }
}