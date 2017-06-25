<?php

namespace Laravision\Crud\Console; 

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Exception;
use File; 
use Illuminate\Support\Str;

class MakeCrudCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:crud
                        {name : Crud name}
                        {--model= : Crud eloquent model class}
                        {--view= : Crud views parent}';

    protected $name = 'make:crud'; 
    protected $description = 'Create a new crud system'; 

    public function __construct()
    {
        parent::__construct();
    } 

    public function fire()
    {
        $arguments = $this->arguments();
        $options = $this->option();
        dd($options);
        $this->info("Generate Crud");
        
        /*require_once __DIR__.'/../load/files/env.php';
 
        if(!$this->option('existing')){
            $this->info("Generating the default authentication");
            Artisan::call('make:auth');
            File::Delete(base_path('routes/web.php'));
            File::deleteDirectory(base_path('resources/views')); 
        } 
        require_once __DIR__.'/../load/files/route.php'; 
        File::copy(__DIR__.'/../packages/app.config.php', base_path('config/app.php')); 
        require_once __DIR__.'/../load/database.php';  
        require_once __DIR__.'/../load/files/kernel.php';  
        File::copy(__DIR__.'/../packages/app.config.auth.php', base_path('config/auth.php'));  
        File::copyDirectory(__DIR__.'/../packages/middleware', app_path('Http/Middleware'));
        Artisan::call('key:generate'); 
        
        $this->info("Migrating the database tables into your application");
        Artisan::call('migrate');
        $process = new Process('php artisan db:seed');
        $process->run(); 
        $this->info("Publishing the Project files");
        Artisan::call('vendor:publish');
        
        $this->info("Dumping the autoloaded files and reloading all new files");
        
        $process = new Process('composer dump-autoload');
        $process->run(); 
        Artisan::call('key:generate');
        $this->info("Successfully installed Admin Zero! Enjoy :)");*/
        return;
        
    }



    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    { 
        $this->createController();
        $this->createViews(); 
        //dd($this->arguments());
    }


    /**
     * Create a controller.
     *
     * @return void
     */
    protected function createController()
    { 
        //$controller = Str::studly(class_basename($this->argument('name'))).'Controller';  
        $controller = $this->argument('name').'Controller';  

        $this->call('make:controller', [
            'name' => $controller,
            '--resource'=>true, 
            '--model' => $this->option('model') ? $this->option('model') : null,
        ]);  
    }

    /**
     * Create a views.
     *
     * @return void
     */
    protected function createViews()
    {  
        $views = ['index','create','show','edit'];
        $name = strtolower(class_basename($this->argument('name')));  
        $view = (!empty($this->option('view')))?$this->option('view'):strtolower($this->argument('name'));
        $dir = base_path() . '/resources/views/' . $view;  
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }

        foreach ($views as $key => $file) {
            $path = $dir.'/'.$file.'.blade.php'; 
            $contents = file_get_contents(dirname(__DIR__).'/views/'.$file.'.blade.php');
            file_put_contents($path,$contents);
        } 
        $this->info(ucfirst($name).' views created successfully.'); 
    } 


}