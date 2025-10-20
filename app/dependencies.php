<?php

declare(strict_types=1);

use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use App\Repositories\EquipoRepository;
use APP\Repositories\PartidoRepository;


return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        LoggerInterface::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);

            $loggerSettings = $settings->get('logger');
            $logger = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $logger->pushHandler($handler);
            return $logger;
        },


         PDO::class => function (ContainerInterface $c) {
            $host = $_ENV['DB_HOST'] ?? '127.0.0.1';
            $db   = $_ENV['DB_DATABASE'] ?? 'torneo_basket';
            $user = $_ENV['DB_USERNAME'] ?? 'root';
            $pass = $_ENV['DB_PASSWORD'] ?? '';
            $port = (int)($_ENV['DB_PORT'] ?? 3306);

            $dsn = "mysql:host={$host};port={$port};dbname={$db};charset=utf8mb4";

            $pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);

            return $pdo;
        },

        EquipoRepository::class => function (ContainerInterface $c) {
            $pdo = $c->get(PDO::class); // ya lo definiste antes
                return new EquipoRepository($pdo);
        },
        
        JugadorRepository::class => function (ContainerInterface $c) {
            $pdo = $c->get(PDO::class); // ya lo definiste antes
                return new JugadorRepository($pdo);
        },

        PartidoRepository::class => function (Psr\Container\ContainerInterface $c) {
            $pdo = $c->get(PDO::class); // ya lo definiste antes
                return new PartidoRepository($pdo);
        },

        TablaRepository::class => function (\Psr\Container\ContainerInterface $c) {
            $pdo = $c->get(PDO::class);
                return new TablaRepository($pdo);
        },

    ]);


};
