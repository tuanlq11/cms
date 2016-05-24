<?php
namespace Core\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use \Illuminate\Filesystem\Filesystem;

/**
 * Created by Mr.Tuan.
 * User: tuanlq
 * Date: 1/22/16
 * Time: 7:41 AM
 */
class GeneratorCommand extends Command
{
    /** @var  Filesystem */
    protected $file;
    /** @var string */
    protected $module_path = "app/Http/Modules/";
    /** @var string */
    protected $name = "core:generate";

    /**
     * GeneratorCommand constructor.
     */
    public function __construct()
    {
        $this->module_path = sprintf("%s/%s", base_path(), 'app/Http/Modules');
        $this->file = new Filesystem();

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $moduleName = $this->argument('Module Name');
        $modulePath = $this->module_path . "/" . $moduleName;
        $existModules = array_map('strtolower', $this->file->directories($this->module_path));
        if (in_array(strtolower($modulePath), $existModules)) {
            $this->error("Module is exist");
        }
        foreach (['', '/configs', '/forms', '/filters', '/views'] as $sub) {
            $this->file->makeDirectory($modulePath . $sub, 0777, true, true);
        }
        $actionContent = str_replace('{MODULE}', $moduleName, file_get_contents(__DIR__ . '/Actions.php.raw'));
        $formContent = str_replace('{MODULE}', $moduleName, file_get_contents(__DIR__ . '/Form.php.raw'));
        $filterContent = str_replace('{MODULE}', $moduleName, file_get_contents(__DIR__ . '/Filter.php.raw'));

        $this->file->put($modulePath . "/{$moduleName}Actions.php", $actionContent);
        $this->file->put($modulePath . "/forms/{$moduleName}Form.php", $formContent);
        $this->file->put($modulePath . "/filters/{$moduleName}Filter.php", $filterContent);

        $this->file->copy(__DIR__ . "/../bases/module/config/default.php", $modulePath . "/configs/config.php");
        $this->file->copy(__DIR__ . "/../bases/module/config/default.php", $modulePath . "/configs/config.php");
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