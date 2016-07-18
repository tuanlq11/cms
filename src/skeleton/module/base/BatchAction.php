<?php
namespace tuanlq11\cms\skeleton\module\base;

use Request, Auth;
use Illuminate\Support\Facades\Input;

/**
 * Created by Mr.Tuan.
 * User: tuanlq
 * Date: 1/19/16
 * Time: 9:39 AM
 */
trait BatchAction
{
    /**
     * Suffix of batchAction name
     *
     * @var string
     */
    protected $batchActionSuffix = 'BatchAction';

    private function checkBatchActionCredential($batchAction)
    {
        /** Because batchAction only avalid in index */
        $action      = 'index';
        $is_secure   = $this->getConfig('is_secure', $action)[0];
        $credentials = $this->getConfig("{$action}.batch_action.{$batchAction}.credential");
        $logged      = Auth::check();

        if ($is_secure) {
            if (!$logged) {
                return redirect('/login');
            }

            /** @var User $user */
            $user       = Auth::user();
            $rules      = array_pluck($user->roles()->get(['name'])->toArray(), 'name');
            $matchRules = array_intersect($credentials, $rules);

            if (empty($matchRules) && !empty($credentials)) {
                return redirect($this->getGeneratedUrl('index'));
            }
        }
    }

    /**
     * Batch action in index page
     *
     * @param $request Request
     *
     * @return mixed
     */
    public function batchAction(Request $request)
    {
        $action = Input::get('_batchAction', null);
        if (!$action) {
            return redirect($this->getGeneratedUrl('index'));
        }
        $this->checkBatchActionCredential($action);

        $action = $action . "BatchAction";
        if (!method_exists($this, $action)) {
            if (env('APP_DEBUG')) {
                abort(404, "Function {$action} not exists");
            }

            return redirect($this->getGeneratedUrl('index'));
        }

        return $this->$action($request);
    }

    /**
     * Init sample function. Delete multi object same time
     *
     * @param Request $request
     *
     * @return mixed
     */
    protected function deleteBatchAction(Request $request)
    {
        if (!$selected = Request::get('selected', null)) return redirect($this->getGeneratedUrl('index'));
        $modelName = $this->getModelName();

        $modelName::destroy($selected);

        return redirect($this->getGeneratedUrl('index'));
    }
}