<?php

declare(strict_types=1);



use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;


// ARCHIVOS DE APIS
use App\Controllers\EquipoController;
use App\Controllers\JugadorController;
use App\Controllers\TablaController;
use App\Controllers\PartidoController;

use App\Utils\View;





return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('Hello world!');
        return $response;
    });

    $app->group('/users', function (Group $group) {
        $group->get('', ListUsersAction::class);
        $group->get('/{id}', ViewUserAction::class);
    });

    $app->get('/health', function (Request $request, Response $response) {
        $payload = ['status' => 'ok', 'env' => $_ENV['APP_ENV'] ?? 'prod'];
        $response->getBody()->write(json_encode($payload, JSON_UNESCAPED_UNICODE));
            return $response->withHeader('Content-Type', 'application/json');
    });

    $app->get('/db/ping', function (Request $request, Response $response) {
    /** @var PDO $pdo */
        $pdo = $this->get(PDO::class);
        $pdo->query('SELECT 1');
        $response->getBody()->write(json_encode(['db' => 'connected']));
            return $response->withHeader('Content-Type', 'application/json');
    });

    $app->get('/ui', function (Request $req, Response $res) {
        return View::render($res, 'home', []);
    });


    /* ===== API: Equipos ===== */
    $app->group('/api/equipos', function (Group $group) {
        $group->get('',  [EquipoController::class, 'apiIndex']);
        $group->post('', [EquipoController::class, 'apiStore']);
    });

    /* ===== UI: Equipos ===== */
    $app->group('/ui/equipos', function (Group $group) {
        $group->get('',               [EquipoController::class, 'uiIndex']);
        $group->post('',              [EquipoController::class, 'uiStore']);
        $group->post('/{id}/update',  [EquipoController::class, 'uiUpdate']);
        $group->post('/{id}/delete',  [EquipoController::class, 'uiDelete']);
    });

    /* ===== API: Jugadores  ===== */
    $app->group('/api/jugadores', function (Group $group) {
        $group->get('',  [JugadorController::class, 'apiIndex']);
        $group->post('', [JugadorController::class, 'apiStore']);
    });

    /* ===== UI: Equipos ===== */
    $app->group('/ui/jugadores', function (Group $group) {
        $group->get('',               [JugadorController::class, 'uiIndex']);
        $group->post('',              [JugadorController::class, 'uiStore']);
        $group->post('/{id}/update',  [JugadorController::class, 'uiUpdate']);
        $group->post('/{id}/delete',  [JugadorController::class, 'uiDelete']);
    });

   /* ===== UI: Partidos ===== */
    $app->group('/ui/partidos', function (Group $group) {
        $group->get('',               [PartidoController::class, 'uiIndex']);    // GET  /ui/partidos
        $group->post('',              [PartidoController::class, 'uiStore']);    // POST /ui/partidos
        $group->post('/{id}/update',  [PartidoController::class, 'uiUpdate']);   // POST /ui/partidos/{id}/update
        $group->post('/{id}/delete',  [PartidoController::class, 'uiDelete']);   // POST /ui/partidos/{id}/delete
    });

    $app->group('/ui/tabla', function (Group $group){
        $group->get('',[TablaController::class,'uiIndex']);
    });


};
