<?php
namespace tuanlq11\cms;

use App\Http\Modules\Smtp\models\Smtp;
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
        /** Publish Migration, Authenticate, .. */
        $this->initCMS($this->app);

        /** Merge core config */
        if (\Schema::hasTable("smtps")) {
            $config = Smtp::where("cms_default", true)->first();
            if ($config) {
                $config         = $config->toArray();
                $config["from"] = ["address" => $config["from"], "name" => null];
                $this->app["config"]->set("mail", array_merge($this->app["config"]->get("mail"), $config));
            }
        }

        $this->mergeConfigFrom(__DIR__ . '/configs/config.php', 'cms');
        $this->replaceConfigFrom(__DIR__ . '/configs/form-builder.php', 'laravel-form-builder');
        $this->mergeConfigFrom(__DIR__ . '/configs/menu.php', 'cms.menu');

        /** Default Validator */
        Validator::extend('equal_field', 'tuanlq11\\cms\\validator\\core@equal_field');
        Validator::extend('arr_exists', 'tuanlq11\\cms\\validator\\core@arr_exists');
        /** Default Validator */

        /** Init Route config */
        $this->configRoute();

        View::addNamespace("cms", resource_path('cms'));
    }

    /**
     * Copy migration to root project
     *
     * @param Application $app
     */
    private function initCMS(Application $app)
    {
        if ($app instanceof \Illuminate\Foundation\Application && $app->runningInConsole()) {
            $migrationPath = realpath(__DIR__ . '/migration');
            (new Filesystem())->makeDirectory(app_path('Models'), 0755, false, true);
            $this->publishes([
                $migrationPath        => database_path('migrations'),
                __DIR__ . "/module"   => app_path('Http/Modules'),
                __DIR__ . "/resource" => resource_path(),
                __DIR__ . "/gulp"     => base_path(),
                __DIR__ . "/configs"  => config_path('cms'),
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
                $middleware = isset($route['middleware']) ? $route['middleware'] : ['web'];

                Route::$method($url, ['uses' => $controllerMethod, 'as' => $as, 'middleware' => $middleware]);
            }
        }

        /** Route change language */
        Route::get('/lang/switch', ['as' => 'switch_lang', 'middleware' => ['web'], 'uses' => 'tuanlq11\cms\controller\LocaleController@switchLang']);
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
                $routes = include($module_path . '/configs/route.php');
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