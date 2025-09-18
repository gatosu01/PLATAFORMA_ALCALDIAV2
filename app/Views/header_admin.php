<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    
    <title>Header Admin</title>
</head>
<body>
    

    <!-- Barra lateral para administradores -->
    <div class="sidebar">
      <div class="sidebar-header">
        <h2>Administración</h2>
        <span class="sidebar-toggle" id="sidebar-toggle">&#9776;</span>
      </div>
      <ul id="sidebar-links">
  <li><a href="/Alcaldia/app/Views/admin_panel.php" class="<?= basename($_SERVER['PHP_SELF']) == 'admin_panel.php' ? 'active' : '' ?>">Inicio</a></li>
  <li><a href="/Alcaldia/app/Views/suggestions_admin.php" class="<?= basename($_SERVER['PHP_SELF']) == 'suggestions_admin.php' ? 'active' : '' ?>">Sugerencias</a></li>
  <li><a href="/Alcaldia/app/Views/complaints_admin.php" class="<?= basename($_SERVER['PHP_SELF']) == 'complaints_admin.php' ? 'active' : '' ?>">Quejas</a></li>
  <li><a href="/Alcaldia/app/Views/reports_admin.php" class="<?= basename($_SERVER['PHP_SELF']) == 'reports_admin.php' ? 'active' : '' ?>">Denuncias</a></li>
  <li><a href="/Alcaldia/app/Views/notification_admin.php" class="<?= basename($_SERVER['PHP_SELF']) == 'notification_admin.php' ? 'active' : '' ?>">Notificaciones</a></li>
  <li><a href="/Alcaldia/app/Views/faq_admin.php" class="<?= basename($_SERVER['PHP_SELF']) == 'faq_admin.php' ? 'active' : '' ?>">FAQ</a></li>
  <li><a href="/Alcaldia/app/Views/admin_slider.php" class="<?= basename($_SERVER['PHP_SELF']) == 'admin_slider.php' ? 'active' : '' ?>">Slider img</a></li>
  <li><a href="/Alcaldia/app/Views/admin_postulations.php" class="<?= basename($_SERVER['PHP_SELF']) == 'admin_postulations.php' ? 'active' : '' ?>">Postulaciones</a></li>
  <li><a href="/Alcaldia/logout">Cerrar Sesión</a></li>
      </ul>
    </div>
    <script>
      const sidebarToggle = document.getElementById("sidebar-toggle");
      const sidebar = document.querySelector(".sidebar");
      const sidebarLinks = document.getElementById("sidebar-links");
      sidebarToggle.addEventListener("click", () => {
        sidebar.classList.toggle("open");
        sidebarLinks.classList.toggle("show");
      });
    </script>
</body>
</html>
