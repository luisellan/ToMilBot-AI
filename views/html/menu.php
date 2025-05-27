<?php
$role = $_SESSION['rol_id'] ?? 1; // 2 por defecto si no está seteado
?>
<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
  <!-- LOGO -->
  <div class="navbar-brand-box">
    <!-- Dark Logo-->
    <a href="index.html" class="logo logo-dark">
      <span class="logo-sm">
        <img src="../../assets/icon/icono.png" alt="" height="40">
      </span>
      <span class="logo-lg">
        <img src="../../assets/icon/icono.png" alt="" height="50">
      </span>
    </a>
    <!-- Light Logo-->
    <a href="index.html" class="logo logo-light">
      <span class="logo-sm">
        <img src="../../assets/icon/icono.png" alt="" height="40">
      </span>
      <span class="logo-lg">
        <img src="../../assets/icon/icono.png" alt="" height="50">
      </span>
    </a>
    <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
      <i class="ri-record-circle-line"></i>
    </button>
  </div>

  <div id="scrollbar">
    <div class="container-fluid">
      <div id="two-column-menu"></div>
      <ul class="navbar-nav" id="navbar-nav">
        <li class="menu-title"><span data-key="t-menu">Menu</span></li>

        <li class="nav-item">
          <a href="../home/" class="nav-link" data-key="t-administrador">
            <i class="ri-dashboard-line"></i>
            <span data-key="t-administrador">Dashboard</span>
          </a>
        </li>

        <li class="nav-item">
          <a href="../Tomilboot/" class="nav-link" data-key="t-tomilboot">
            <i class="ri-apps-2-line"></i>
            <span data-key="t-tomilboot">Tomilboot AI</span>
          </a>
        </li>

        <li class="nav-item">
          <a href="../ManteniemtoHistorial/" class="nav-link" data-key="t-historial">
            <i class="ri-history-line"></i>
            <span data-key="t-historial">Historial</span>
          </a>
        </li>
        <?php if ($role === 2): // Solo admin ve lo siguiente 
        ?>
          <li class="menu-title">
            <i class="ri-more-fill"></i>
            <span data-key="t-pages">Pages</span>
          </li>

          <li class="nav-item">
            <a href="../MntUsuario/" class="nav-link" data-key="t-user">
              <i class="ri-user-line"></i>
              <span data-key="t-user">Usuarios</span>
            </a>
            <a href="../MntRol/" class="nav-link" data-key="t-roles">
              <i class="ri-shield-keyhole-line"></i>
              <span data-key="t-roles">Roles</span>
            </a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
    <!-- Sidebar -->
  </div>

  <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
<!-- Vertical Overlay-->