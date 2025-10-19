<?php

namespace App\Utils;

use Psr\Http\Message\ResponseInterface as Response;

class View{
    public static function render(Response $response, string $view, array $data = []): Response {
        extract($data);
        $viewsDir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'views';

        $viewFile = $viewsDir . DIRECTORY_SEPARATOR . $view . '.php';
        if(is_file($viewFile)){
            ob_start();
            include $viewFile;
             $content = ob_get_clean();
        }else{
            echo  "<div class='container py-5'><h3>Vista no encontrada: {$view}</h3></div>";
        }
        

        $layoutFile = $viewsDir . DIRECTORY_SEPARATOR . 'layout.php';
        if (is_file($layoutFile)) {
            ob_start();
            include $layoutFile;
            $html = ob_get_clean();
        } else {
            // Si no hay layout, devolvemos solo el contenido de la vista
            $html = $content;
        }

        $response->getBody()->write($html);
        return $response->withHeader('Content-Type', 'text/html; charset=utf-8');
    } 

}