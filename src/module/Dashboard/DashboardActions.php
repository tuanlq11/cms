<?php
namespace App\Http\Modules\Dashboard;

use View, Config;
use tuanlq11\cms\skeleton\module\BaseActions;

class DashboardActions extends BaseActions
{
    public function index()
    {
        $menu        = Config::get('cms.menu', []);
        $javascripts = $this->_javascripts;
        $stylesheets = $this->_stylesheets;
        $layout      = 'cms::layout';

        return View::make("Dashboard::index", get_defined_vars())->with('menu', $menu)->with('controller', $this);
    }
}