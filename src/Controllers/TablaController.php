<?php

declare(strict_types=1);

namespace App\Controllers;


use App\Repositories\TablaRepository;
use App\Utils\View;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class TablaController{
    public function __construct(private TablaRepository $repo){}

    public function uiIndex(Request $req, Response $res): Response{
        $tabla = $this->repo->posiciones();
        return View::render($res, 'tabla_index', compact('tabla'));
    }
}