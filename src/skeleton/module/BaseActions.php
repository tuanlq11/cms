<?php

namespace tuanlq11\cms\skeleton\module;

use tuanlq11\cms\skeleton\module\base\BatchAction;
use tuanlq11\cms\skeleton\module\base\Form;
use tuanlq11\cms\skeleton\module\base\Language;
use tuanlq11\cms\skeleton\module\base\ObjectAction;
use tuanlq11\cms\skeleton\module\base\Render;
use tuanlq11\cms\skeleton\module\base\Action;
use tuanlq11\cms\skeleton\module\base\Authenticate;
use tuanlq11\cms\skeleton\module\base\Base;
use tuanlq11\cms\skeleton\module\base\Configure;
use tuanlq11\cms\skeleton\module\base\Query;
use tuanlq11\cms\skeleton\module\base\Filter;
use tuanlq11\cms\skeleton\module\base\SubAction;
use Route, Config, View, App, Session, Request;

class BaseActions extends Base
{
    /** Core Addition */
    use Configure, Action, SubAction, ObjectAction, BatchAction;
    use Authenticate, Query, Filter, Form, Render;
    use Language;

    /** End */


    /**
     * Controller constructor.
     */
    public function __construct()
    {
        if (App::runningInConsole()) return;

        $action = 'index';
        /** Detect current route action */
        $route = Route::getCurrentRoute();
        if (!is_null($route)) {
            $action = Route::getCurrentRoute()->getAction()['uses'];
            $action = explode('@', $action)[1];
        }
        /** End */

        $this->init($action);
        $this->initView($action);
        /** Push config to laravel config. With key is module_name */
        Config::push(strtolower($this->getModuleName()), $this->config);
    }

    /**
     * Init View
     */
    protected function initView($action)
    {
        View::addNamespace($this->getModuleName(), $this->getModuleViewPath());

        /** Load Config View for Head */
        $this->buildViewHead($action);
    }

    /**
     * Init Module
     */
    protected function init($action)
    {
        $this->action = $this->getCurrentAction();
        /** Store previous url */
        $this->logPreviousUrl();
        /** End */

        /** Core Middleware */
        $middleware = sprintf('\tuanlq11\cms\middleware\Gateway:%s', $this->getModuleName());
        $this->middleware($middleware);

        /** Load module configuration */
        $this->initConfiguration();

        if (in_array($action, ['index', 'filter'])) {
            /** Load Form Filter */
            $this->buildFormFilter();
        }
    }
}