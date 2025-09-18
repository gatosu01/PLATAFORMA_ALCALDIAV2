<!-- Barra lateral para administradores -->
<div class="sidebar">
  <div class="sidebar-header">
    <h2>Administración</h2>
    <div class="sidebar-toggle" id="sidebar-toggle">☰</div>
  </div>
  <ul id="sidebar-links">
    <li><a href="admin_panel.php" class="<?= basename($_SERVER['PHP_SELF']) == 'admin_panel.php' ? 'active' : '' ?>">Inicio</a></li>
    <li><a href="suggestions_admin.php" class="<?= basename($_SERVER['PHP_SELF']) == 'suggestions_admin.php' ? 'active' : '' ?>">Sugerencias</a></li>
    <li><a href="complaints_admin.php" class="<?= basename($_SERVER['PHP_SELF']) == 'complaints_admin.php' ? 'active' : '' ?>">Quejas</a></li>
    <li><a href="reports_admin.php" class="<?= basename($_SERVER['PHP_SELF']) == 'reports_admin.php' ? 'active' : '' ?>">Denuncias</a></li>
    <li><a href="notification_admin.php" class="<?= basename($_SERVER['PHP_SELF']) == 'notification_admin.php' ? 'active' : '' ?>">Notificaciones</a></li>
    <li><a href="faq_admin.php" class="<?= basename($_SERVER['PHP_SELF']) == 'faq_admin.php' ? 'active' : '' ?>">FAQ</a></li>
    <li><a href="admin_slider.php" class="<?= basename($_SERVER['PHP_SELF']) == 'admin_slider.php' ? 'active' : '' ?>">Slider img</a></li>
    <li><a href="admin_postulations.php" class="<?= basename($_SERVER['PHP_SELF']) == 'admin_postulations.php' ? 'active' : '' ?>">Postulaciones</a></li>
    <li><a href="logout.php" class="<?= basename($_SERVER['PHP_SELF']) == 'logout.php' ? 'active' : '' ?>">Cerrar Sesión</a></li>
  </ul>
</div>

<script>
  const sidebarToggle = document.getElementById("sidebar-toggle");
  const sidebarLinks = document.getElementById("sidebar-links");

  sidebarToggle.addEventListener("click", () => {
    sidebarLinks.classList.toggle("show");
  });
</script>
