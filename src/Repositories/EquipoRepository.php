<?php
    namespace App\Repositories;

    use PDO;

class EquipoRepository {
        public function __construct(private PDO $pdo) {}

        public function all(): array {
            return $this->pdo->query("SELECT * FROM equipos ORDER BY nombre_equipo ")->fetchAll();
        }

        public function find(int $id): ?array {
            $stmt = $this->pdo->prepare("SELECT * FROM equipos WHERE id_equipo = :id");
            $stmt->execute([':id' => $id]);
            return $stmt->fetch() ?: null;
        }

        public function create(string $nombre): int {
            $stmt = $this->pdo->prepare("INSERT INTO equipos (nombre_equipo) VALUES (:n)");
            $stmt->execute([':n' => $nombre]);
            return (int)$this->pdo->lastInsertId();
        }

        public function update(int $id, string $nombre): void{
            $stmt = $this->pdo->prepare("UPDATE equipos SET nombre_equipo = :n WHERE id_equipo = :id");
            $stmt->execute([':n' => $nombre, ':id' => $id]);
        }

        public function delete(int $id): void {
            $stmt = $this->pdo->prepare("DELETE FROM equipos WHERE id_equipo = :id");
            $stmt->execute([':id' => $id]);
        }
    }
