<?php
namespace tuanlq11\cms\skeleton\module\base;

use tuanlq11\cms\controller\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Route, Session, Request, Config;

/**
 * Created by Fallen
 */
class Base extends Controller
{
    /**
     * Path to module
     *
     * @var string
     */
    protected $module_path = 'app/Http/Modules/{ModuleName}/';

    /**
     * List rule default. Use to map from rule to query method
     *
     * @var array
     */
    protected $ruleDefault = [
        'date',
        'string',
        'numeric',
        'boolean',
    ];

    /**
     * Map table from rule to query method
     *
     * @var array
     */
    protected $ruleToMethod = [
        'date'    => '=',
        'string'  => 'like',
        'boolean' => '=',
        'numeric' => '=',
    ];

    /**
     * Module name
     * Use for default model name
     *
     * @var string
     */
    protected $module_name;

    /**
     * Model name for query
     *
     * @var string
     */
    protected $model_name;

    /**
     * Current Action
     *
     * @var string
     */
    protected $action;

    /**
     * @return string
     */
    public function getModulePath()
    {
        $path = trim(str_replace('{ModuleName}', $this->getModuleName(), $this->module_path), '/');
        $path = sprintf("%s/%s/", base_path(), $path);

        return $path;
    }

    /**
     * Return current action function
     *
     * @return string
     */
    public function getCurrentAction()
    {
        if (!$this->action) {
            $currentNameSpace = Route::currentRouteAction();
            $this->action     = explode('@', $currentNameSpace)[1];
        }

        return $this->action;
    }

    /**
     * Return module view path
     *
     * @return string
     */
    public function getModuleViewPath()
    {
        $path = trim(str_replace('{ModuleName}', $this->getModuleName(), $this->module_path), '/');
        $path = sprintf("%s/%s/views", base_path(), $path);

        return $path;
    }

    /**
     * @return string
     */
    public function getModuleName()
    {
        $className   = class_basename($this);
        $calcModName = substr(class_basename($this), 0, strlen($className) - strlen("Actions"));

        return is_null($this->module_name) ? $calcModName : $this->module_name;
    }

    /**
     * @param string $module_name
     */
    public function setModuleName($module_name)
    {
        $this->module_name = $module_name;
    }

    /**
     * @return string
     */
    public function getModelName()
    {
        $module_name = $this->getModuleName();
        if (is_null($this->model_name)) {
            $models = [
                sprintf("App\\Http\\Modules\\{$module_name}\\models\\{$module_name}"),
                sprintf("App\\Model\\{$module_name}"),
                sprintf("tuanlq11\\cms\\model\\{$module_name}"),
            ];

            foreach ($models as $model_class) {
                if (class_exists($model_class)) {
                    $this->model_name = $model_class;

                    return $this->model_name;
                }
            }

            return null;
        } else {
            return $this->model_name;
        }
    }

    /**
     * @param string $model_name
     */
    public function setModelName($model_name)
    {
        $this->model_name = $model_name;
    }

    /**
     * Get Method from rule string in validation
     *
     * @param $rule
     *
     * @return mixed
     */
    protected function getMethodFromRule($rule)
    {
        $rulePiece = explode('|', $rule);
        foreach ($this->ruleDefault as $method) {
            if (in_array($method, $rulePiece)) {
                return isset($this->ruleToMethod[$method]) ? $this->ruleToMethod[$method] : '=';
            }
        }

        return '=';
    }

    /**
     * Return environment
     *
     * @return mixed
     */
    public function getEnv()
    {
        return env('APP_ENV', 'dev');
    }

    /**
     * Return generated url. Not working if you custom route
     *
     * @param $action
     *
     * @return string
     */
    public function getGeneratedUrl($action, $params = [], $absolute = true)
    {
        $routeName = $this->getGeneratedRoute($action);
        $url       = route($routeName, $params, $absolute);

        return $url;
    }

    /**
     * Return name of route
     *
     * @param $action
     *
     * @return string
     */
    public function getGeneratedRoute($action)
    {
        return $routeName = sprintf("%s.%s", strtolower($this->getModuleName()), $action);
    }

    /**
     * Check action is trusted with current login user
     *
     * @param $action
     *
     * @return boolean
     */
    public function isActionTrusted($action)
    {
        $rules       = Session::get(sprintf("%s-preload", strtolower($this->getModuleName())), ['rules' => []])['rules'];
        $credentials = $this->getConfig('credentials', $action);
        if ($rules === ['*'] || $credentials === ['*']) return true;
        $matchRules = array_intersect($credentials, array_keys($rules));

        return !empty($matchRules);
    }

    /**
     * Get previous url
     *
     * @param $action string
     *
     * @return string
     */
    public function getPreviousUrl($action)
    {
        $key = sprintf('log.previous.url.%s', $action);

        return Session::get($key);
    }

    /**
     * Store previous url to session
     */
    protected function logPreviousUrl()
    {
        $action = $this->action;
        $url    = Request::fullUrl();
        $key    = sprintf('log.previous.url.%s', $action);
        Session::put($key, $url);
    }

    public function parseFieldName($input)
    {
        $regex = '/\[[a-zA-Z0-9]+\]/';
        if (strpos($input, ']')) {
            $input = '[' . trim($input, '[]') . ']';
            $input = preg_replace_callback($regex, function ($str) {
                $str = ((array)$str);

                return trim($str[0], '[]') . ".";
            }, $input);
            $input = trim($input, '.');
        }

        return $input;
    }

    /**
     * Parse string|key to func name
     */
    public function parseFuncName($name)
    {
        return Str::studly($name);
    }

    /**
     * Return current locale
     */
    public function getCurrentLocale()
    {
        return Session::get('cms.locale', Config::get('app.locale', 'en'));
    }
}