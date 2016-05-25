<?php
namespace tuanlq11\cms;

use tuanlq11\cms\model\Group;
use \Illuminate\Filesystem\Filesystem;
use tuanlq11\cms\console\GeneratorCommand;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;
use Route, Validator, View, Session;

/**
 * Created by Fallen
 */
class CMSProvider extends ServiceProvider
{
    /**
     * Default route each module.
     */
    protected $defaultRoutes = [
        'index'       => ['uses' => 'index', 'middleware' => 'web', 'url' => "{AUTO}", 'method' => 'get'],
        'filter'      => ['uses' => 'filter', 'middleware' => 'web', 'url' => "{AUTO}", 'method' => 'post'],
        'edit'        => ['uses' => 'edit', 'middleware' => 'web', 'url' => "{AUTO}", 'method' => 'get'],
        'update'      => ['uses' => 'update', 'middleware' => 'web', 'url' => "{AUTO}", 'method' => 'post'],
        'show'        => ['uses' => 'show', 'middleware' => 'web', 'url' => "{AUTO}", 'method' => 'get'],
        'destroy'     => ['uses' => 'destroy', 'middleware' => 'web', 'url' => "{AUTO}", 'method' => 'post'],
        'create'      => ['uses' => 'create', 'middleware' => 'web', 'url' => "{AUTO}", 'method' => 'get'],
        'store'       => ['uses' => 'store', 'middleware' => 'web', 'url' => "{AUTO}", 'method' => 'post'],
        'batchAction' => ['uses' => 'batchAction', 'middleware' => 'web', 'url' => "{AUTO}", 'method' => 'post'],
    ];

    /**
     * Default format for url
     */
    protected $defaultUrl = [
        'index'       => '{PREFIX}',
        'filter'      => '{PREFIX}/{ACTION}',
        'edit'        => '{PREFIX}/{{MODULENAME}}/{ACTION}',
        'update'      => '{PREFIX}/{{MODULENAME}}/{ACTION}',
        'create'      => '{PREFIX}/create',
        'store'       => '{PREFIX}/store',
        'show'        => '{PREFIX}/{{MODULENAME}}/{ACTION}',
        'destroy'     => '{PREFIX}/{{MODULENAME}}/{ACTION}',
        'batchAction' => '{PREFIX}/{ACTION}',
    ];

    /**
     * Replace the given configuration with the existing configuration.
     *
     * @param  string $path
     * @param  string $key
     *
     * @return void
     */
    protected function replaceConfigFrom($path, $key)
    {
        $config = $this->app['config']->get($key, []);

        $this->app['config']->set($key, array_replace_recursive(require $path, $config));
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /** Merge core config */
        $this->mergeConfigFrom(__DIR__ . '/configs/config.php', 'core');
        $this->replaceConfigFrom(__DIR__ . '/configs/form-builder.php', 'laravel-form-builder');
        $this->mergeConfigFrom(__DIR__ . '/configs/menu.php', 'core.menu');

        Validator::extend('equal_field', "tuanlq11\\cms\\validator\\core@equal_field");
        Validator::extend('arr_exists', "tuanlq11\\cms\\validator\\core@arr_exists");

        $this->configRoute();
        View::addNamespace("System", base_path() . "/core/bases/module/view");

        /** Publish Migration */
        $this->initMigration($this->app);
    }

    /**
     * Copy migration to root project
     * @param Application $app
     */
    private function initMigration(Application $app)
    {
        (new Filesystem())->makeDirectory(app_path('Http/Modules/Authenticate'), 0755, false, true);

        if ($app instanceof \Illuminate\Foundation\Application && $app->runningInConsole()) {
            $migrationPath = realpath(__DIR__ . '/migration');
            $this->publishes([
                $migrationPath                             => database_path('migrations'),
                /** Copy default Authenticate module */
                __DIR__ . "/console/template/Authenticate" => app_path('Http/Modules/Authenticate'),
            ]);
        }
    }

    /**
     * Load and autoload configuration
     */
    protected function configRoute()
    {
        $modules = $this->getListModules();
        foreach ($modules as $module_name => $module) {
            foreach ($module['routes'] as $route_name => $route) {
                if (empty($route)) continue;

                /** Controller namespace. In \App\Http\Modules\%s\%sActions */
                if (!class_exists($controller = sprintf('\App\Http\Modules\%s\%sActions', $module_name, $module_name))) {
                    continue;
                };
                /** Method in controller. Ex: index|update|edit ... */
                $controllerMethod = strpos($route['uses'], '@') ? $route['uses'] : sprintf('%s@%s', $controller, $route['uses']);

                /** Generate auto url for module */
                $url = str_replace('{AUTO}', $this->generateURL($route_name, $module_name), $route['url']);

                /** Get config 'method' */
                $method = array_get($route, 'method', 'get');
                /** Get config 'as'. Default: generate module_name.route_name */
                $as = array_get($route, 'as', sprintf("%s.%s", strtolower($module_name), $route_name));

                /** Get middleware config */
                $middleware = array_get($route, 'middleware', null);

                Route::$method($url, ['uses' => $controllerMethod, 'as' => $as, 'middleware' => $middleware]);
                Route::bind(strtolower($module_name), function ($value) use ($module_name) {
                    $model_class = "App\\Models\\{$module_name}";
                    $locale      = Session::get('language', 'en');
                    if (!class_exists($model_class)) return $value;
                    /** @var Model $model */
                    $model   = new $model_class();
                    $is_i18n = method_exists($model, 'saveI18N');
                    /** @var Builder $query */
                    $query = $is_i18n ? $model_class::I18N($locale) : $model_class::query();

                    if ($is_i18n) $query->select(\DB::raw("i18n.*,{$model->getTable()}.*"));

                    $obj = $query->find($value);
                    if (!$obj) abort(404, 'Data not found');

                    return $obj;
                });
            }
        }

        /** Route change language */
        Route::get('/lang/switch', ['as' => 'switch_lang', 'uses' => 'tuanlq11\cms\controller\LocaleController@switchLang']);
        /** End */
    }

    /**
     * Generate url auto
     *
     * @param $action
     * @param $module_name
     *
     * @return string
     */
    protected function generateURL($action, $module_name)
    {
        $defaultUrl = array_get($this->defaultUrl, $action, '');
        $url        = str_replace('{PREFIX}', sprintf('/%s', strtolower($module_name)), $defaultUrl);
        $url        = str_replace('{MODULENAME}', strtolower($module_name), $url);
        $url        = str_replace('{ACTION}', strtolower($action), $url);

        return $url;
    }

    /**
     * Get List Modules
     */
    protected function getListModules()
    {
        $result  = [];
        $modules = array_filter(glob(base_path() . '/app/Http/Modules/*'), 'is_dir');
        foreach ($modules as $module_path) {
            $moduleName = basename($module_path);

            if (!\File::exists("{$module_path}/{$moduleName}Actions.php")) {
                continue;
            }

            try {
                $routes = include($module_path . "/configs/route.php");
            } catch (\Exception $ex) {
                $routes = [];
            }

            $routes              = array_merge($this->defaultRoutes, $routes);
            $result[$moduleName] = compact('module_path', 'routes');
        }

        return $result;
    }

    public function register()
    {
        $this->app['command.cms.generate'] = $this->app->share(function ($app) {
            return new GeneratorCommand();
        });

        $this->commands(['command.cms.generate']);
    }
}