<nav class="navbar navbar-expand-lg navbar-light">
  <div class="container-fluid">
    <!-- Logo que redirige al index -->
    <!-- si es tecnico se va a la pagina index_tecnico.php -->
    <?php if (isset($_SESSION['tecnico'])):?>
    <a class="navbar-brand menuti" href="index_tecnico.php">
      <img src="codes/img/logo.png" alt="Logo" width="40" height="40" />
      CCSS Orotina-San Mateo
    </a>
    <?php else:?>
    <a class="navbar-brand menuti" href="index.php">
      <img src="codes/img/logo.png" alt="Logo" width="40" height="40" />
      CCSS Orotina-San Mateo
    </a>
    <?php endif;?>

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
