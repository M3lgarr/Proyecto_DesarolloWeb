<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\PartidoRepository;
use App\Repositories\EquipoRepository;
use App\Utils\View;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class PartidoController{

    public function __construct(
        private PartidoRepository $repo,
        private EquipoRepository $equipos
    ){}

    public function uiIndex(Request $req, Response $res): Response{
        $partidos = $this->repo->all();
        $equipos = $this->equipos->all();

        $editId =(int)($req->getQueryParams()['edit'] ?? 0);
        $editing = $editId > 0 ? $this->repo->find($editId) : null;

        return  View::render($res, 'partidos_index', compact('partidos', 'equipos', 'editing'));
    }

    public function uiStore(Request $req, Response $res): Response{
        $d = (array)$req->getParsedBody();

        $idLocal = (int)($d['id_local'] ?? 0);
        $idVis = (int)($d['id_visitante'] ?? 0);

        if($idLocal <= 0 || $idVis <= 0 || $idLocal === $idVis){
            return $res->withHeader('Location', '/ui/partidos?err=' . urlencode('Equipos inválidos'))->withStatus(302);
        }

        $fh = trim($d['fecha_hora'] ?? '');
        $fechaSql = $fh ? str_replace('T', ' ', $fh) . ':00' : null;

        $this->repo->create([
            'id_local'     => $idLocal,
            'id_visitante' => $idVis,
            'fecha_hora'   => $fechaSql,
            'sede'         => trim($d['sede'] ?? ''),
            'jornada'      => (int)($d['jornada'] ?? ''),
            'estado'       => 'pendiente',
            'pts_local'    => null,
            'pts_visitante'=> null,
        ]);
            return $res->withHeader('Location', '/ui/partidos?ok=' . urlencode('Partido creado') . '&type=create')->withStatus(302);
    }


    public function uiUpdate(Request $req, Response $res, array $args): Response{
        $id = (int)$args['id'];
        $d = (array)$req->getParsedBody();

        $idLocal = (int)($d['id_local'] ?? 0);
        $idVis = (int)($d['id_visitante'] ?? 0);

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
            'jornada'       => trim($d['jornada'] ?? ''),
            'estado'        => $estado,
            'pts_local'     => $pl,
            'pts_visitante' => $pv,
        ]);
            return $res->withHeader('Location', '/ui/partidos?ok=' . urlencode('Partido actualizado') . '&type=edit')->withStatus(302);
    }


    public function uiDelete(Request $req, Response $res, array $args): Response
    {
        $id = (int)$args['id'];
        $this->repo->delete($id);
        return $res->withHeader('Location', '/ui/partidos?ok=' . urlencode('Partido eliminado') . '&type=delete')->withStatus(302);
    }
}