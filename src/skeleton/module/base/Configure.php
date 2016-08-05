<?php
namespace tuanlq11\cms\skeleton\module\base;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Cache, Schema, Config;

/**
 * @property string action
 */
trait Configure
{
    /** Name of cache to store form-filter object serialization */
    protected $cached_filter_form_object_name = "cms.{MODULE}.{ACTION}.form-filter.object";

    /** Name of cache store configuration string **/
    protected $cached_configuration_name = "core.{MODULE}.{ACTION}.config.cached";

    /**
     * Prefix path configuration static
     *
     * @var string
     */
    protected $static_configuration_prefix = "/configs/";

    /**
     * Result of mered from all config
     * Store config get from Default -> Global -> Static config -> Database config
     *
     * @var array
     */
    private $config = [];

    /**
     * This is default config for all module
     *
     * @var array
     */
    protected $coreConfig = [];

    /**
     * Default core config for module
     *
     * @var array $default_configuration
     */
    protected $default_configuration = [];

    /**
     * Global config in /config/{ModuleName}.php
     *
     * @var array $default_configuration
     */
    protected $global_configuration = [];

    /**
     * Static config in app/Http/Modules/{ModuleName}/config/config.php
     *
     * @var array $default_configuration
     */
    protected $static_configuration = [];

    /**
     * Dynamic config has load from Database. In table generate_configuration
     * Table schema:
     *  + id - module_name - environment - action - content(json)
     * But, only read from database 1 time. After, this configuration cached for optimize performance
     * Cache Schema:
     *  + name #Hash environment + module_name + action
     *
     * Dynamic config has priority highest
     *
     * @var array $default_configuration
     */
    protected $dynamic_configuration = [];

    /**
     * View configuration has load from Local Config -> ModuleConfig -> Core Config
     */
    protected $view_configuration = [];

    /**
     * Store list field get form config, at first read
     */
    protected $list_field = [];

    /**
     * Load default configuration in core system
     *
     * @return $this
     */
    protected function loadDefaultConfiguration()
    {
        $config                      = include(__DIR__ . "/../config/default.php");
        $this->default_configuration = $config;

        return $this;
    }

    /**
     * Load default configuration in core system
     *
     * @return $this
     */
    protected function loadGlobalConfiguration()
    {
        $path                       = strtolower($this->getModuleName());
        $config                     = Config::get($path, []);
        $this->global_configuration = $config;

        return $this;
    }

    /**
     * Load dynamic configuration from Database | Default table: Permission
     */
    protected function loadDynamicConfig()
    {
        $locale    = $this->getCurrentLocale();
        $modelName = "tuanlq11\\cms\\model\\Permission";
        /** @var Model $model */
        $model = new $modelName();
        $query = $model->query();

        $query->whereRaw("upper(module) like ?", ["%" . strtoupper($this->getModuleName()) . "%"]);
        $query->with([
            'role' => function ($role) use ($locale) {
                $role->I18N($locale)->select("*");
            },
        ]);
        $rawConfig      = $query->get()->toArray();
        $configurations = [];
        foreach ($rawConfig as $config) {
            $action = $config['action'];
            if (!isset($configurations[$action])) $configurations[$action] = ['credentials' => []];
            $configurations[$action]['credentials'][] = $config['role']['id'];
        }

        $this->dynamic_configuration['all'] = $configurations;
    }

    /**
     * Load Static in local directory module
     *
     * @return $this
     */
    protected function loadStaticConfiguration()
    {
        $path = trim($this->static_configuration_prefix, '/');
        $path = sprintf("%s/%s/%s", rtrim($this->getModulePath(), '/'), $path, 'config.php');

        if (!\File::exists($path)) {
            return $this;
        }

        $this->static_configuration = include($path);

        return $this;
    }

    /**
     * Load core Config. This is config available for all module
     *
     * @return array
     */
    protected function loadCoreConfig()
    {
        $allModule  = Config::get('cms.module.all', []);
        $thisModule = Config::get("cms.module.{$this->getModuleName()}", []);

        $this->coreConfig = array_replace_recursive($allModule, $thisModule);

        return $this->coreConfig;
    }

    /**
     * Remove last node not exits in array 2 but exists in array 1
     *
     * @param $arr1
     * @param $arr2
     *
     * @return mixed
     */
    protected function clear_last_node_empty($arr1, $arr2)
    {
        foreach ($arr1 as $key => $value) {
            if (array_key_exists($key, $arr2)) {
                if (is_array($value)) {
                    $arr1[$key] = $this->clear_last_node_empty($value, $arr2[$key]);
                } else if ($arr2[$key] === '{DISABLED}') {
                    unset($arr1[$key]);
                }
            }
        }

        return $arr1;
    }

    /**
     * Prepare variable for load config
     */
    protected function prepareVariable()
    {
        /** Init cache key */
        $this->cached_configuration_name      = strtolower(str_replace('{ACTION}', $this->action, str_replace('{MODULE}', $this->getModuleName(), $this->cached_configuration_name)));
        $this->cached_filter_form_object_name = strtolower(str_replace('{ACTION}', $this->action, str_replace('{MODULE}', $this->getModuleName(), $this->cached_filter_form_object_name)));
    }

    /**
     * Load all configuration for action
     */
    protected function initConfiguration()
    {
        $this->prepareVariable();

        if (!($this->config = Cache::get($this->cached_configuration_name, null))) {
            $this->loadCoreConfig();
            $this->loadDefaultConfiguration();
            $this->loadGlobalConfiguration();
            $this->loadStaticConfiguration();
            $this->loadDynamicConfig();

            $this->config = array_replace_recursive(
                $this->coreConfig,
                $this->default_configuration,
                $this->global_configuration,
                $this->dynamic_configuration
//                $this->static_configuration
            );

            $this->config = array_merge_recursive($this->static_configuration, $this->config);
            $this->config = array_replace_recursive($this->config, $this->static_configuration);

            $allEnvConfig = array_get($this->config, 'all', []);
            $envConfig    = array_get($this->config, $this->getEnv(), []);

            $this->config = array_replace_recursive($allEnvConfig, $envConfig);

            /** Last filter config not exists */
            $allEnvStaticConfig = array_get($this->static_configuration, 'all', []);
            $envStaticConfig    = array_get($this->static_configuration, $this->getEnv(), []);

            $this->config = $this->clear_last_node_empty($this->config, array_replace_recursive($allEnvStaticConfig, $envStaticConfig));
            /** END */

            /** Cache Configuration */
            $cacheConfiguration = $this->getConfig("cache.configuration", $this->action);
            if ($cacheConfiguration['enabled']) {
                Cache::put($this->cached_configuration_name, $this->config, Carbon::now()->addSecond($cacheConfiguration['lifetime']));
            } else {
                Cache::forget($this->cached_configuration_name);
            }
            /** END */
        }

        return $this;
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
    public function getConfig($key = '', $action = false, $default = [])
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
     * Get local config to only focus action
     *
     * @param      $key
     * @param null $default
     */
    public function getActionConfig($key = null, $default = null)
    {
        $key = $key ? '.' . $key : '';

        return array_get($this->config, "{$this->action}{$key}", $default);
    }

    /**
     * Get field config
     *
     * @param $action boolean
     *
     * @return array
     */
    public function getFieldsConfig($action = false)
    {
        $fieldsRaw = $this->getConfig('field', $action);
        $fields    = [];

        array_walk($fieldsRaw, function ($item, $key) use (&$fields) {
            if (is_string($item)) {
                $key  = $item;
                $item = [
                    'label' => Str::slug($key),
                ];
            }

            $fields[$key] = $item;
        });

        return $fields;
    }

    /**
     * Get List field config for index page. Or custom page
     * Show 'list'
     * Hide 'hidden'
     *
     * @return array
     */
    public function getListFieldsConfig()
    {
        if ($this->list_field === []) {
            $action        = $this->action;
            $fields        = $this->getFieldsConfig();
            $hiddenFields  = $this->getConfig('hidden', $action);
            $listFieldsRaw = $this->getConfig('list', $action);

            if ($listFieldsRaw === [] && ($model = $this->getModel())) {
                $listFieldsRaw = Schema::getColumnListing($model->getTable());
            }

            $listFields = [];

            array_walk($listFieldsRaw, function ($item, $key) use ($fields, &$listFields) {
                if (is_string($item)) {
                    $key  = $item;
                    $item = [
                        'label' => Str::slug($key),
                    ];
                }
                if (isset($fields[$key])) {
                    $item = array_merge($fields[$key], $item);
                }
                $listFields[$key] = $item;
            });

            $this->list_field = array_except($listFields, $hiddenFields);
        }

        return $this->list_field;
    }
}