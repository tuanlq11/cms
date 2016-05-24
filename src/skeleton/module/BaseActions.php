<?php

namespace Core\Bases\Module;

use Core\Bases\Module\Base\BatchAction;
use Core\Bases\Module\Base\Form;
use Core\Bases\Module\Base\Language;
use Core\Bases\Module\Base\ObjectAction;
use Core\Bases\Module\Base\Render;
use Core\Bases\Module\Base\Action;
use Core\Bases\Module\Base\Authenticate;
use Core\Bases\Module\Base\Base;
use Core\Bases\Module\Base\Configure;
use Core\Bases\Module\Base\Query;
use Core\Bases\Module\Base\Filter;
use Core\Bases\Module\Base\SubAction;
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
        $middleware = sprintf('\Core\Bases\Middleware\Gateway:%s', $this->getModuleName());
        $this->middleware($middleware);
        /** Load module configuration */
        $this->initConfiguration();

        if (in_array($action, ['index', 'filter'])) {
            /** Load Form Filter */
            $this->buildFormFilter();
        }
    }
}