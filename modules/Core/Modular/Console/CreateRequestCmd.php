<?php

namespace Modules\Core\Modular\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Filesystem\Filesystem;

class CreateRequestCmd extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'create:request {path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Request class in a given module';

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

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $packet = $this->ask('Nhập vào tên Packet: System/Api)?');
        $path = base_path();
        if($packet == "System"){
            $moduleName = $this->ask('Nhập vào tên Module:');
            $modulePath = $path.'/Modules/System/'.$moduleName;
            if(!$this->filesystem->isDirectory( $modulePath)){
                return $this->error('Module '.$moduleName.' chưa tồn tại trong hệ thống!');
            }
            $stubs = __DIR__.'/stubs/request.stub';
        }else if($packet == "Api"){
            
        }else{
            return $this->error('Packet '.$packet.' Chưa tồn tại trong hệ thống!');
        }

        $className = explode('@', $this->argument('path'))[0];
        $filePath = $modulePath."/Requests/".$className."Request.php";

        // check if module exists
        if(!is_dir($modulePath))
            return $this->error('Module '.$moduleName.' does not exists!');

        // check if file exists
        if(is_file($filePath))
            return $this->error("File just Exists");

        if(! $this->filesystem->isDirectory($modulePath.'/Requests'))
            $this->filesystem->makeDirectory($modulePath.'/Requests', 0777, true, true);

        $stub = str_replace('{{MODULE_NAME}}', $className, file_get_contents($stubs));
        $stub = str_replace('{{LAYOUT_NAME}}', $packet, $stub);
        $stub = str_replace('{{MODULE_NAME_LOWER}}', lcfirst($className), $stub);

        //build and put example file into directory
        $this->filesystem->put(str_replace('\\', '/', $filePath), $stub);

        $this->info('Tạo thành công: '.$filePath);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['path', InputArgument::REQUIRED, 'Path to save new request class with format: ModuleName@RequestClassName'],
        ];
    }
}
