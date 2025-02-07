<?php

namespace App\Lib;

class Controller
{

    public function modal($modelName)
    {
        $className = 'App\Models\\' . $modelName;

        if (class_exists($className)) {
            return new $className();
        }

        throw new \Exception("Model '$modelName' does not exist");
    }

    public function view($view, $data = [])
    {
        if (file_exists("../app/views/" . $view . ".php")) {
            require_once "../app/inc/header.php";
            require_once  "../app/views/" . $view . ".php";
            require_once "../app/inc/footer.php";
        } else {
            require_once "../app/inc/header.php";
            require_once  "../app/views/notFound.php";
            require_once "../app/inc/footer.php";
        }
    }
    protected function valideRoleUser($role_name)
    {
        if (isset($_SESSION["user"])) {
            $user = $this->modal("User");
            $result = $user->getUserRole($_SESSION["user"]["id"]);
            if ($result && isset($result["isactive"])) {
                if ($result["isactive"] == 1) {
                    if ($result["role_name"] !== $role_name) {
                        redirect("users/forbidden");
                        exit();
                    }
                } else {
                    redirect("users/activeAccount");
                    exit();
                }
            } else {
                redirect("users/login");
                exit();
            }
        } else {
            redirect("users/login");
            exit();
        }
    }
    function validateCsrfToken($token)
    {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
}
