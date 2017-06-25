<?php

namespace Laravision\Crud; 

use Illuminate\Support\ServiceProvider; 

class CrudServiceProvider extends ServiceProvider
{  
    public function boot() {   
    } 

    public function register(){  

         $this->commands([
                Console\MakeCrudCommand::class, 
            ]);

    } 


}
