<?php $title = 'Tabla de posiciones'; ?>
<div class="container">
  <!-- 🏆 Título -->
  <div class="d-flex align-items-center justify-content-between mb-2">
    <h3 class="mb-0">TABLA DE POSICIONES</h3>
  </div>

  <!-- 📝 Mini leyenda superior -->
  <div class="d-flex flex-wrap gap-3 align-items-center small text-muted mb-3">
    <div><i class="bi bi-bar-chart-fill text-primary me-1"></i><strong>PJ</strong>: Jugados</div>
    <div><i class="bi bi-trophy-fill text-success me-1"></i><strong>PG</strong>: Ganados</div>
    <div><i class="bi bi-x-circle-fill text-danger me-1"></i><strong>PP</strong>: Perdidos</div>
    <div><i class="bi bi-dash-circle-fill text-warning me-1"></i><strong>PE</strong>: Empates</div>
    <div><i class="bi bi-star-fill text-primary me-1"></i><strong>PTS</strong>: Puntos</div>
  </div>

  <!-- 📊 Tabla principal -->
  <div class="card p-3 mb-3">
    <div class="table-responsive">
      <table class="table align-middle">
        <thead>
          <tr>
            <th>#</th>
            <th>Equipo</th>
            <th class="text-center">PJ</th>
            <th class="text-center">PG</th>
            <th class="text-center">PP</th>
            <th class="text-center">PE</th>
            <th class="text-center">PF</th>
            <th class="text-center">PC</th>
            <th class="text-center">DIF</th>
            <th class="text-center">PTS</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($tabla as $i => $r): ?>
            <tr>
              <td><?= $i+1 ?></td>
              <td><?= htmlspecialchars($r['nombre_equipo']) ?></td>
              <td class="text-center"><?= (int)$r['PJ'] ?></td>
              <td class="text-center"><?= (int)$r['PG'] ?></td>
              <td class="text-center"><?= (int)$r['PP'] ?></td>
              <td class="text-center"><?= (int)$r['PE'] ?></td>
              <td class="text-center"><?= (int)$r['PF'] ?></td>
              <td class="text-center"><?= (int)$r['PC'] ?></td>
              <td class="text-center"><?= (int)$r['DIF'] ?></td>
              <td class="text-center fw-semibold"><?= (int)$r['PTS'] ?></td>
            </tr>
          <?php endforeach; ?>
          <?php if (empty($tabla)): ?>
            <tr><td colspan="10" class="text-center text-muted">Sin datos aún</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- 📝 Leyenda completa -->
  <div class="card p-3 small text-muted">
    <strong class="mb-2 d-block"><i class="bi bi-info-circle me-1"></i> Significado de columnas</strong>
    <ul class="mb-0 mt-2" style="columns: 2; -webkit-columns: 2; -moz-columns: 2; list-style: none; padding-left: 0;">
      <li><i class="bi bi-bar-chart-fill text-primary me-1"></i><strong>PJ</strong>: Partidos Jugados</li>
      <li><i class="bi bi-trophy-fill text-success me-1"></i><strong>PG</strong>: Partidos Ganados</li>
      <li><i class="bi bi-x-circle-fill text-danger me-1"></i><strong>PP</strong>: Partidos Perdidos</li>
      <li><i class="bi bi-dash-circle-fill text-warning me-1"></i><strong>PE</strong>: Empatados</li>
      <li><i class="bi bi-graph-up text-success me-1"></i><strong>PF</strong>: Puntos a Favor</li>
      <li><i class="bi bi-graph-down text-danger me-1"></i><strong>PC</strong>: Puntos en Contra</li>
      <li><i class="bi bi-arrow-left-right text-secondary me-1"></i><strong>DIF</strong>: Diferencia (PF - PC)</li>
      <li><i class="bi bi-star-fill text-primary me-1"></i><strong>PTS</strong>: Puntos Totales</li>
    </ul>
  </div>
</div>
