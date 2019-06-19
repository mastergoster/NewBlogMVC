<?php
namespace App;

use \App\Controller\RouterController;
use \App\Controller\Database\DatabaseController;

class App {

    private static $_instance;

    public $title;

    private $router;
    private $startTime;
    private $db_instance;


    public static function getInstance()
    {
        if(is_null(self::$_instance)){
            self::$_instance = new App();
        }
        return self::$_instance;
    }

    public static function load()
    {
        if (getenv("ENV_DEV")) {
            $whoops = new \Whoops\Run;
            $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
            $whoops->register();
        }
        
        $numPage = URL::getPositiveInt('page');
        
        if ($numPage !== null) {
            // url /categories?page=1&parm2=pomme
            if ($numPage == 1) {
                $uri = explode('?', $_SERVER["REQUEST_URI"])[0];
                $get = $_GET;
                unset($get["page"]);
                $query = http_build_query($get);
                if (!empty($query)) {
                    $uri = $uri . '?' . $query;
                }
                http_response_code(301);
                header('location: ' . $uri);
                exit();
            } 
        }
    
    }

    public function getRouter($basePath = "/var/www"): RouterController
    {
        if(is_null($this->router)){
            $this->router = new RouterController($basePath . 'views');
        }
        return $this->router;
    }

    public function setStartTime()
    {
        $this->startTime = microtime(true);
    }

    public function getDebugTime()
    {
        return number_format((microtime(true) - $this->startTime) *  1000, 2);
    }

    public function getTable(string $nameTable) {
        $nameTable = "\\App\\Model\\Table\\".ucfirst($nameTable)."Table";
        return new $nameTable($this->getDb());
    }

    public function getDb(): DatabaseController
    {
        if(is_null($this->db_instance)) {
            $this->db_instance = new DatabaseController(getenv('MYSQL_DATABASE'),
                    getenv('MYSQL_USER'),
                    getenv('MYSQL_PASSWORD'),
                    getenv('MYSQL_HOST'));
        }
        return $this->db_instance;
    }
}