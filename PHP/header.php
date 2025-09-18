<?php session_status(); ?>
<header>
  <nav>
    <div class="logo">
      <img src="../IMG/logo.png" alt="Logo de la Alcaldía" />
    </div>

    <div class="menu-toggle" id="menu-toggle">☰</div>

    <ul class="nav-links" id="nav-links">
      <li><a href="../VIEWS/index_view.php">INICIO</a></li>
        <li><a href="../VIEWS/complaints_reports_view.php">QUEJAS/DENUNCIAS</a></li>
        <li><a href="../VIEWS/procedures_view.php">TRÁMITES</a></li>
      <?php if (isset($_SESSION['usuario_id'])): ?>
        <!-- Menú para usuarios logeados -->
        <li><a href="../PHP/my_complaints.php">MIS DENUNCIAS</a></li>
        <li><a href="../VIEWS/buzon_view.php">BUZON DE SUGERENCIAS</a></li>
        <li><a href="../VIEWS/faq.php">PREGUNTAS FRECUENTES</a></li>
        <li><a href="../PHP/logout.php" class="btn-login">CERRAR SESIÓN</a></li>
      <?php else: ?>
        <!-- Menú para visitantes -->
         <li><a href="../VIEWS/buzon_view.php">BUZON DE SUGERENCIAS</a></li>
        <li><a href="../VIEWS/faq.php">PREGUNTAS FRECUENTES</a></li>
        <li><a href="../VIEWS/sign_in_form.php" class="btn-login">INICIAR SESIÓN</a></li>
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