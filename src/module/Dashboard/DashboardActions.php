<?php
namespace App\Http\Modules\Dashboard;

use View;
use Config;
use Core\Bases\Module\BaseActions;

/**
 * Created by Tien Nguyen.
 * User: tienexe
 * Date: 2/3/16
 * Time: 11:00 AM
 */
class DashboardActions extends BaseActions
{
    public function index()
    {
        $menu = Config::get('core.menu', []);
        $javascripts = $this->_javascripts;
        $stylesheets = $this->_stylesheets;
        return View::make("Dashboard::index", get_defined_vars())->with('menu', $menu)->with('controller', $this);
    }
}