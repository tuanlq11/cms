<?php
namespace tuanlq11\cms\console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use \Illuminate\Filesystem\Filesystem;

/**
 * Created by Fallen
 */
class GeneratorCommand extends Command
{
    /** @var  Filesystem */
    protected $file;
    /** @var string */
    protected $module_path = "app/Http/Modules/";
    /** @var string */
    protected $name = "cms:generate";

    /**
     * GeneratorCommand constructor.
     */
    public function __construct()
    {
        $this->module_path = sprintf("%s/%s", base_path(), 'app/Http/Modules');
        $this->file        = new Filesystem();

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $this->file->makeDirectory($this->module_path, 0777, false, true);

        $moduleName   = $this->argument('Module Name');
        $modulePath   = $this->module_path . "/" . $moduleName;
        $existModules = array_map('strtolower', $this->file->directories($this->module_path));
        if (in_array(strtolower($modulePath), $existModules)) {
            $this->error("Module is exist");
        }

        foreach (['', '/configs', '/forms', '/filters', '/views', '/models'] as $sub) {
            $this->file->makeDirectory($modulePath . $sub, 0777, true, true);
        }

        $actionContent = str_replace('{MODULE}', $moduleName, file_get_contents(__DIR__ . '/template/Actions.php.raw'));
        $formContent   = str_replace('{MODULE}', $moduleName, file_get_contents(__DIR__ . '/template/Form.php.raw'));
        $filterContent = str_replace('{MODULE}', $moduleName, file_get_contents(__DIR__ . '/template/Filter.php.raw'));
        $modelContent  = str_replace('{MODULE}', $moduleName, file_get_contents(__DIR__ . '/template/Model.php.raw'));

        $this->file->put($modulePath . "/{$moduleName}Actions.php", $actionContent);
        $this->file->put($modulePath . "/forms/{$moduleName}Form.php", $formContent);
        $this->file->put($modulePath . "/filters/{$moduleName}Filter.php", $filterContent);
        $this->file->put($modulePath . "/models/{$moduleName}.php", $modelContent);

        $this->file->copy(__DIR__ . "/../skeleton/module/config/default.php", $modulePath . "/configs/config.php");
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['Module Name', InputArgument::REQUIRED],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [];
    }
}