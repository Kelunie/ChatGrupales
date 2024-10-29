<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <!-- Logo que redirige al index -->
    <a class="navbar-brand" href="index.php">
      <img
        src="codes/img/logo.png"
        alt="Logo"
        width="40"
        height="40"
        class="d-inline-block align-text-top"
      />
      CCSS Orotina-San Mateo
    </a>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <!-- Verificar si el técnico está en sesión -->
        <?php if (isset($_SESSION['tecnico'])): ?>
        <li class="nav-item">
          <a class="nav-link" href="logout.php">Cerrar sesión</a>
        </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
