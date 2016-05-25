<?php
namespace App\Http\Modules\Authenticate;

use View;
use tuanlq11\cms\skeleton\module\BaseActions;

/**
 * Created by Fallen
 */
class AuthenticateActions extends BaseActions
{
    public function index()
    {
        return View::make("Authenticate::login");
    }


}