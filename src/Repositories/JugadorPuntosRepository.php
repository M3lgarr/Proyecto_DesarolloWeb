<?php
declare(strict_types=1);

namespace App\Repositories;

use PDO;

class JugadorPuntosRepository {
    public function __construct(private PDO $pdo) {}

    public function create(array $data): int {
        $sql = "INSERT INTO jugador_puntos (id_partido, id_jugador, jornada, tipo_tiro, cantidad)
                VALUES (:p, :j, :jor, :tipo, :cant)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':p'    => $data['id_partido'],
            ':j'    => $data['id_jugador'],
            ':jor'  => $data['jornada'],
            ':tipo' => $data['tipo_tiro'],
            ':cant' => $data['cantidad'],
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    public function delete(int $id): void {
        $stmt = $this->pdo->prepare("DELETE FROM jugador_puntos WHERE id_punto = :id");
        $stmt->execute([':id' => $id]);
    }

    public function listByPartido(int $idPartido): array {
        $sql = "SELECT jp.*, j.nombres, j.apellidos
                FROM jugador_puntos jp
                JOIN jugadores j ON j.id_jugador = jp.id_jugador
                WHERE jp.id_partido = :p
                ORDER BY jp.id_punto DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':p' => $idPartido]);
        return $stmt->fetchAll();
    }
}
?>