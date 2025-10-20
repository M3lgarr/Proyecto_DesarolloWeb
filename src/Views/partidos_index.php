<?php $title = 'Partidos'; ?>
<div class="container">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h3 class="mb-0">PARTIDOS</h3>
  </div>

  <div class="row g-4">
    <!-- LISTA -->
    <div class="col-lg-7">
      <div class="card p-3">
        <div class="table-responsive">
          <table class="table align-middle">
            <thead>
              <tr>
                <th>#</th>
                <th>Local</th>
                <th>Visitante</th>
                <th>Fecha/Hora</th>
                <th>Marcador</th>
                <th>Estado</th>
                <th style="width:180px">Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php
                $badgeMap = ['pendiente'=>'secondary','finalizado'=>'success','suspendido'=>'warning'];
              ?>
              <?php foreach ($partidos as $i => $p): ?>
                <tr>
                  <td><?= $i+1 ?></td>
                  <td><?= htmlspecialchars($p['local_nombre']) ?></td>
                  <td><?= htmlspecialchars($p['visitante_nombre']) ?></td>
                  <td><small class="text-muted"><?= $p['fecha_hora'] ? htmlspecialchars($p['fecha_hora']) : '—' ?></small></td>
                  <td>
                    <?= (is_null($p['pts_local']) || is_null($p['pts_visitante']))
                          ? '—'
                          : (int)$p['pts_local'].' - '.(int)$p['pts_visitante'] ?>
                  </td>
                  <td>
                    <?php $estado = strtolower((string)($p['estado'] ?? 'pendiente')); ?>
                    <span class="badge bg-<?= $badgeMap[$estado] ?? 'secondary' ?>">
                      <?= htmlspecialchars($p['estado']) ?>
                    </span>
                  </td>
                  <td>
                    <a class="btn btn-sm btn-outline-primary" href="/ui/partidos?edit=<?= (int)$p['id_partido'] ?>">Editar</a>
                    <form action="/ui/partidos/<?= (int)$p['id_partido'] ?>/delete" method="POST" style="display:inline-block"
                          onsubmit="return confirm('¿Eliminar este partido?');">
                      <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
              <?php if (empty($partidos)): ?>
                <tr><td colspan="7" class="text-center text-muted">Sin registros</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- FORM (crear/editar) -->
    <div class="col-lg-5">
      <?php
        $isEdit = isset($editing) && $editing;
        $action = $isEdit ? "/ui/partidos/{$editing['id_partido']}/update" : "/ui/partidos";
        $dtVal  = $isEdit && !empty($editing['fecha_hora'])
                  ? str_replace(' ', 'T', substr($editing['fecha_hora'], 0, 16))
                  : '';
        // clases para “modo edición”
        $cardClass   = 'card p-3' . ($isEdit ? ' border-warning' : '');
        $titleClass  = 'mb-3' . ($isEdit ? ' text-warning' : '');
        $inputWarn   = $isEdit ? ' border-warning' : '';
        $btnClass    = $isEdit ? 'btn btn-warning text-white' : 'btn btn-primary';
        $btnLabel    = $isEdit ? 'Actualizar' : 'Guardar';
      ?>
      <div class="<?= $cardClass ?>">
        <h5 class="<?= $titleClass ?>">
          <?= $isEdit ? '✏️ EDITAR PARTIDO' : '➕ NUEVO PARTIDO' ?>
        </h5>

        <form method="POST" action="<?= $action ?>">
          <div class="mb-3">
            <label class="form-label">Equipo local</label>
            <select name="id_local" class="form-select<?= $inputWarn ?>" required>
              <option value="">Selecciona...</option>
              <?php foreach ($equipos as $e): ?>
                <option value="<?= (int)$e['id_equipo'] ?>"
                  <?= $isEdit && (int)$editing['id_local'] === (int)$e['id_equipo'] ? 'selected' : '' ?>>
                  <?= htmlspecialchars($e['nombre_equipo']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Equipo visitante</label>
            <select name="id_visitante" class="form-select<?= $inputWarn ?>" required>
              <option value="">Selecciona...</option>
              <?php foreach ($equipos as $e): ?>
                <option value="<?= (int)$e['id_equipo'] ?>"
                  <?= $isEdit && (int)$editing['id_visitante'] === (int)$e['id_equipo'] ? 'selected' : '' ?>>
                  <?= htmlspecialchars($e['nombre_equipo']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Fecha y hora</label>
            <input type="datetime-local" name="fecha_hora" class="form-control<?= $inputWarn ?>"
                   value="<?= htmlspecialchars($dtVal) ?>">
          </div>

          <div class="mb-3">
            <label class="form-label">Sede (opcional)</label>
            <input type="text" name="sede" class="form-control<?= $inputWarn ?>"
                   value="<?= $isEdit ? htmlspecialchars($editing['sede'] ?? '') : '' ?>">
          </div>

          <div class="mb-3">
            <label class="form-label">Jornada (opcional)</label>
            <input type="text" name="jornada" class="form-control<?= $inputWarn ?>"
                   value="<?= $isEdit ? htmlspecialchars($editing['jornada'] ?? '') : '' ?>">
          </div>

          <?php if ($isEdit): ?>
            <div class="row g-2">
              <div class="col-6">
                <label class="form-label">Puntos local</label>
                <input type="number" name="pts_local" class="form-control<?= $inputWarn ?>"
                       value="<?= htmlspecialchars((string)($editing['pts_local'] ?? '')) ?>">
              </div>
              <div class="col-6">
                <label class="form-label">Puntos visitante</label>
                <input type="number" name="pts_visitante" class="form-control<?= $inputWarn ?>"
                       value="<?= htmlspecialchars((string)($editing['pts_visitante'] ?? '')) ?>">
              </div>
            </div>

            <div class="mb-3 mt-2">
              <label class="form-label">Estado</label>
              <select name="estado" class="form-select<?= $inputWarn ?>">
                <?php
                  $est = $editing['estado'] ?? 'pendiente';
                  foreach (['pendiente'=>'Pendiente','finalizado'=>'Finalizado','suspendido'=>'Suspendido'] as $k=>$v):
                ?>
                  <option value="<?= $k ?>" <?= $est === $k ? 'selected' : '' ?>><?= $v ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          <?php endif; ?>

          <div class="d-flex gap-2">
            <button class="<?= $btnClass ?>"><?= $btnLabel ?></button>
            <?php if ($isEdit): ?>
              <a href="/ui/partidos" class="btn btn-secondary">Cancelar</a>
            <?php endif; ?>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
