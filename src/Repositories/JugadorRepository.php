<?php
declare(strict_types=1);

namespace App\Repositories;

use PDO;

class JugadorRepository{
    public function __construct(private PDO $pdo){}

    public function all(): array{
        $sql = "SELECT j.id_jugador, j.nombres, j.apellidos, j.fecha_nacimiento, j.foto,
                       e.nombre_equipo, e.id_equipo
                FROM jugadores j
                JOIN equipos e ON e.id_equipo = j.id_equipo
                ORDER BY e.nombre_equipo, j.apellidos, j.nombres";
        return $this->pdo->query($sql)->fetchAll();
    }

    public function find(int $id): ?array{
        $stmt = $this->pdo->prepare("SELECT * FROM jugadores WHERE id_jugador = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): int{
        $stmt = $this->pdo->prepare("
            INSERT INTO jugadores (nombres, apellidos, fecha_nacimiento, foto, id_equipo)
            VALUES (:n, :a, :f, :foto, :e)
        ");
        $stmt->execute([
            ':n'   => $data['nombres'],
            ':a'   => $data['apellidos'],
            ':f'   => $data['fecha_nacimiento'] ?: null,
            ':foto'=> $data['foto'] ?? null,
            ':e'   => $data['id_equipo'],
           
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): void{
        $stmt = $this->pdo->prepare("
            UPDATE jugadores
                SET nombres = :n, apellidos = :a, fecha_nacimiento = :f, id_equipo = :e, foto = :foto
                WHERE id_jugador = :id

        ");
        $stmt->execute([
            ':n'   => $data['nombres'],
            ':a'   => $data['apellidos'],
            ':f'   => $data['fecha_nacimiento'] ?: null,
            ':e'   => $data['id_equipo'],
            ':foto'=> $data['foto'] ?? null,
            ':id'  => $id,
        ]);
    }

    public function delete(int $id): void{
        $stmt = $this->pdo->prepare("DELETE  FROM jugadores WHERE id_jugador = :id");
        $stmt->execute([':id' => $id]);
    }

    public function getFotoPath(int $id): ?string{
        $stmt = $this->pdo->prepare(" SELECT foto FROM jugadores WHERE id_jugador =:id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ? $row['foto'] : null;
    }

}