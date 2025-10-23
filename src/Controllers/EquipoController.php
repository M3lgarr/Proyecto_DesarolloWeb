<?php
namespace App\Controllers;

use App\Repositories\EquipoRepository;
use App\utils\View; // si cambias a App\Utils\View, ajusta aquí
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class EquipoController
{
    public function __construct(private EquipoRepository $repo) {}

    /* ===== API ===== */

    public function apiIndex(Request $request, Response $response): Response {
        $rows = $this->repo->all();
        $response->getBody()->write(json_encode($rows));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function apiStore(Request $request, Response $response): Response {
        $data = (array)$request->getParsedBody();
        $nombre = trim($data['nombre_equipo'] ?? '');

        if ($nombre === '') {
            $response->getBody()->write(json_encode(['error' => 'El nombre es obligatorio']));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        try {
            $id = $this->repo->create($nombre);
            $response->getBody()->write(json_encode(['message' => 'Equipo creado', 'id' => $id]));
            return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
        } catch (\PDOException $e) {
            // UNIQUE constraint
            if ($e->getCode() === '23000') {
                $response->getBody()->write(json_encode(['error' => 'El nombre ya existe']));
                return $response->withStatus(409)->withHeader('Content-Type', 'application/json');
            }
            throw $e;
        }
    }

    /* ===== UI ===== */

    // GET /ui/equipos (+ ?edit=ID)
    public function uiIndex(Request $req, Response $res): Response {
        $equipos = $this->repo->all();

        $editId = (int)($req->getQueryParams()['edit'] ?? 0);
        $editing = $editId > 0 ? $this->repo->find($editId) : null;

        $flash_success = $req->getQueryParams()['ok'] ?? null;
        $flash_error   = $req->getQueryParams()['err'] ?? null;

        return View::render($res, 'equipos_index', compact('equipos','flash_success','flash_error','editing'));
    }

    // POST /ui/equipos (crear)
    public function uiStore(Request $req, Response $res): Response {
        $data = (array)$req->getParsedBody();
        $nombre = trim($data['nombre_equipo'] ?? '');

        try {
            if ($nombre === '') throw new \RuntimeException('El nombre es obligatorio.');
            $this->repo->create($nombre);
           return $res->withHeader('Location', '/ui/equipos?ok=' . urlencode('Equipo creado') . '&type=create')
                       ->withStatus(302);
                       
        } catch (\PDOException $e) {
            $msg = ($e->getCode() === '23000') ? 'El nombre ya existe' : $e->getMessage();
            return $res->withHeader('Location', '/ui/equipos?err=' . urlencode('Error: ' . $msg))
                       ->withStatus(302);

        } catch (\Throwable $e) {
            return $res->withHeader('Location', '/ui/equipos?err=' . urlencode('Error: ' . $e->getMessage()))
                       ->withStatus(302);
        }
    }

    // POST /ui/equipos/{id}/update
    public function uiUpdate(Request $req, Response $res, array $args): Response {
        $id = (int)$args['id'];
        $data = (array)$req->getParsedBody();
        $nombre = trim($data['nombre_equipo'] ?? '');

        try {
            if ($id <= 0) throw new \RuntimeException('ID inválido.');
            if ($nombre === '') throw new \RuntimeException('El nombre es obligatorio.');
            $this->repo->update($id, $nombre);
            return $res->withHeader('Location', '/ui/equipos?ok=' . urlencode('Equipo actualizado') . '&type=edit')
                       ->withStatus(302);


        } catch (\PDOException $e) {
            $msg = ($e->getCode() === '23000') ? 'El nombre ya existe' : $e->getMessage();
            return $res->withHeader('Location', '/ui/equipos?err=' . urlencode('Error: ' . $msg))
                       ->withStatus(302);
        } catch (\Throwable $e) {
            return $res->withHeader('Location', '/ui/equipos?err=' . urlencode('Error: ' . $e->getMessage()))
                       ->withStatus(302);
        }
    }

    // POST /ui/equipos/{id}/delete
   public function uiDelete(Request $req, Response $res, array $args): Response {
    $id = (int)$args['id'];
    try {
        if ($id <= 0) throw new \RuntimeException('ID inválido.');

        if ($this->repo->hasPartidos($id)) {
            return $res->withHeader(
                'Location',
                '/ui/equipos?err=' . urlencode('No se puede eliminar: el equipo tiene partidos asociados.')
            )->withStatus(302);
        }

        $this->repo->delete($id);
        return $res->withHeader(
            'Location',
            '/ui/equipos?ok=' . urlencode('Equipo eliminado') . '&type=delete'
        )->withStatus(302);

    } catch (\Throwable $e) {
        return $res->withHeader(
            'Location',
            '/ui/equipos?err=' . urlencode('Error: ' . $e->getMessage())
        )->withStatus(302);
    }
}

}
