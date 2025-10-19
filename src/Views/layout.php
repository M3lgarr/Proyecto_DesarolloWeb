<?php $title = $title ?? 'Torneo Basket'; ?>
<!doctype html>
<html lang="es">


<head>
    <meta charset="utf-8">
    <title><?= htmlspecialchars($title) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
     <!-- Bootstrap core -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Estilos globales del layout -->
    <link rel="stylesheet" href="/assets/css/layout.css">
</head>


    <body>

        <header class="app-header">
            <nav class="navbar navbar-expand-lg shadow-sm bg-white-opaque">
                <div class="container app-container">
                <a class="navbar-brand d-flex align-items-center gap-2" href="/ui">
                    <span class="brand-logo d-inline-flex align-items-center justify-content-center">
                    <i class="bi bi-trophy-fill"></i>
                    </span>
                    <strong>Torneo Basket</strong>
                </a>

                <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div id="nav" class="collapse navbar-collapse">
                    <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                    <li class="nav-item">
                        <a class="nav-link <?= ($_SERVER['REQUEST_URI'] ?? '') === '/ui' ? 'active' : '' ?>" href="/ui">
                        <i class="bi bi-house-door me-1"></i> Inicio
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= str_starts_with($_SERVER['REQUEST_URI'] ?? '', '/ui/equipos') ? 'active' : '' ?>" href="/ui/equipos">
                        <i class="bi bi-people me-1"></i> Equipos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= str_starts_with($_SERVER['REQUEST_URI'] ?? '', '/ui/jugadores') ? 'active' : '' ?>" href="/ui/jugadores">
                        <i class="bi bi-person-badge me-1"></i> Jugadores
                        </a>
                    </li>
                    </ul>
                </div>
                </div>
            </nav>
        </header>

        <main class="app-main">
            <div class="container app-container py-4">
                <?= $content ?? '' ?>
            </div>
        </main>

        <footer class="app-footer">
            <div class="container app-container py-4 text-center text-muted small">
                <div class="d-flex justify-content-center align-items-center gap-2">
                    <i class="bi bi-lightning-charge-fill"></i>
                    <span>Slim + Bootstrap • <?= date('Y') ?></span>
                </div>
            </div>
        </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <?php if (isset($_GET['ok'])): ?>
        <?php
        $type = $_GET['type'] ?? 'default';
        $msg  = htmlspecialchars($_GET['ok']);
        $icon = 'success';
        $bg = '#3B82F6'; // azul por defecto (crear)

            if ($type === 'edit')   $bg = '#10B981'; // 🟩 verde suave (editar)
            if ($type === 'delete') $bg = '#EF4444'; // 🟥 rojo minimalista (eliminar)
                ?>
                    <script>
                        Swal.fire({
                        icon: 'success',
                        title: '<?= $msg ?>',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2500,
                        timerProgressBar: true,
                        background: '<?= $bg ?>',
                        color: '#fff',
                        iconColor: '#fff'
                        });
                    </script>
                <?php endif; ?>

                <?php if (isset($_GET['err'])): ?>
                    <script>
                        Swal.fire({
                        icon: 'error',
                        title: '<?= htmlspecialchars($_GET['err']) ?>',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        background: '#B91C1C',
                        color: '#fff',
                        iconColor: '#fff',
                        });
                    </script>
                <?php endif; ?>

    </body>
</html>
