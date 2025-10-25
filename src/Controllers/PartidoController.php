<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\PartidoRepository;
use App\Repositories\EquipoRepository;
use App\Repositories\JugadorPuntosRepository;
use App\Repositories\JugadorRepository; // 👈 IMPORTANTE
use App\Utils\View;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class PartidoController
{
    public function __construct(
        private PartidoRepository $repo,
        private EquipoRepository $equipos,
        private JugadorPuntosRepository $puntosRepo,
        private JugadorRepository $jugRepo // 👈 nombre consistente
    ) {}

    public function uiIndex(Request $req, Response $res): Response {
        $partidos = $this->repo->all();
        $equipos  = $this->equipos->all();

        $editId  = (int)($req->getQueryParams()['edit'] ?? 0);
        $editing = $editId > 0 ? $this->repo->find($editId) : null;

        return View::render($res, 'partidos_index', compact('partidos','equipos','editing'));
    }

    public function uiStore(Request $req, Response $res): Response {
        $d = (array)$req->getParsedBody();

        $idLocal = (int)($d['id_local'] ?? 0);
        $idVis   = (int)($d['id_visitante'] ?? 0);

        if ($idLocal <= 0 || $idVis <= 0 || $idLocal === $idVis) {
            return $res->withHeader('Location', '/ui/partidos?err=' . urlencode('Equipos inválidos'))->withStatus(302);
        }

        $fh = trim($d['fecha_hora'] ?? '');
        $fechaSql = $fh ? str_replace('T', ' ', $fh) . ':00' : null;

        $this->repo->create([
            'id_local'      => $idLocal,
            'id_visitante'  => $idVis,
            'fecha_hora'    => $fechaSql,
            'sede'          => trim($d['sede'] ?? ''),
            'jornada'       => trim($d['jornada'] ?? ''), // VARCHAR
            'estado'        => 'pendiente',
            'pts_local'     => null,
            'pts_visitante' => null,
        ]);

        return $res->withHeader('Location', '/ui/partidos?ok=' . urlencode('Partido creado') . '&type=create')->withStatus(302);
    }

    public function uiUpdate(Request $req, Response $res, array $args): Response {
        $id = (int)$args['id'];
        $d  = (array)$req->getParsedBody();

        $idLocal = (int)($d['id_local'] ?? 0);
        $idVis   = (int)($d['id_visitante'] ?? 0);

        if ($idLocal <= 0 || $idVis <= 0 || $idLocal === $idVis) {
            return $res->withHeader('Location', '/ui/partidos?err=' . urlencode('Equipos inválidos'))->withStatus(302);
        }

        $fh = trim($d['fecha_hora'] ?? '');
        $fechaSql = $fh ? str_replace('T', ' ', $fh) . ':00' : null;

        $estado = $d['estado'] ?? 'pendiente';
        $pl = strlen($d['pts_local'] ?? '') ? (int)$d['pts_local'] : null;
        $pv = strlen($d['pts_visitante'] ?? '') ? (int)$d['pts_visitante'] : null;

        $this->repo->update($id, [
            'id_local'      => $idLocal,
            'id_visitante'  => $idVis,
            'fecha_hora'    => $fechaSql,
            'sede'          => trim($d['sede'] ?? ''),
            'jornada'       => trim($d['jornada'] ?? ''), // VARCHAR
            'estado'        => $estado,
            'pts_local'     => $pl,
            'pts_visitante' => $pv,
        ]);

        return $res->withHeader('Location', '/ui/partidos?ok=' . urlencode('Partido actualizado') . '&type=edit')->withStatus(302);
    }

    public function uiDelete(Request $req, Response $res, array $args): Response {
        $id = (int)$args['id'];
        $this->repo->delete($id);
        return $res->withHeader('Location', '/ui/partidos?ok=' . urlencode('Partido eliminado') . '&type=delete')->withStatus(302);
    }

  // src/Controllers/PartidoController.php

    // POST /ui/partidos/{id}/puntos
    public function uiAddPunto(Request $req, Response $res, array $args): Response {
        $idPartido = (int)$args['id'];
        $d = (array)$req->getParsedBody();

        try {
            $idJugador = (int)($d['id_jugador'] ?? 0);
            $tipo      = $d['tipo_tiro'] ?? '';
            $cant      = max(1, (int)($d['cantidad'] ?? 1));
            $jornada   = $d['jornada'] ?? null;

            if ($idJugador <= 0 || !in_array($tipo, ['libre','dos','tres'], true)) {
                throw new \RuntimeException('Datos inválidos.');
            }

            $this->puntosRepo->create([
                'id_partido' => $idPartido,
                'id_jugador' => $idJugador,
                'jornada'    => $jornada,
                'tipo_tiro'  => $tipo,
                'cantidad'   => $cant,
            ]);

            return $res->withHeader(
                'Location',
                "/ui/partidos/{$idPartido}/puntos?ok=" . urlencode('Anotación agregada') . "&type=create"
            )->withStatus(302);

        } catch (\Throwable $e) {
            return $res->withHeader(
                'Location',
                "/ui/partidos/{$idPartido}/puntos?err=" . urlencode('Error: '.$e->getMessage())
            )->withStatus(302);
        }
    }

    // GET /ui/partidos/{id}/puntos
    public function uiPuntosForm(Request $req, Response $res, array $args): Response {
        $idPartido = (int)$args['id'];
        $partido   = $this->repo->find($idPartido);
        if (!$partido) {
            return $res->withHeader('Location', '/ui/partidos?err=' . urlencode('Partido no encontrado'))
                    ->withStatus(302);
        }

        $jugLocal     = $this->jugRepo->byEquipo((int)$partido['id_local']);
        $jugVisitante = $this->jugRepo->byEquipo((int)$partido['id_visitante']);
        $anotaciones  = $this->puntosRepo->listByPartido($idPartido); // 👈 ahora sí

        return View::render($res, 'partidos_puntos', compact(
            'partido','jugLocal','jugVisitante','anotaciones'
        ));
    }

    // POST /ui/partidos/puntos/{id}/delete
    public function uiDeletePunto(Request $req, Response $res, array $args): Response {
        $idPunto = (int)$args['id'];
        $d       = (array)$req->getParsedBody();                // 👈 leer desde POST
        $back    = $d['back'] ?? '/ui/partidos';

        try {
            $this->puntosRepo->delete($idPunto);
            return $res->withHeader('Location', $back.'?ok='.urlencode('Anotación eliminada').'&type=delete')
                    ->withStatus(302);
        } catch (\Throwable $e) {
            return $res->withHeader('Location', $back.'?err='.urlencode('Error: '.$e->getMessage()))
                    ->withStatus(302);
        }
    }

}
