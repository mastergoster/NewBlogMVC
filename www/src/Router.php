<?php
namespace App;

class Router
{
    private $router;
    private $viewPath;

    public function __construct(string $viewPath)
    {
        $this->viewPath = $viewPath;
        $this->router = new \AltoRouter();
    }

    public function get(string $uri, string $file, string $name): self
    {
        $this->router->map('GET', $uri, $file, $name);
        return $this;
    }


    public function run(): void
    {
        $match = $this->router->match();

        if (is_array($match)) {
            ob_start();
            $params = $match['params'];
            require $this->viewPath . DIRECTORY_SEPARATOR . $match['target'] . '.php';
            $content = ob_get_clean();
            //dd($content);
            require $this->viewPath . DIRECTORY_SEPARATOR . 'layout/default.php';
            exit();
        } else {
            // no route was matched
            header($_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
            exit();
        }
    }
}
