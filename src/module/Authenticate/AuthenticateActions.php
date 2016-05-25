<?php
namespace App\Http\Modules\Authenticate;

use View;
use Core\Bases\Module\BaseActions;

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