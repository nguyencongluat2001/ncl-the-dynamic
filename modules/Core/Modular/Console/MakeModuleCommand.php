<?php

namespace Modules\Core\Modular\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Support\Facades\DB;

/**
	* Author: Toanph
    * Date: 17/01/2017
*/
class MakeModuleCommand extends Command
{

	protected $signature = 'create:module {path} {--packet=Api} {--module=default}';
    protected $filesystem;
	protected $description = 'Khởi tạo một module mới';
    protected $adminFolder = "";

    public function __construct(Filesystem $filesystem)
    {
        parent::__construct();
        
        $this->filesystem = $filesystem;
    }

    /**
     * Idea: Thuc thi mot lenh cmd tao modul
	 * @return void
	 */
	public function handle()
	{
        $return = false;
        $pathmigration = $this->getModuleName();
        $path = base_path();

        $packet = $this->option('packet');
        if($packet == "Api"){
            $adminFolder = $this->option('module');
            if($adminFolder == 'default'){
                $this->adminFolder = "";
            }else if($adminFolder == 'Admin'){
                $this->adminFolder = "\Admin";
            }else{
                return $this->error('Giá trị nhập không đúng');
            }
            $path .= '/Modules/Api';
            $this->genResource($path,$packet);
        }else{
            $this->packet = $packet;
            // Kiểm tra module
            if(!$this->filesystem->isDirectory($path.'/Modules/'.$packet)){
                return $this->error('Layout '.$packet.' chưa tồn tại trong hệ thống!');
            }
            $pathmigration = $packet.'\\'.$this->getModuleName();
            $path .= '/Modules/'.$packet.'/'.$this->getModuleName();
            if($this->filesystem->isDirectory($path)){
                return $this->error('Module:  '.$this->getModuleName().' đã tồn tại trong hệ thống!');
            }
            $this->filesystem->makeDirectory($path, 0777, true, true);
            // Tao folder mo hinh MVC
            $this->genRoutes($path,$packet);
            $this->genViews($path,$packet);
            $this->genJavascipt($pathmigration,$packet);
        }

        $this->genControllers($path,$packet);
        $this->genModels($path,$packet);
        $this->genRepository($path,$packet);
        $this->genService($path,$packet);
        $this->genRequests($path,$packet);

		$this->info('Đã tạo thành công module: '.$this->getModuleName());
	}

    public function genResource($path,$layoutname)
	{
        $path = $path."/Resources";
        if(! $this->filesystem->isDirectory($path))
            $this->filesystem->makeDirectory($path, 0777, true, true);
        if($this->adminFolder !== ""){
            $path = $path.$this->adminFolder;
            if(! $this->filesystem->isDirectory($path))
                $this->filesystem->makeDirectory($path, 0777, true, true);
        }
        $stubs  = file_get_contents(__DIR__.'/stubs/api/resource.stub');
        $stub = str_replace('{{MODULE_NAME}}', $this->getModuleName(),  $stubs);
        $stub = str_replace('{{LAYOUT_NAME}}', $layoutname, $stub);
        $stub = str_replace('{{ADMIN_FOLDER}}', $this->adminFolder, $stub);
        $stub = str_replace('{{MODULE_NAME_LOWER}}', lcfirst($this->getModuleName()), $stub);
        //$this->line($stub);
        $filePath = str_replace('\\', '/', $path."/".$this->getModuleName()."Resource.php");
        if(!is_file($filePath))
            $this->filesystem->put($filePath, $stub);
	}

    public function genService($path,$layoutname)
	{
        $path = $path."/Services";
        if(! $this->filesystem->isDirectory($path))
            $this->filesystem->makeDirectory($path, 0777, true, true);
        if($layoutname == 'Api'){
            if($this->adminFolder !== ""){
                $path = $path.$this->adminFolder;
                if(! $this->filesystem->isDirectory($path))
                    $this->filesystem->makeDirectory($path, 0777, true, true);
            }
            $stubs  = file_get_contents(__DIR__.'/stubs/api/service.stub');
        }else{
            $stubs  = file_get_contents(__DIR__.'/stubs/service.stub');
        }
        $stub = str_replace('{{MODULE_NAME}}', $this->getModuleName(),  $stubs);
        $stub = str_replace('{{LAYOUT_NAME}}', $layoutname, $stub);
        $stub = str_replace('{{ADMIN_FOLDER}}', $this->adminFolder, $stub);
        $stub = str_replace('{{MODULE_NAME_LOWER}}', lcfirst($this->getModuleName()), $stub);
        //$this->line($stub);
        $filePath = str_replace('\\', '/', $path."/".$this->getModuleName()."Service.php");
        if(!is_file($filePath))
            $this->filesystem->put($filePath, $stub);
	}

    public function genRepository($path,$layoutname)
	{
        $path = $path."/Repositories";
        if(! $this->filesystem->isDirectory($path))
            $this->filesystem->makeDirectory($path, 0777, true, true);
        if($layoutname == 'Api'){
            if($this->adminFolder !== ""){
                $path = $path.$this->adminFolder;
                if(! $this->filesystem->isDirectory($path))
                    $this->filesystem->makeDirectory($path, 0777, true, true);
            }
            $stubs  = file_get_contents(__DIR__.'/stubs/api/repository.stub');
        }else{
            $stubs  = file_get_contents(__DIR__.'/stubs/repository.stub');
        }
        $stub = str_replace('{{MODULE_NAME}}', $this->getModuleName(),  $stubs);
        $stub = str_replace('{{LAYOUT_NAME}}', $layoutname, $stub);
        $stub = str_replace('{{ADMIN_FOLDER}}', $this->adminFolder, $stub);
        $stub = str_replace('{{MODULE_NAME_LOWER}}', lcfirst($this->getModuleName()), $stub);
        //$this->line($stub);
        $filePath = str_replace('\\', '/', $path."/".$this->getModuleName()."Repository.php");
        if(!is_file($filePath))
            $this->filesystem->put($filePath, $stub);
	}

	public function genRoutes($path,$layoutname)
	{
        $stub  = file_get_contents(__DIR__.'/stubs/routes.stub');
        $find = array("{{MODULE_NAME}}", "{{LAYOUT_NAME}}","{{MODULE_NAME_LOWER}}");
        $replace   = array($this->getModuleName(), $layoutname,strtolower($this->getModuleName()));
        $stub = str_replace($find, $replace, $stub);
        //$this->line($stub);
		//build and put example file into directory
		$this->filesystem->put(str_replace('\\', '/', $path."/routes.php"), $stub);
	}

    public function genViews($path,$layoutname)
    {
        $path = $path."/Views";

        if(! $this->filesystem->isDirectory($path))
            $this->filesystem->makeDirectory($path);

        $stub  = file_get_contents(__DIR__.'/stubs/view.stub');
        $find = array("{{MODULE_NAME}}", "{{LAYOUT_NAME}}","{{MODULE_NAME_LOWER}}");
        $replace   = array($this->getModuleName(), $layoutname,strtolower($this->getModuleName()));
        $stub = str_replace($find, $replace, $stub);
        //build and put example file into directory
        $this->filesystem->put($path."/index.blade.php", $stub);
    }

    public function genFolder($path,$layoutname,$folderName){
        $path = $path."/".$folderName;
        if(! $this->filesystem->isDirectory($path))
            $this->filesystem->makeDirectory($path, 0777, true, true);
    }

	public function genControllers($path,$layoutname)
	{
        $path = $path."/Controllers";
        if(! $this->filesystem->isDirectory($path))
            $this->filesystem->makeDirectory($path, 0777, true, true);
        if($layoutname == 'Api'){
            if($this->adminFolder !== ""){
                $path = $path.$this->adminFolder;
                if(! $this->filesystem->isDirectory($path))
                    $this->filesystem->makeDirectory($path, 0777, true, true);
            }
            $stubs  = file_get_contents(__DIR__.'/stubs/api/controller.stub');
        }else{
            $stubs  = file_get_contents(__DIR__.'/stubs/controller.stub');
        }

        $stub = str_replace('{{MODULE_NAME}}', $this->getModuleName(),  $stubs);
        $stub = str_replace('{{LAYOUT_NAME}}', $layoutname, $stub);
        $stub = str_replace('{{ADMIN_FOLDER}}', $this->adminFolder, $stub);
        $stub = str_replace('{{MODULE_NAME_LOWER}}', lcfirst($this->getModuleName()), $stub);
        //$this->line($stub);
        //build and put example file into directory
        $filePath = str_replace('\\', '/', $path."/".$this->getModuleName()."Controller.php");
        if(!is_file($filePath))
            $this->filesystem->put($filePath, $stub);
       
	}

    public function genModels($path,$layoutname)
    {
        $path = $path."/Models";
        if(! $this->filesystem->isDirectory($path))
            $this->filesystem->makeDirectory($path, 0777, true, true);
        if($layoutname == 'Api'){
            if($this->adminFolder !== ""){
                $path = $path.$this->adminFolder;
                if(! $this->filesystem->isDirectory($path))
                    $this->filesystem->makeDirectory($path, 0777, true, true);
            }
            $stubs  = file_get_contents(__DIR__.'/stubs/api/model.stub');
        }else{
            $stubs  = file_get_contents(__DIR__.'/stubs/model.stub');
        }
        
        $stub = str_replace('{{MODULE_NAME}}', $this->getModuleName(),  $stubs);
        $stub = str_replace('{{LAYOUT_NAME}}', $layoutname, $stub);
        $stub = str_replace('{{ADMIN_FOLDER}}', $this->adminFolder, $stub);
        $stub = str_replace('{{MODULE_NAME_LOWER}}', lcfirst($this->getModuleName()), $stub);
        //$this->line($stub);
        //build and put example file into directory
        $filePath = str_replace('\\', '/', $path."/".$this->getModuleName()."Model.php");
        if(!is_file($filePath))
            $this->filesystem->put($filePath, $stub);
    }

	public function genRequests($path,$layoutname)
    {
        $path = $path."/Requests";
        if(! $this->filesystem->isDirectory($path))
            $this->filesystem->makeDirectory($path, 0777, true, true);
        if($layoutname == 'Api'){
            $path = $path."/".$this->getModuleName();
            if(! $this->filesystem->isDirectory($path))
                $this->filesystem->makeDirectory($path, 0777, true, true);
            $stub  = file_get_contents(__DIR__.'/stubs/api/request.stub');
        }else{
            $stub  = file_get_contents(__DIR__.'/stubs/request.stub');
        }
        $find = array("{{MODULE_NAME}}", "{{LAYOUT_NAME}}","{{MODULE_NAME_LOWER}}");
        $replace   = array($this->getModuleName(), $layoutname,lcfirst($this->getModuleName()));
        $stub = str_replace($find, $replace, $stub);
        //$this->line($stub);
        $filePath = str_replace('\\', '/', $path."/".$this->getModuleName()."Request.php");
        if(!is_file($filePath))
            $this->filesystem->put($filePath, $stub);
    }

	/**
	 * Get value of name input argument
	 *
	 * @return array|string
	 */
	public function getModuleName()
	{
		return ucfirst($this->argument('path'));
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
			['path', InputArgument::REQUIRED, 'Module name.'],
		];
	}

    public function genJavascipt($pathmigration,$layoutname)
    {
        $path = base_path()."/resources/js/".$pathmigration;
        if(!$this->filesystem->isDirectory($path))
            $this->filesystem->makeDirectory($path, 0777, true, true);

        $stub  = file_get_contents(__DIR__.'/stubs/javascript.stub');
        $find = array("{{MODULE_NAME}}", "{{LAYOUT_NAME}}");
        $replace   = array($this->getModuleName(), $layoutname);
        $stub = str_replace($find, $replace, $stub);
        $this->filesystem->put($path."/JS_".$this->getModuleName().".js", $stub);

    }
    public function genXml($pathmigration)
    {
        $path = base_path()."/xml/".$pathmigration;

        if(!$this->filesystem->isDirectory($path))
            $this->filesystem->makeDirectory($path, 0777, true, true);


        $stub = str_replace('{{MODULE_NAME}}', $this->getModuleName(), file_get_contents(__DIR__.'/stubs/xml.stub'));

        //build and put example file into directory
        $this->filesystem->put($path."/".$this->getModuleName().".xml", $stub);
    }
}
