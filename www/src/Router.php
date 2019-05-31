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
        ob_start();
        if (is_array($match)) {
            $params = $match['params'];
            require $this->pathToFile($match['target']);
        } else {
            // no route was matched
            header($_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
            require $this->pathToFile("layout/404");
        }
        $content = ob_get_clean();
        require $this->pathToFile("layout/default");
    }

    private function pathToFile(string $file): string
    {
        return $this->viewPath . DIRECTORY_SEPARATOR . $file . '.php';
    }
}
