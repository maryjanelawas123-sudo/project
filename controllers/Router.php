<?php
class Router {
    public function route($url) {
        $urlParts = explode('/', trim($url, '/'));
        
        $controller = $urlParts[0] ?? 'home';
        $action = $urlParts[1] ?? 'index';
        $params = array_slice($urlParts, 2);
        
        $controllerFile = __DIR__ . '/' . ucfirst($controller) . 'Controller.php';
        
        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            $controllerClass = ucfirst($controller) . 'Controller';
            
            if (class_exists($controllerClass)) {
                $controllerInstance = new $controllerClass();
                
                if (method_exists($controllerInstance, $action)) {
                    call_user_func_array([$controllerInstance, $action], $params);
                } else {
                    $this->show404();
                }
            } else {
                $this->show404();
            }
        } else {
            $this->show404();
        }
    }
    
    private function show404() {
        http_response_code(404);
        require_once __DIR__ . '/../views/errors/404.php';
        exit();
    }
}
?>