<?php 


declare(strict_types=1);

namespace App\Repositories;

use PDO;

class PartidoRepository{

    public function __construct(private PDO $pdo){}

    public function all(): array{
        $sql = "SELECT p.*, 
                       el.nombre_equipo AS local_nombre,
                       ev.nombre_equipo AS visitante_nombre
                FROM partidos p
                JOIN equipos el ON el.id_equipo = p.id_local
                JOIN equipos ev ON ev.id_equipo = p.id_visitante
                ORDER BY COALESCE(p.fecha_hora, '2999-12-31') ASC, p.id_partido DESC";
        return $this->pdo->query($sql)->fetchAll();
    }

    public function find(int $id): ?array{
        $stmt = $this->pdo->prepare("SELECT * FROM partidos WHERE id_partido = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch() ?: null;
    }
    

    public function create(array $data): int{
        $stmt = $this->pdo->prepare("
            INSERT INTO partidos (id_local, id_visitante, fecha_hora, sede, jornada, estado, pts_local, pts_visitante)
            VALUES (:l, :v, :fh, :sede, :jor, :est, :pl, :pv)
            ");

        $stmt->execute([
            ':l'    => $data['id_local'],
            ':v'    => $data['id_visitante'],
            ':fh'   => $data['fecha_hora'] ?: null,
            ':sede' => $data['sede']?? null,
            ':jor'  => $data['jornada']?? null,
            ':est'  => $data['estado']?? 'pendiente',
            ':pl'   => $data['pts_local'] ?? null,
            ':pv'   => $data['pts_visitante'] ?? null,
        ]);
        return (int)$this->pdo->lastInsertId();
    }


    public function update(int $id, array $data): void{
        $stmt = $this->pdo->prepare("
        UPDATE partidos
            SET id_local = :l,
                id_visitante = :v,
                fecha_hora = :fh,
                sede = :sede,
                jornada = :jor,
                estado = :est,
                pts_local = :pl,
                pts_visitante = :pv
            WHERE id_partido = :id
         ");

        $stmt->execute([
            ':l'    => $data['id_local'],
            ':v'    => $data['id_visitante'],
            ':fh'   => $data['fecha_hora'] ?: null,
            ':sede' => $data['sede']?? null,
            ':jor'  => $data['jornada']?? null,
            ':est'  => $data['estado']?? 'pendiente',
            ':pl'   => $data['pts_local'] ?? null,
            ':pv'   => $data['pts_visitante'] ?? null,
            ':id'   => $id,
        ]);
    }

    public function delete(int $id): void{
        $stmt = $this->pdo->prepare("DELETE FROM partidos WHERE id_partido = :id");
        $stmt->execute([':id' => $id]);
    }
}

