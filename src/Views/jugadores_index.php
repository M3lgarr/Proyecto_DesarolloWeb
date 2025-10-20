<?php $title = 'Jugadores'; ?>
<div class="container">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h3 class="mb-0">JUGADORES</h3>
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
                <th>Foto</th>
                <th>Nombre</th>
                <th>Equipo</th>
                <th>Nacimiento</th>
                <th style="width:170px">Acciones</th>
              </tr>
            </thead>
            <tbody>
            <?php foreach ($jugadores as $i => $j): ?>
              <tr>
                <td><?= $i+1 ?></td>
                <td style="width:64px">
                  <?php if (!empty($j['foto'])): ?>
                    <img src="/<?= htmlspecialchars($j['foto']) ?>" alt="foto"
                         style="width:48px;height:48px;object-fit:cover;border-radius:50%;">
                  <?php else: ?>
                    <span class="text-muted">—</span>
                  <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($j['apellidos'] . ', ' . $j['nombres']) ?></td>
                <td><?= htmlspecialchars($j['nombre_equipo']) ?></td>
                <td><small class="text-muted"><?= htmlspecialchars($j['fecha_nacimiento'] ?? '—') ?></small></td>
                <td>
                  <a class="btn btn-sm btn-outline-primary" href="/ui/jugadores?edit=<?= (int)$j['id_jugador'] ?>">Editar</a>
                  <form action="/ui/jugadores/<?= (int)$j['id_jugador'] ?>/delete" method="POST"
                        style="display:inline-block" onsubmit="return confirm('¿Eliminar este jugador?');">
                    <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
            <?php if (empty($jugadores)): ?>
              <tr><td colspan="6" class="text-center text-muted">Sin registros</td></tr>
            <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- FORM (crear/editar) -->
    <div class="col-lg-5 mb-4">
      <?php
        $isEdit   = isset($editing) && $editing;
        $cardCls  = 'card p-3' . ($isEdit ? ' border-warning' : '');
        $titleCls = 'mb-3' . ($isEdit ? ' text-warning' : '');
        $inputCls = $isEdit ? ' border-warning' : '';
      ?>
      <div class="<?= $cardCls ?>">
        <h5 class="<?= $titleCls ?>">
          <?= $isEdit ? '✏️ EDITAR JUGADOR' : '➕ NUEVO JUGADOR' ?>
        </h5>

        <?php if ($isEdit): ?>
          <form method="POST" action="/ui/jugadores/<?= (int)$editing['id_jugador'] ?>/update" enctype="multipart/form-data">
            <div class="mb-3">
              <label class="form-label">Nombres</label>
              <input type="text" name="nombres" class="form-control<?= $inputCls ?>" value="<?= htmlspecialchars($editing['nombres']) ?>" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Apellidos</label>
              <input type="text" name="apellidos" class="form-control<?= $inputCls ?>" value="<?= htmlspecialchars($editing['apellidos']) ?>" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Fecha de nacimiento</label>
              <input type="date" name="fecha_nacimiento" class="form-control<?= $inputCls ?>" value="<?= htmlspecialchars($editing['fecha_nacimiento'] ?? '') ?>">
            </div>
            <div class="mb-3">
              <label class="form-label">Equipo</label>
              <select name="id_equipo" class="form-select<?= $inputCls ?>" required>
                <?php foreach ($equipos as $e): ?>
                  <option value="<?= (int)$e['id_equipo'] ?>" <?= ($editing['id_equipo']==$e['id_equipo'])?'selected':'' ?>>
                    <?= htmlspecialchars($e['nombre_equipo']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Foto (opcional; reemplaza la actual)</label>
              <input type="file" name="foto" class="form-control<?= $inputCls ?>" accept="image/*">
            </div>
            <div class="d-flex gap-2">
              <button class="btn btn-warning text-white">Actualizar</button>
              <a href="/ui/jugadores" class="btn btn-secondary">Cancelar</a>
            </div>
          </form>
        <?php else: ?>
          <form method="POST" action="/ui/jugadores" enctype="multipart/form-data">
            <div class="mb-3">
              <label class="form-label">Nombres</label>
              <input type="text" name="nombres" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Apellidos</label>
              <input type="text" name="apellidos" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Fecha de nacimiento</label>
              <input type="date" name="fecha_nacimiento" class="form-control">
            </div>
            <div class="mb-3">
              <label class="form-label">Equipo</label>
              <select name="id_equipo" class="form-select" required>
                <option value="">Selecciona equipo…</option>
                <?php foreach ($equipos as $e): ?>
                  <option value="<?= (int)$e['id_equipo'] ?>"><?= htmlspecialchars($e['nombre_equipo']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Foto (opcional)</label>
              <input type="file" name="foto" class="form-control" accept="image/*">
            </div>
            <button class="btn btn-primary">Guardar</button>
          </form>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
