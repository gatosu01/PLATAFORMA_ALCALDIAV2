<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
?>
<header>
  <nav>
    <div class="logo">
      <img src="/Alcaldia/public/IMG/logo.png" alt="Logo de la Alcaldía" />
    </div>
    <div class="menu-toggle" id="menu-toggle">☰</div>
    <ul class="nav-links" id="nav-links">
      <li><a href="/Alcaldia/home">INICIO</a></li>
      <li><a href="/Alcaldia/complaints-reports">QUEJAS/DENUNCIAS</a></li>
      <li><a href="/Alcaldia/procedures">TRÁMITES</a></li>
      <?php if (isset($_SESSION['usuario_id'])): ?>
        <li><a href="/Alcaldia/my-complaints">MIS DENUNCIAS</a></li>
        <li><a href="/Alcaldia/buzon">BUZON DE SUGERENCIAS</a></li>
        <li><a href="/Alcaldia/faq">PREGUNTAS FRECUENTES</a></li>
        <li><a href="/Alcaldia/logout" class="btn-login">CERRAR SESIÓN</a></li>
      <?php else: ?>
        <li><a href="/Alcaldia/buzon">BUZON DE SUGERENCIAS</a></li>
        <li><a href="/Alcaldia/faq">PREGUNTAS FRECUENTES</a></li>
        <li><a href="/Alcaldia/login" class="btn-login">INICIAR SESIÓN</a></li>
      <?php endif; ?>
    </ul>
  </nav>
</header>
<script>
  const menuToggle = document.getElementById("menu-toggle");
  const navLinks = document.getElementById("nav-links");
  menuToggle.addEventListener("click", () => {
    navLinks.classList.toggle("show");
  });
</script>
