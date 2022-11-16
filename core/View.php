<?php

namespace Core;

use Exception;
use Jenssegers\Blade\Blade;

final class View
{
    private Blade $view;

    public function __construct()
    {
        $this->view = new Blade("../resources/views", "../storage/views");
        $this->directive();
    }

    final public function render(string $template, array $data = [], array $mergeData = [])
    {
        echo $this->config($template, $data, $mergeData);
    }
    
    final public function unsetFlashSession(string $var)
    {
        foreach ($_SESSION as $key => $value) {
            if (str_contains($key, $var)) {
                unset($_SESSION[$key]);
            }
        }
    }

    private function directive()
    {
        //
    }

    private function config(string $template, array $data = [], array $mergeData = [])
    {
        $file = "../resources/views/" . str_replace(".", "/", $template) . ".blade.php";
        if (file_exists($file)) {
            return $this->view->render(str_replace(".", "\\", $template), $data, $mergeData);
        } else {
            throw new Exception("View not found");
        }
    }
}
