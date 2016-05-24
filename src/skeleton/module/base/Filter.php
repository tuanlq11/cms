<?php
namespace tuanlq11\cms\skeleton\module\base;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;
use Kris\LaravelFormBuilder\Fields\ButtonType;
use Kris\LaravelFormBuilder\Form;
use \Kris\LaravelFormBuilder\FormBuilder;
use Session, Schema;

/**
 * Class Filter
 * @method string getModuleName()
 * @method string getConfig(string $key = '')
 * @method string getMethodFromRule(string $rule)
 * @property string action
 * @package Core\Bases\Module\Base
 */
trait Filter
{
    /** @var Form */
    protected $form_filter = null;

    /**
     * Generate filter name html input
     *
     * @param $name
     *
     * @return string
     */
    protected function generateFilterName($name)
    {
        return sprintf("filters[%s]", strtolower($name));
    }

    /**
     * Get instance form filter
     *
     * @param $filterData array
     *
     * @return $this
     */
    protected function buildFormFilter($filterData = [])
    {
        $action = $this->action;
        /** @var Form $filter */
        $filter = null;

        /** List available namespace exists to class */
        $filter_classs = [
            // Static class string in custom config [1]
            $this->getConfig('index')['filter_class'],
            // Auto detect exists class in module directory [2]
            sprintf("App\\Forms\\%sFilter", $this->getModuleName()),
            // Auto detect exists class in form directory [3]
            sprintf("App\\Http\\Modules\\%s\\filters\\%sFilter", $this->getModuleName(), $this->getModuleName()),
        ];
        /** End */

        /** Check first namespace exists */
        foreach ($filter_classs as $class) {
            if (class_exists($class)) {
                $filter = $class;
                break;
            }
        }
        /** End */

        if (is_null($filter)) {
            if (env('APP_DEBUG')) {
                $message = "Not found FormFilter for module!";
//                abort(404, $message);
            }

            $this->form_filter = null;
            return null;
        }

        /**
         * Use FormBuilder to init class form
         */
        $filter_action = $this->getActionConfig('filter_action', 'filter');
        $filter        = FormBuilder::create(
            $filter,
            [
                'method' => 'post',
                'route'  => $this->getGeneratedRoute($filter_action),
                'name'   => "filters",
            ],
            $filterData
        );

        /**
         * Get List config of filter from module config
         * @var  $filterConfig
         */
        $filterConfig = $this->getConfig("{$action}.filter");

        /**
         * Create field to form if it not exists
         * @var string $key
         * @var  string $config
         */
        foreach ($filterConfig as $key => $config) {
            if ($filter->has($key)) continue;
            $key = trim($key, '[]');

            $fieldParams = [
                'default_value' => array_get($config, 'default_value', ''),
                'label'         => array_get($config, 'label', $key),
                'class'         => array_get($config, 'class', ''),
                'rules'         => array_get($config, 'validation', ''),
            ];

            if (isset($config['template'])) {
                $fieldParams['template'] = $config['template'];
            }

            $fieldParams = array_merge($fieldParams, (array)array_get($config, 'params', []));

            $filter->add($key, array_get($config, 'type', 'text'), $fieldParams);
        }
        /** End */

        /** Default two button Filter and Reset */
        $buttons = $this->getConfig("{$action}.filter_buttons.items");
        array_walk($buttons, function (&$v, $k) use ($filter) {
            $type    = array_get($v, 'type', 'submit');
            $options = array_get($v, 'options', []);
            $v       = new ButtonType($k, $type, $filter, $options);
        });
        $filter
            ->add('btnBottom', 'groupButtons', ['wrapper' => $this->getConfig("{$action}.filter_buttons.wrapper"), 'items' => $buttons])
            ->rebuildForm();
        /** End */

        $this->form_filter = $filter;
        $this->setFilterData($filterData === [] ? $this->getCurrentFilter() : $filterData);

        return $this;
    }


    /**
     * Update Form Filter data
     *
     * @param $data
     */
    protected function setFilterData($data)
    {
        foreach ($data as $key => $value) {
            if (!$this->form_filter->has($key)) continue;
            $oldField = $this->form_filter->getField($key);
            $this->form_filter->modify($key, $oldField->getType(), ['attr' => ['data-previous' => $value], 'value' => $value]);
        }
    }

    /**
     * Generate prefix for filter session
     */
    protected function getFilterPrefix()
    {
        $prefix = md5(sprintf("%s-%s-filter", env('APP_ENV'), $this->getModuleName()));
        return $prefix;
    }

    /**
     * Get Filter from request
     */
    protected function getFilter()
    {
        $data = Input::get('filters', []);
        if (!is_array($data)) {
            return [];
        }

        return $data;
    }

    /**
     * Get current filter in session
     * @return array
     */
    protected function getCurrentFilter()
    {
        return \Session::get($this->getFilterPrefix(), []);
    }

    /**
     * Get Filter validation and message in config
     * @return array
     */
    protected function getFilterValidation()
    {
        $action       = $this->action;
        $filterConfig = $this->getConfig("{$action}.filter");
        $validations  = [];
        $messages     = [];

        foreach ($filterConfig as $key => $config) {
            $validations[$key] = isset($config['validation']) ? $config['validation'] : '';
            $messages[$key]    = (isset($config['message']) && is_array($config['message'])) ?
                $config['message'] : [];
        }

        $messages = array_dot(['filters' => $messages]);

        $result = compact('validations', 'messages');

        return $result;
    }

    /**
     * Validate filter
     *
     * @return boolean
     */
    protected function validateFilter()
    {
        if (is_null($this->form_filter)) return false;
        $filterValidation = $this->getFilterValidation();
        $messages         = $filterValidation['messages'];

        $this->form_filter->validate([], $messages);

        return $this->form_filter->isValid();
    }

    /**
     * Store filter to session
     */
    protected function saveFilter()
    {
        Session::put($this->getFilterPrefix(), $this->getFilter());
    }

    /**
     * Apply filter to query
     *
     * @param &$query Builder
     *
     * @return Builder
     */
    protected function applyFilter(&$query)
    {
        $rules      = $this->getFilterValidation();
        $filterData = $this->getCurrentFilter();
        if (empty($filterData)) {
            return $query;
        }

        foreach ($rules['validations'] as $key => $rule) {
            $filterVal = isset($filterData[$key]) ? $filterData[$key] : null;
            if (is_null($filterVal) || (is_string($filterVal) && strlen($filterVal) == 0))
                continue;

            $funcName = $this->getHookFilterFuncName($key);

            if (method_exists($this, $funcName)) {
                call_user_func_array([$this, $funcName], [$key, $filterVal, &$query]);
            } else {
                $method = $this->getMethodFromRule($rule);
                $value  = $filterData[$key];

                /** Special value for 'like' method */
                if ($method == 'like') {
                    $value = "%" . $value . "%";
                }

                $query->where($key, $this->getMethodFromRule($rule), $value);
            }
        }

    }

    /**
     * Get name of function use instead system auto applyFilter
     *
     * @param $filter
     *
     * @return string
     */
    protected function getHookFilterFuncName($filter)
    {
        return sprintf('Filter%s', Str::studly($filter));
    }

    /**
     * Reset Filter to none
     */
    protected function clearFilter()
    {
        Session::forget($this->getFilterPrefix());
    }
}