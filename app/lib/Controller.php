<?php
    namespace App\Lib;
    class Controller  {

        public function modal($modelName)
        {
            $className = 'App\Models\\' . $modelName;

            if (class_exists($className)) {
                return new $className();
            }

            throw new \Exception("Model '$modelName' does not exist");
        }

        public function view($view,$data = []){
            if(file_exists("../app/views/". $view . ".php")){
                require_once "../app/inc/header.php";
                require_once  "../app/views/". $view . ".php";
                require_once "../app/inc/footer.php";
            }else {
                require_once "../app/inc/header.php";
                require_once  "../app/views/notFound.php";
                require_once "../app/inc/footer.php";
            }
        }
    }