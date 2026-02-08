<?php

namespace App\Core;

class View
{
    private static string $basePath;
    private static array $shared = [];

    public static function setBasePath(string $path): void
    {
        self::$basePath = rtrim($path, '/');
    }

    public static function share(string $key, mixed $value): void
    {
        self::$shared[$key] = $value;
    }

    public static function render(string $view, array $data = [], ?string $layout = 'layouts/site'): void
    {
        $viewFile = self::viewPath($view);
        $layoutFile = $layout ? self::viewPath($layout) : null;

        $vars = array_merge(self::$shared, $data);
        extract($vars, EXTR_SKIP);

        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        if ($layoutFile) {
            require $layoutFile;
            return;
        }

        echo $content;
    }

    private static function viewPath(string $view): string
    {
        $view = str_replace('.', '/', $view);
        $path = self::$basePath.'/'.$view.'.php';
        if (! file_exists($path)) {
            http_response_code(500);
            echo 'View not found: '.$view;
            exit;
        }
        return $path;
    }
}
