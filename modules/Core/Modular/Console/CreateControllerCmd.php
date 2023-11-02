<?php

namespace Modules\Core\Modular\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Filesystem\Filesystem;

class CreateControllerCmd extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature  = 'create:controller {path} {--packet=Api} {--module=default}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tạo controller cho System';

    /**
     * @var Filesystem
     */
    protected $filesystem;

    protected $adminFolder = "";

    /**
     * CreateControllerCmd constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->filesystem = new Filesystem();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $packet = $this->option('packet');
        $path = base_path();
        if ($packet == "Api") {
            $adminFolder = $this->option('module');
            $modulePath = $path . '/Modules/Api';
            $stubs = __DIR__ . '/stubs/api/controller.stub';
            $className = explode('@', $this->argument('path'))[0];
            if ($adminFolder == 'default') {
                $this->adminFolder = "";
                $filePath = $modulePath . "/Controllers/" . $className . "Controller.php";
            } else if ($adminFolder == 'Admin') {
                $this->adminFolder = "\Admin";
                $filePath = $modulePath . "/Controllers/Admin/" . $className . "Controller.php";
            } else {
                return $this->error('Giá trị nhập không đúng');
            }
        } else {
            $moduleName = $this->option('module');
            $modulePath = $path . '/Modules/'.$packet.'/' . $moduleName;
            if (!$this->filesystem->isDirectory($modulePath)) {
                return $this->error('Module ' . $moduleName . ' chưa tồn tại trong hệ thống!');
            }
            $stubs = __DIR__ . '/stubs/controller.stub';
            $className = explode('@', $this->argument('path'))[0];
            $filePath = $modulePath . "/Controllers/" . $className . "Controller.php";
        }

        // check if file exists
        if (is_file($filePath))
            return $this->error("File " . $filePath . " đã tồn tại trong hệ thống!");

        if (!$this->filesystem->isDirectory($modulePath . '/Controllers'))
            $this->filesystem->makeDirectory($modulePath . '/Controllers', 0777, true, true);

        $stub = str_replace('{{MODULE_NAME}}', $className, file_get_contents($stubs));
        $stub = str_replace('{{LAYOUT_NAME}}', $packet, $stub);
        $stub = str_replace('{{MODULE_NAME_LOWER}}', lcfirst($className), $stub);
        $stub = str_replace('{{ADMIN_FOLDER}}', $this->adminFolder, $stub);

        //build and put example file into directory
        $this->filesystem->put(str_replace('\\', '/', $filePath), $stub);

        $this->info('Tạo thành công: ' . $filePath);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['path', InputArgument::REQUIRED, 'Path to save new controller class with format: ModuleName@ControllerClassName'],
        ];
    }
}
