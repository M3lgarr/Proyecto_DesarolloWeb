<?php $title = 'Equipos'; ?>
<div class="container">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h3 class="mb-0">EQUIPOS</h3>
  </div>

  <div class="row">
    <!-- LISTA -->
    <div class="col-lg-7 mb-4">
      <div class="card p-3">
        <div class="table-responsive">
          <table class="table align-middle">
            <thead>
              <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Creación</th>
                <th style="width:160px">Acciones</th>
              </tr>
            </thead>
            <tbody>
            <?php foreach ($equipos as $i => $e): ?>
              <tr>
                <td><?= $i+1 ?></td>
                <td><?= htmlspecialchars($e['nombre_equipo']) ?></td>
                <td><small class="text-muted"><?= htmlspecialchars($e['fecha_creacion']) ?></small></td>
                <td>
                  <a class="btn btn-sm btn-outline-primary"
                     href="/ui/equipos?edit=<?= (int)$e['id_equipo'] ?>">Editar</a>

                  <form action="/ui/equipos/<?= (int)$e['id_equipo'] ?>/delete"
                        method="POST" style="display:inline-block"
                        onsubmit="return confirm('¿Eliminar este equipo?');">
                    <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
            <?php if (empty($equipos)): ?>
              <tr><td colspan="4" class="text-center text-muted">Sin registros</td></tr>
            <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- FORM (crear/editar) -->
    <div class="col-lg-5 mb-4">
      <div class="card p-3 <?= isset($editing) && $editing ? 'border-warning' : '' ?>">
        <h5 class="mb-3 <?= isset($editing) && $editing ? 'text-warning' : '' ?>">
          <?= isset($editing) && $editing ? '✏️ EDITAR EQUIPO' : '➕ NUEVO EQUIPO' ?>
        </h5>

        <?php /*if (!empty($flash_success)): ?>
          <div class="alert alert-success py-2"><?= htmlspecialchars($flash_success) ?></div>
        <?php endif; ?>
        <?php if (!empty($flash_error)): ?>
          <div class="alert alert-danger py-2"><?= htmlspecialchars($flash_error) ?></div>
        <?php endif; */
        ?>

        <?php if (isset($editing) && $editing): ?>
          <!-- Formulario de edición -->
          <form method="POST" action="/ui/equipos/<?= (int)$editing['id_equipo'] ?>/update">
            <div class="mb-3">
              <label class="form-label">Nombre del equipo</label>
              <input type="text" name="nombre_equipo" class="form-control border-warning"
                     value="<?= htmlspecialchars($editing['nombre_equipo']) ?>" required>
            </div>
            <div class="d-flex gap-2">
              <button class="btn btn-warning text-white">Actualizar</button>
              <a href="/ui/equipos" class="btn btn-secondary">Cancelar</a>
            </div>
          </form>
        <?php else: ?>
          <!-- Formulario de creación -->
          <form method="POST" action="/ui/equipos">
            <div class="mb-3">
              <label class="form-label">Nombre del equipo</label>
              <input type="text" name="nombre_equipo" class="form-control" required>
            </div>
            <button class="btn btn-primary">Guardar</button>
          </form>
        <?php endif; ?>

      </div>
    </div>
  </div>
</div>
