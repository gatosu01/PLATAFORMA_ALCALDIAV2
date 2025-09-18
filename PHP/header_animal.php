<!-- Barra lateral para Bienestar Animal -->
<div class="sidebar">
  <div class="sidebar-header">
    <h2>Bienestar Animal</h2>
    <div class="sidebar-toggle" id="sidebar-toggle">☰</div>
  </div>
  <ul id="sidebar-links">
    <li><a href="animal_admin_panel.php" class="<?= basename($_SERVER['PHP_SELF']) == 'animal_admin_panel.php' ? 'active' : '' ?>">Mascotas Extraviadas</a></li>
    <li><a href="admin_denuncia_animal.php" class="<?= basename($_SERVER['PHP_SELF']) == 'admin_denuncia_animal.php' ? 'active' : '' ?>">Denuncia Animal</a></li>
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
