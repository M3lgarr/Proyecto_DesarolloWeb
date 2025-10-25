<?php

declare(strict_types=1);

use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

// Repositorios
use App\Repositories\EquipoRepository;
use App\Repositories\PartidoRepository;
use App\Repositories\JugadorRepository;
use App\Repositories\JugadorPuntosRepository;
use App\Repositories\TablaRepository;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        // Logger (Monolog)
        LoggerInterface::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);
            $loggerSettings = $settings->get('logger');

            $logger = new Logger($loggerSettings['name']);
            $logger->pushProcessor(new UidProcessor());
            $logger->pushHandler(new StreamHandler(
                $loggerSettings['path'],
                $loggerSettings['level']
            ));

            return $logger;
        },

        // PDO (MySQL)
        PDO::class => function (ContainerInterface $c) {
            $host = $_ENV['DB_HOST'] ?? '127.0.0.1';
            $db   = $_ENV['DB_DATABASE'] ?? 'torneo_basket';
            $user = $_ENV['DB_USERNAME'] ?? 'root';
            $pass = $_ENV['DB_PASSWORD'] ?? '';
            $port = (int)($_ENV['DB_PORT'] ?? 3306);

            $dsn = "mysql:host={$host};port={$port};dbname={$db};charset=utf8mb4";

            return new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        },

        // Repositorios
        EquipoRepository::class         => fn (ContainerInterface $c) => new EquipoRepository($c->get(PDO::class)),
        JugadorRepository::class        => fn (ContainerInterface $c) => new JugadorRepository($c->get(PDO::class)),
        PartidoRepository::class        => fn (ContainerInterface $c) => new PartidoRepository($c->get(PDO::class)),
        JugadorPuntosRepository::class  => fn (ContainerInterface $c) => new JugadorPuntosRepository($c->get(PDO::class)),
        TablaRepository::class          => fn (ContainerInterface $c) => new TablaRepository($c->get(PDO::class)),
    ]);
};
