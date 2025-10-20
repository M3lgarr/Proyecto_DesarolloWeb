<?php
declare(strict_types=1);

namespace App\Repositories;

use PDO;

class TablaRepository{
    public function __construct(private PDO $pdo){}
        public function posiciones(): array{
            $sql = "SELECT * FROM vw_posiciones
                ORDER BY PTS DESC, DIF DESC, PF DESC, nombre_equipo ASC";
            return $this->pdo->query($sql)->fetchAll();
        }
    }
