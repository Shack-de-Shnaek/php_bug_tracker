<?php 
class Router {
    private $routes = [];
    private $base = "";
    private $isTopLevel = false;

    public function __construct(string $base, bool $isTopLevel = false) {
        if($base !== '/') $this->base = $base;
        $this->isTopLevel = $isTopLevel;
    }

    public function add(string $route, callable $callback) {
        $this->routes[$this->base . $route] = $callback;
    }

    public function run() {
        foreach($this->routes as $route => $callback) {
            if(strpos(strtok($_SERVER['REQUEST_URI'], '?'), $route) === 0) {
                $callback();
                return;
            }
        }
        if($this->isTopLevel) {
            if(strpos(strtok($_SERVER['REQUEST_URI'], '?'), $this->base) === 0) {
                http_response_code(404);
            }
            else {
                echo file_get_contents('/xampp/htdocs/app.html');
            }
        }
        else http_response_code(404);
    }
}