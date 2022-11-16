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

    final public function render(string $template, array $data = [], array $mergeData = []): void
    {
        echo $this->config($template, $data, $mergeData);
    }

    final public function unsetFlashSession(string $var): void
    {
        foreach ($_SESSION as $key => $value) {
            if (str_contains($key, $var)) {
                unset($_SESSION[$key]);
            }
        }
    }

    private function directive(): void
    {
        //
    }

    private function config(string $template, array $data = [], array $mergeData = []): mixed
    {
        $file = "../resources/views/" . str_replace(".", "/", $template) . ".blade.php";
        if (file_exists($file)) {
            return $this->view->render(str_replace(".", "\\", $template), $data, $mergeData);
        } else {
            throw new Exception("View not found");
        }
    }
}
