<?php

namespace tuanlq11\cms\middleware;

use Illuminate\Support\Facades\App;
use tuanlq11\cms\model\User;
use Request, Closure, Route, Config, Auth, Session;

class Gateway
{
    /**
     * Module configuration
     */
    protected $config;

    /**
     * @param Request $request
     * @param Closure $next
     * @param string  $module
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $module)
    {
        $action = explode('@', Route::getCurrentRoute()->getActionName())[1];
        $this->config = Config::get(strtolower($module), [[]])[0];
        $credentials = $this->getConfig('credentials', $action);
        $is_secure = $this->getConfig('is_secure', $action)[0];

        $logged = Auth::check();
        $rules = [];

        if ($is_secure) {
            if (!$logged) {
                return redirect('/login');
            }

            /** @var User $user */
            $user = Auth::user();

            if ($user->super_admin) {
                $rules = ['*'];
            } else if (!empty($credentials) && $credentials !== ['*']) {
                $rules = array_pluck($user->roles()->I18N()->where('is_active', true)->get(['i18n.id', 'name'])->toArray(), 'name', 'id');

                /** Get Group Role */
                /** @var array $groupRules */
                $groupRules = $user->groups()->where('is_active', true)->with([
                    'roles' => function ($subQuery) {
                        $subQuery->I18N()
                            ->select(['i18n.id', 'name']);

                        return $subQuery;
                    },
                ])->get()->toArray();

                foreach ($groupRules as $group) {
                    $rules = array_pluck($group['roles'], 'name', 'id') + $rules;
                }
                /** END */

                $matchRules = array_intersect($credentials, array_values($rules));

                if (empty($matchRules)) {
                    abort(404, 'Permission deny');
                }
            }
        }

        Session::put(sprintf("%s-preload", strtolower($module)), ['rules' => $rules]);

        /** Apply locale to Laravel-Loacle */
        if ($locale = Session::get('cms.locale', null)) {
            App::setLocale($locale);
        }
        /** END */

        /** Apply Session Locale */
        if (Session::has('language')) {
            \App::setLocale(Session::get('language'));
        }
        /** End*/

        $response = $next($request);

        return $response;
    }

    /**
     * Return Config variable
     *
     * @param $action  string|boolean
     * @param $key     string
     * @param $default mixed
     *
     * @return mixed
     */
    protected function getConfig($key = '', $action = false, $default = [])
    {
        $config = (array)array_get($this->config, $key, []);

        if ($action) {
            $exactConfig = (array)array_get($this->config, sprintf('%s.%s', $action, $key), []);
            $config = array_replace_recursive($config, $exactConfig);
        }

        if ($config === []) {
            $config = $default;
        }

        return $config;
    }

    /**
     * Return environment
     *
     * @return mixed
     */
    protected function getEnv()
    {
        return env('APP_ENV', 'dev');
    }
}
