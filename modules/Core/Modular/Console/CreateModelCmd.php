<?php

namespace Modules\Core\Modular\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Filesystem\Filesystem;

class CreateModelCmd extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'create:model {path} {--packet=Api} {--module=default}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Khởi tạo model';

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * CreateModelCmd constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->filesystem = new Filesystem();
    }

    protected $adminFolder = "";

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
            $stubs = __DIR__ . '/stubs/api/model.stub';
            $className = explode('@', $this->argument('path'))[0];
            if ($adminFolder == 'default') {
                $this->adminFolder = "";
                $filePath = $modulePath . "/Models/" . $className . "Model.php";
            } else if ($adminFolder == 'Admin') {
                $this->adminFolder = "\Admin";
                $filePath = $modulePath . "/Models/Admin/" . $className . "Model.php";
            } else {
                return $this->error('Giá trị nhập không đúng');
            }
        } else {
            $moduleName = $this->option('module');
            $modulePath = $path . '/Modules/'.$packet.'/' . $moduleName;
            if (!$this->filesystem->isDirectory($modulePath)) {
                return $this->error('Module ' . $moduleName . ' chưa tồn tại trong hệ thống!');
            }
            $stubs = __DIR__ . '/stubs/model.stub';
            $className = explode('@', $this->argument('path'))[0];
            $filePath = $modulePath . "/Models/" . $className . "Model.php";

            // check if module exists
            if (!is_dir($modulePath))
                return $this->error('Module ' . $modulePath . ' does not exists!');
        }

        // check if file exists
        if (is_file($filePath))
            return $this->error("File just Exists");

        if (!$this->filesystem->isDirectory($modulePath . '/Models'))
            $this->filesystem->makeDirectory($modulePath . '/Models', 0777, true, true);

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
            ['path', InputArgument::REQUIRED, 'Path to save new model class with format: ModuleName@ModelClassName'],
        ];
    }
}
