<?php
include '../db/sesion.php';
include '../db/conexion.php';
requerir_rol('coordinador');

$mensaje = '';

if (isset($_GET['baja'])) {
    $id = mysqli_real_escape_string($conexion, $_GET['baja']);
    //deletea las asignaciones de grupo y materia del profesor antes de darlo de baja
    mysqli_query($conexion, "DELETE FROM profesor_grupo_materia WHERE profesor_id = $id");
    //deletea el registro profesor y el filtro AND tipo profesor evita el borrar otros roles por error
    mysqli_query($conexion, "DELETE FROM estudiantes WHERE id = $id AND tipo = 'profesor'");
    header("Location: profesores.php");
    exit;
}

//profesores organizados por apellido
$profesores = mysqli_query($conexion, "SELECT * FROM estudiantes WHERE tipo = 'profesor' ORDER BY apellido ASC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Profesores</title>
  <link rel="stylesheet" href="../assets/estilos.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined">
</head>

<body>

<div class="panel">

  <nav class="menuprinci">
    <div class="perfil"><?php echo $_SESSION['usuario']; ?></div>
    <p style="color: #cdaa80; font-size: 12px; margin-bottom: 10px;"><?php echo $_SESSION['tipo']; ?></p>
    <ul>
      <li onclick="window.location.href='../comun/registro.php'">
        <span class="material-symbols-outlined icono">home</span> Inicio
      </li>
      <li onclick="window.location.href='profesores.php'">
        <span class="material-symbols-outlined icono">school</span> Profesores
      </li>
      <li onclick="window.location.href='alumnos.php'">
        <span class="material-symbols-outlined icono">group</span> Alumnos
      </li>
      <li onclick="window.location.href='asignaciones.php'">
        <span class="material-symbols-outlined icono">assignment_ind</span> Asignaciones
      </li>
      <li onclick="window.location.href='estadisticas.php'">
        <span class="material-symbols-outlined icono">insights</span> Estadisticas
      </li>
      <li onclick="window.location.href='../comun/cerrar_sesion.php'">
        <span class="material-symbols-outlined icono">logout</span> Cerrar Sesion
      </li>
    </ul>
  </nav>

  <main class="contenido">
    <div class="formulario" style="width: 800px; max-width: 95%;">
      <h2>Profesores</h2>

      <?php if ($mensaje != ''): ?>
        <p class="mensaje"><?php echo $mensaje; ?></p>
      <?php endif; ?>

      <table class="tabla-lista" style="margin-top: 10px;">
        <tr>
          <th>Nombre</th>
          <th>Apellido</th>
          <th>Email</th>
          <th>Usuario</th>
          <th>Acciones</th>
        </tr>
        <?php while ($p = mysqli_fetch_assoc($profesores)): ?>
        <tr>
          <td><?php echo $p['nombre']; ?></td>
          <td><?php echo $p['apellido']; ?></td>
          <td><?php echo $p['email']; ?></td>
          <td><?php echo $p['usuario']; ?></td>
          <td>
            <a href="profesores.php?baja=<?php echo $p['id']; ?>"
               onclick="return confirm('¿Dar de baja al profesor?')"
               class="btn-eliminar">
              <span class="material-symbols-outlined icono">person_remove</span>
            </a>
          </td>
        </tr>
        <?php endwhile; ?>
      </table>
    </div>
  </main>
</div>

</body>
</html>
