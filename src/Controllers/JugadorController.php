<?php
declare(strict_types=1);

namespace App\Controllers;


use App\Repositories\JugadorRepository;
use App\Repositories\EquipoRepository;
use App\Utils\View;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class JugadorController {
    public function __construct(
        private JugadorRepository $repo,
        private EquipoRepository $equiposRepo
    ) {}




     /* ===== API ===== */

     public function apiIndex(Request $request, Response $response): Response {
        $rows = $this->repo->all();
        $response->getBpdy()->wtite(json_encode($rows));
        return $response->withHeader('Content-Type', 'application/json');

     }



     public function apiStore(Request $request, Response $response): Response {
        $data =(array)$request->getParsedBody();
        $nombres = trim($data['nombres'] ?? '');
        $apellidos = trim($data['apellidos'] ?? '');
        $idEquipo = (int)($data['id_equipo'] ?? 0);

        if ($nombres === '' || $apellidos === '' || $idEquipo <= 0) {
            $response->getBody()->write(json_encode(['error' => 'Campos requeridos: nombres, apellidos, id_equipo']));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        $id = $this->repo->create([
            'nombres'=>$nombres, 'apellidoos'=>$apellidos,
            'fecha_nacimiento'=>$data['fecha_nacimiento'] ?? null,
            'foto'=>null, 'id_equipo'=>$idEquipo,
        ]);

        $response->getBody()->write(json_encode(['message'=>'Jugador creado', 'id'=>$id]));
        return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
     }



      /* ===== UI ===== */

      public function uiIndex(Request $req, Response $res): Response {
        $jugadores = $this->repo->all();
        $equipos   = $this->equiposRepo->all();

        $editId  = (int)($req->getQueryParams()['edit'] ?? 0);
        $editing = $editId > 0 ? $this->repo->find($editId) : null;

        $flash_success = $req->getQueryParams()['ok'] ?? null;
        $flash_error   = $req->getQueryParams()['err'] ?? null;

        return View::render($res, 'jugadores_index', compact('jugadores', 'equipos', 'editing', 'flash_success', 'flash_error'));
    }



    public function uiStore(Request $req, Response $res): Response {
        $data = $req->getParsedBody();
        $files = $req->getUploadedFiles();
        $nombres   = trim($data['nombres'] ?? '');
        $apellidos = trim($data['apellidos'] ?? '');
        $idEquipo  = (int)($data['id_equipo'] ?? 0);

        try {
            if ($nombres === '' || $apellidos === '' || $idEquipo <= 0) {
                throw new \RuntimeException('Campos requeridos: nombres, apellidos y equipo.');
            }

            $fotoPath = $this->handleUpload($files['foto'] ?? null, null);
            $this->repo->create([
                'nombres'=>$nombres,
                'apellidos'=>$apellidos,
                'fecha_nacimiento'=>$data['fecha_nacimiento'] ?? null,
                'foto'=>$fotoPath,
                'id_equipo'=>$idEquipo
            ]);

            return $res->withHeader('Location', '/ui/jugadores?ok=' . urlencode('Jugador creado') . '&type=create')
                       ->withStatus(302);

        } catch (\Throwable $e) {
           return $res->withHeader('Location', '/ui/jugadores?err=' . urlencode('Error: ' . $e->getMessage()))
                       ->withStatus(302);
        }
    }

    public function uiUpdate(Request $req, Response $res, array $args): Response {
        $id = (int)$args['id'];
        $data  = $req->getParsedBody();
        $files = $req->getUploadedFiles();

        try {
            if ($id <= 0) throw new \RuntimeException('ID inválido.');
            $nombres   = trim($data['nombres'] ?? '');
            $apellidos = trim($data['apellidos'] ?? '');
            $idEquipo  = (int)($data['id_equipo'] ?? 0);
            if ($nombres === '' || $apellidos === '' || $idEquipo <= 0) {
                throw new \RuntimeException('Campos requeridos: nombres, apellidos y equipo.');
            }

            $fotoActual = $this->repo->getFotoPath($id);
            $fotoPath   = $this->handleUpload(($files['foto'] ?? null), $fotoActual);
            $this->repo->update($id, [
                'nombres'=>$nombres,
                'apellidos'=>$apellidos,
                'fecha_nacimiento'=>$data['fecha_nacimiento'] ?? null,
                'foto'=>$fotoPath,
                'id_equipo'=>$idEquipo
            ]);

            return $res->withHeader('Location', '/ui/jugadores?ok=' . urlencode('Jugador actualizado') . '&type=edit')
                       ->withStatus(302);

        } catch (\Throwable $e) {
            return $res->withHeader('Location', '/ui/jugadores?err=' . urlencode('Error: ' . $e->getMessage()))
                       ->withStatus(302);
        }
    }



    public function uiDelete(Request $req, Response $res, array $args): Response {
        $id = (int)$args['id'];

        try {
            if ($id <= 0) throw new \RuntimeException('ID inválido.');
            $foto = $this->repo->getFotoPath($id);
            $this->repo->delete($id);


            if (!empty($foto)) {
                $abs = dirname(__DIR__, 2) . '/public/' . $foto;
                if (is_file($abs)) @unlink($abs);
            }

            return $res->withHeader('Location', '/ui/jugadores?ok=' . urlencode('Jugador eliminado') . '&type=delete')
                       ->withStatus(302);

        } catch (\Throwable $e) {
            return $res->withHeader('Location', '/ui/jugadores?err=' . urlencode('Error: ' . $e->getMessage()))
                       ->withStatus(302);
        }
    }


    private function handleUpload($uploadedFile, ?string $oldPath): ?string {
        if (!$uploadedFile || $uploadedFile->getError() !== UPLOAD_ERR_OK) {
            return $oldPath; // sin cambios
        }

        $ext = strtolower(pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','gif','webp'];

        if (!in_array($ext, $allowed, true)) {
            throw new \RuntimeException('Formato de imagen no permitido.');
        }

        if ($uploadedFile->getSize() > 2 * 1024 * 1024) {
            throw new \RuntimeException('La imagen supera 2MB.');
        }

        $uploadDir = dirname(__DIR__, 2) . '/public/uploads/jugadores';

        if (!is_dir($uploadDir)) @mkdir($uploadDir, 0777, true);

            $safeName = uniqid('jug_', true) . '.' . $ext;
            $uploadedFile->moveTo($uploadDir . DIRECTORY_SEPARATOR . $safeName);
            $newPublicPath = 'uploads/jugadores/' . $safeName;

       
        if (!empty($oldPath)) {
            $oldAbs = dirname(__DIR__, 2) . '/public/' . $oldPath;
            if (is_file($oldAbs)) @unlink($oldAbs);
        }

        return $newPublicPath;
    }
}