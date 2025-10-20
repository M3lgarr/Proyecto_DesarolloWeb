<?php $title = 'Inicio'; ?> 
<link rel="stylesheet" href="/assets/css/home.css">

<div class="container py-5">
  <div class="row g-4 justify-content-center">

    <!-- Card Equipos -->
    <div class="col-md-5 col-lg-4">
      <div class="feature-card text-center p-4 h-100">
        <div class="icon-circle bg-primary-gradient mb-3">
          <i class="bi bi-people-fill"></i>
        </div>
        <h5 class="fw-semibold mb-2">Equipos</h5>
        <p class="text-muted mb-4 small">Administra todos los equipos inscritos en el torneo.</p>
        <a href="/ui/equipos" class="action-btn">
          Ir a Equipos
          <i class="bi bi-arrow-right-circle-fill ms-1"></i>
        </a>
      </div>
    </div>

    <!-- Card Jugadores -->
    <div class="col-md-5 col-lg-4">
      <div class="feature-card text-center p-4 h-100">
        <div class="icon-circle bg-green-gradient mb-3">
          <i class="bi bi-person-badge-fill"></i>
        </div>
        <h5 class="fw-semibold mb-2">Jugadores</h5>
        <p class="text-muted mb-4 small">Gestiona jugadores y sus fotografías de perfil.</p>
        <a href="/ui/jugadores" class="action-btn green">
          Ir a Jugadores
          <i class="bi bi-arrow-right-circle-fill ms-1"></i>
        </a>
      </div>
    </div>

    <!-- Card Partidos -->
    <div class="col-md-5 col-lg-4">
      <div class="feature-card text-center p-4 h-100">
        <div class="icon-circle bg-purple-gradient mb-3">
          <i class="bi bi-calendar2-week"></i>
        </div>
        <h5 class="fw-semibold mb-2">Partidos</h5>
        <p class="text-muted mb-4 small">Programa, gestiona y registra resultados de los partidos.</p>
        <a href="/ui/partidos" class="action-btn purple">
          Ir a Partidos
          <i class="bi bi-arrow-right-circle-fill ms-1"></i>
        </a>
      </div>
    </div>

    <!-- Card Tabla de Posiciones -->
    <div class="col-md-5 col-lg-4">
      <div class="feature-card text-center p-4 h-100">
        <div class="icon-circle bg-indigo-gradient mb-3">
          <i class="bi bi-bar-chart-line-fill"></i>
        </div>
        <h5 class="fw-semibold mb-2">Tabla de Posiciones</h5>
        <p class="text-muted mb-4 small">Consulta la clasificación general y estadísticas del torneo.</p>
        <a href="/ui/tabla" class="action-btn indigo">
          Ver Tabla
          <i class="bi bi-arrow-right-circle-fill ms-1"></i>
        </a>
      </div>
    </div>

  </div>
</div>

<!-- Bootstrap Icons -->
