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
    protected $description = 'Create a new crud system (v1.2)'; 

    protected $views = ['index','create','show','edit'];

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
        $type = $this->confirm('Run default CRUD system ?',true);
        if ($type) {
           $this->defaultCrud();
        }else{
           $this->advancedCrud();
        }
    }

    /**
     * Execute the default CRUD
     *
     * @return mixed
     */
    public function defaultCrud()
    {  
        $this->controlCrud();
        $this->alert('     Run default CRUD !     ');
        $this->output->progressStart(3);
        $ctr    = $this->createController();
        $view   = $this->createViews(); 
        sleep(1); 
        $this->output->progressFinish();
        $this->getDetails($ctr,$view);
        $this->getRoute($ctr,$view);

    }

    /**
     * Execute the advanced CRUD
     *
     * @return mixed
     */
    public function advancedCrud()
    {  

        $this->controlCrud();
        $this->alert('     Run new CRUD !     ');
        $this->output->progressStart(3);
        $ctr    = $this->writeController();  
        $view   = $this->writeViews(); 
        sleep(1); 
        $this->output->progressFinish(); 
        $this->getDetails($ctr,$view);
        $this->getRoute($ctr,$view);

    }


    /**
     * Create a controller.
     *
     * @return void
     */
    protected function createController()
    {    
        $name = str_replace("Controller", "", $this->argument('name'));
        $model = $this->option('model');
        $controller = ucfirst($name.'Controller'); 

        $this->callSilent('make:controller', [
            'name' => $controller,
            '--resource'=>true, 
            '--model' => $model ? 'Models/'.$model : null,
        ]);  
        sleep(1);
        $this->output->progressAdvance();
        return $controller; 
    }


    /**
     * Write new controller.
     *
     * @return void
     */
    protected function writeController()
    {    
        $name = str_replace("Controller", "", $this->argument('name'));
        $explode = explode('/', $name);
        $model = $this->option('model'); 
        $controller = ucfirst($name.'Controller'); 
        $view = str_replace('/', '.', (!empty($this->option('view')))?$this->option('view'):strtolower($this->argument('name')));
        $ctrname = ucfirst(last($explode).'Controller'); 
        $namespace = str_replace('/', '\\', explode('/'.last($explode), $this->argument('name'))[0]);

        $defaultCtr = file_get_contents(dirname(__DIR__).'/files/defaultController.php'); 
        $defCtrWithModel = file_get_contents(dirname(__DIR__).'/files/modelController.php'); 
 
        if (!empty($model)) {
           $this->callSilent('make:model', [
                'name' => 'Models/'.$model
            ]);
           $fileCtr = $this->render($defCtrWithModel,compact(['namespace','ctrname','view','model']));
        }else{ 
            $fileCtr = $this->render($defaultCtr,compact(['namespace','ctrname','view']));
        }

        $dir = app_path('Http/Controllers/'.$namespace);
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        } 
        $file = str_replace('/', '\\', $dir.'/'.$ctrname.'.php');
        file_put_contents($file, $fileCtr);
 
        sleep(1);
        $this->output->progressAdvance();
        return $controller; 
         
    }

    /**
     * Create a views.
     *
     * @return array($name,$view)
     */
    protected function createViews()
    {  
        $url = $this->argument('name'); 
        $view = (!empty($this->option('view')))?$this->option('view'):strtolower($url);
        $name = strtolower(class_basename($url));  
        $dir = base_path() . '/resources/views/' . $view;  
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }

        foreach ($this->views as $file) {
            $path = $dir.'/'.$file.'.blade.php'; 
            $contents = "<h1>".ucfirst($file)." page</h1>";
            file_put_contents($path,$contents);
        }   
        
        sleep(1);
        $this->output->progressAdvance();
        return ['name'=>$name,'view'=>$view];
    }

    /**
     * Create a views.
     *
     * @return array($name,$view)
     */
    protected function writeViews()
    {  
        $url = $this->argument('name'); 
        $view = (!empty($this->option('view')))?$this->option('view'):strtolower($url);
        $name = strtolower(class_basename($url));  
        $dir = base_path() . '/resources/views/' . $view;  
        $defaultViews = dirname(__DIR__).'/files/defaultViews';
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }

        foreach ($this->views as $file) {
            $blade = '/'.$file.'.blade.php'; 
            $contents = file_get_contents($defaultViews.$blade);
            file_put_contents($dir.$blade,$contents);
        }   
        
        sleep(1);
        $this->output->progressAdvance();
        return ['name'=>$name,'view'=>$view];
    }
 

    /**
     * get Route
     *
     * @return void
     */
    protected function getDetails($ctr,$viewData)
    {   
        $this->line("\nDetails :"); 
        $this->comment('  CRUD controller "'.$ctr.'" created successfully.');
        $this->comment('  CRUD views folder "'.ucfirst($viewData['name']).'" [views/'.$viewData['view'].'] created successfully.'); 

    }
 

    /**
     * get Route
     *
     * @return void
     */
    protected function getRoute($controller,$viewData)
    {  
        $controller = str_replace("/","\\",$controller);
        /* route */ 
        $this->line("\nPaste in route file (exp : routes/web.php) :"); 
        $this->comment("  Route::resource('".$viewData['name']."','".$controller."');");

    }
 

    /**
     * Controle CRUD
     *
     * @return void
     */
    protected function controlCrud()
    {  
        /* find controller */
        $ctr = $this->argument('name').'Controller';
        $controllerFile = ucfirst($ctr.'.php');
        if (is_file(app_path('Http/Controllers/'.$controllerFile))) {
            $this->error('Controller "'.$ctr.'" exist ! ');
            $force = $this->confirm('Force and replace with new controller ?',false);
            if(!$force){ 
                exit;
            }
        }
        /* find views */
        $dirViews =  resource_path('views/'.strtolower($this->argument('name')));
        $files = [];
        foreach ($this->views as $key => $view) {
            if (is_file($dirViews.'/'.$view.'.blade.php')) {
                $files[] = $view;
            }
        }
        if (count($files)>0) {
            $this->error('Views '.json_encode($files).' exist ! ');
            $force = $this->confirm('Force and replace with new views ?',false);
            if(!$force){ 
                exit;
            }
        }

    }


    /**
     * render controller file
     */
    protected function render($file,$data)
    {   
        $search = [];
        $replace = [];
        foreach ($data as $key => $item) {
            $search[] = '{{'.$key.'}}';
            $replace[] = $item;
        } 
        return str_replace($search, $replace, $file);
    }


    /**
     * progress bar
     */
    protected function progressBar($val=10)
    {  
        $this->output->progressStart($val);

        for ($i = 0; $i < $val; $i++) {
            sleep(1);

            $this->output->progressAdvance();
        }

        $this->output->progressFinish();
    }





}