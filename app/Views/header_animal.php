<!-- Barra lateral para Bienestar Animal -->
<div class="sidebar">
  <div class="sidebar-header">
    <h2>Bienestar Animal</h2>
    <div class="sidebar-toggle" id="sidebar-toggle">☰</div>
  </div>
  <ul id="sidebar-links">
  <li><a href="/Alcaldia/animal-admin-panel" class="<?= strpos($_SERVER['REQUEST_URI'], 'animal-admin-panel') !== false ? 'active' : '' ?>">Mascotas Extraviadas</a></li>
  <li><a href="/Alcaldia/denuncia-animal-admin" class="<?= strpos($_SERVER['REQUEST_URI'], 'denuncia-animal-admin') !== false ? 'active' : '' ?>">Denuncia Animal</a></li>
  <li><a href="/Alcaldia/logout">Cerrar Sesión</a></li>
  </ul>
</div>

<script>
  const sidebarToggle = document.getElementById("sidebar-toggle");
  const sidebarLinks = document.getElementById("sidebar-links");

  sidebarToggle.addEventListener("click", () => {
    sidebarLinks.classList.toggle("show");
  });
</script>
