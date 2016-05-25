<?php
namespace tuanlq11\cms\skeleton\module\base;


use App\Models\User;
use Html, View, Config;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Input, URL;

/**
 * Created by Mr.Tuan.
 * User: tuanlq
 * Date: 1/15/16
 * Time: 9:39 AM
 */
trait Render
{
    /**
     * Store tag html in head. Include stylesheet
     * @var array
     */
    protected $_stylesheets = [];
    /**
     * Store tag html in head. Include javascript
     * @var array
     */
    protected $_javascripts = [];
    /**
     * Store tag html in head. Include meta
     * @var array
     */
    protected $_metas = [];

    /**
     * Return layout key name use in View::make
     *
     * @param $action string
     *
     * @return string
     */
    public function getLayoutKeyName($action)
    {
        $configName = sprintf("%s.layout", $action);
        $config     = $this->getConfig($configName);

        if (!$config) {
            return 'System::layout';
        }

        $namespace = $config['namespace'];
        $namespace = is_null($namespace) ?
            $this->getModuleName() . "::" :
            (strlen($namespace) == 0 ? '' : $namespace . "::");

        return sprintf("%s%s", $namespace, $config['name']);
    }

    /**
     * Return view key name use in View::make
     *
     * @param $action string
     *
     * @return string
     */
    public function getViewKeyName($action)
    {
        $configName = sprintf("%s.view", $action);
        $config     = $this->getConfig($configName, false, strtolower($this->getModuleName()));

        if (!$config) {
            return false;
        }

        $namespace = $config['namespace'];
        $namespace = is_null($namespace) ?
            "cms::" :
            (strlen($namespace) == 0 ? '' : $namespace . "::");

        return sprintf("%s%s", $namespace, $config['name']);
    }

    /**
     * Build view head html From configuration
     *
     * @param $action string
     *
     * @return string
     */
    protected function buildViewHead($action)
    {
        $config     = $this->getConfig('view', $action);
        $stylesheet = array_get($config, 'stylesheet', []);
        $javascript = array_get($config, 'javascript', []);
        $meta       = array_get($config, 'meta');

        array_walk($stylesheet, function (&$item) {
            $item = Html::style($item);
        });

        array_walk($javascript, function (&$item) {
            $item = Html::script($item);
        });

        array_walk($meta, function (&$item) {
            $item = Html::meta($item);
        });

        $this->_stylesheets = implode("\n", $stylesheet);
        $this->_javascripts = implode("\n", $javascript);
        $this->_metas       = implode("\n", $meta);

        return $this;
    }

    /**
     * Return view for action
     *
     * @param $action string
     * @param $params []
     * @param $object Model
     * @param $view string
     *
     * @return View
     */
    protected function renderView($action = null, $params = [], $object = null, $view = null)
    {
        $action = $action ? $action : $this->action;
        $label  = array_get($this->getConfig("{$action}"), "label", $action);
        $layout = $this->getLayoutKeyName($action);
        $view   = $view ? $view : $this->getViewKeyName($action);

        $controller  = $this;
        $javascripts = $this->_javascripts;
        $stylesheets = $this->_stylesheets;
        $metas       = $this->_metas;
        $menu        = Config::get('core.menu', []);
        $is_iframe   = Input::get('is_iframe', false);

        /** Parse iframe */
        $iframes = $this->getConfig($action . '.iframes');
        array_walk($iframes, function (&$val) use ($object) {
            if (!($source = array_get($val, 'source'))) return;
            $sourceType = explode(':', $source)[0];
            if ($sourceType == 'action') $source = $this->getGeneratedUrl($source);

            $type   = array_get($val, 'param_type', 'static');
            $params = array_get($val, 'params', []);

            if ($type == 'object' && $object) {
                foreach ($params as $pk => $pv) {
                    $params[$pk] = $object->$pv;
                }
            }
            $params['is_iframe'] = true;

            $val = sprintf("%s?%s", $source, http_build_query($params));
        });
        /** End */

        return View::make($view, array_merge(get_defined_vars(), $params));
    }

    /**
     * Apply html generated object action
     * And Apply MiddlewareListColumn
     *
     * @param LengthAwarePaginator $items
     */
    protected function generateHTMLObjAction(&$items)
    {
        $action        = $this->action;
        $objectActions = $this->getConfig("{$action}.object_action");
        $fields        = array_keys($this->getListFieldsConfig());

        foreach ($items as $key => &$item) {

            $item->append($fields);
            /**
             * Loop for call method apply data
             * @var  $column
             * @var  $value
             */
            foreach ($fields as $field) {
                $methodName = sprintf("%sMiddlewareListColumn", Str::studly($field));
                if (!method_exists($this, $methodName)) continue;
                $item->$field = $this->$methodName($item, $item->$field);
            }

            foreach ($objectActions as $objectAction => $config) {
                $objectAction = sprintf("%sObjectAction", $objectAction);
                if (!method_exists($this, $objectAction) || !$config) continue;
                $item["_objectActions"] .= $this->$objectAction($item, $config);
            }
        }
    }
}