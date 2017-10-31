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
    protected $description = 'Create a new crud system (v1.1)'; 

    public function __construct()
    {
        parent::__construct();
    }  



    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    { 
        $ctr    = $this->createController();
        $name   = $this->createViews();  
        $this->helpData($ctr,$name); 
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
        $controller = ucfirst($this->argument('name').'Controller');  

        $this->call('make:controller', [
            'name' => $controller,
            '--resource'=>true, 
            '--model' => $this->option('model') ? $this->option('model') : null,
        ]); 
        return $controller; 
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
        return $name;
    }

    /**
     * Generate help data
     *
     * @return void
     */
    protected function helpData($controller,$name)
    {  
        $controller = str_replace("/","\\",$controller);
        /* route */ 
        $this->line("\nRoute :"); 
        $this->comment("  Route::resource('".$name."','".$controller."');");

    }


    /**
     * progress bar
     */
    protected function progressBar()
    {  
        $this->output->progressStart(10);

        for ($i = 0; $i < 10; $i++) {
            sleep(1);

            $this->output->progressAdvance();
        }

        $this->output->progressFinish();
    }





}