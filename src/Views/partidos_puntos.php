<?php $title = 'Puntuar partido'; ?>
<div class="container">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h3 class="mb-0">PUNTUAR — 
      <?= htmlspecialchars($partido['local_nombre']) ?> 
      <span class="text-muted">vs</span> 
      <?= htmlspecialchars($partido['visitante_nombre']) ?>
    </h3>
    <a class="btn btn-outline-secondary" href="/ui/partidos">Volver</a>
  </div>

  <div class="row g-4">
    <!-- Formulario nueva anotación -->
    <div class="col-lg-5">
      <div class="card p-3">
        <h5 class="mb-3"><i class="bi bi-plus-circle me-1"></i>Nueva anotación</h5>
        <form method="POST" action="/ui/partidos/<?= (int)$partido['id_partido'] ?>/puntos">
          <div class="mb-3">
            <label class="form-label">Jornada (opcional)</label>
            <input type="text" name="jornada" class="form-control" placeholder="Jornada 1"
                   value="<?= htmlspecialchars($_GET['jornada'] ?? '') ?>">
          </div>

          <div class="mb-3">
            <label class="form-label">Jugador</label>
            <select name="id_jugador" class="form-select" required>
              <option value="">Selecciona...</option>

              <optgroup label="Local — <?= htmlspecialchars($partido['local_nombre']) ?>">
                <?php foreach (($jugLocal ?? []) as $j): ?>
                  <option value="<?= (int)$j['id_jugador'] ?>">
                    <?= htmlspecialchars($j['apellidos'] . ', ' . $j['nombres']) ?>
                  </option>
                <?php endforeach; ?>
              </optgroup>

              <optgroup label="Visitante — <?= htmlspecialchars($partido['visitante_nombre']) ?>">
                <?php foreach (($jugVisitante ?? []) as $j): ?>
                  <option value="<?= (int)$j['id_jugador'] ?>">
                    <?= htmlspecialchars($j['apellidos'] . ', ' . $j['nombres']) ?>
                  </option>
                <?php endforeach; ?>
              </optgroup>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Tipo de tiro</label>
            <select name="tipo_tiro" class="form-select" required>
              <option value="libre">Libre (1)</option>
              <option value="dos">Dos puntos (2)</option>
              <option value="tres">Tres puntos (3)</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Cantidad</label>
            <input type="number" name="cantidad" class="form-control" value="1" min="1" required>
          </div>

          <button class="btn btn-success">Agregar</button>
          <a href="/ui/partidos" class="btn btn-secondary">Cancelar</a>
        </form>
      </div>
    </div>

    <!-- Tabla de anotaciones -->
    <div class="col-lg-7">
      <div class="card p-3">
        <h5 class="mb-3"><i class="bi bi-clipboard2-data me-1"></i> Anotaciones</h5>
        <div class="table-responsive">
          <table class="table align-middle">
            <thead>
              <tr>
                <th>#</th>
                <th>Jugador</th>
                <th>Jornada</th>
                <th>Tipo</th>
                <th>Cant.</th>
                <th style="width:120px">Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach (($anotaciones ?? []) as $i => $a): ?>
                <tr>
                  <td><?= $i+1 ?></td>
                  <td><?= htmlspecialchars(($a['apellidos'] ?? '').', '.($a['nombres'] ?? '')) ?></td>
                  <td><?= htmlspecialchars($a['jornada'] ?? '—') ?></td>
                  <td><?= htmlspecialchars($a['tipo_tiro']) ?></td>
                  <td><?= (int)$a['cantidad'] ?></td>
                  <td>
                    <form method="POST" action="/ui/partidos/puntos/<?= (int)$a['id_punto'] ?>/delete">
                    <input type="hidden" name="back" value="/ui/partidos/<?= (int)$partido['id_partido'] ?>/puntos">
                    <button class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar esta anotación?')">Eliminar</button>
                    </form>

                  </td>
                </tr>
              <?php endforeach; ?>
              <?php if (empty($anotaciones)): ?>
                <tr><td colspan="6" class="text-center text-muted">Sin anotaciones</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
