<?php
include '../db/sesion.php';
include '../db/conexion.php';
requerir_rol('alumno');

$resultado = mysqli_query($conexion, "SELECT * FROM estudiantes WHERE tipo = 'alumno'");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Lista de Estudiantes</title>
  <link rel="stylesheet" href="../assets/estilos.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined">
</head>

<body>

<div class="panel">

  <nav class="menuprinci">
    <div class="perfil"><?php echo $_SESSION['usuario']; ?></div>
    <p style="color: #cdaa80; font-size: 10px; margin-bottom: 10px;"><?php echo $_SESSION['tipo']; ?></p>
    <ul>
      <li onclick="window.location.href='../comun/registro.php'">
        <span class="material-symbols-outlined icono">home</span> Inicio
      </li>
      <li onclick="window.location.href='lista_estudiantes.php'">
        <span class="material-symbols-outlined icono">group</span> Compañeros
      </li>
      <li onclick="window.location.href='mis_calificaciones.php'">
        <span class="material-symbols-outlined icono">school</span> Calificaciones
      </li>
      <li onclick="window.location.href='../comun/cerrar_sesion.php'">
        <span class="material-symbols-outlined icono">logout</span> Cerrar Sesion
      </li>
    </ul>
  </nav>

  <main class="contenido">
    <div class="formulario" style="width: 700px;">
      <h2>Compañeros</h2>

      <table class="tabla-lista">
        <tr>
          <th>Nombre</th>
          <th>Apellido</th>
          <th>Email</th>
          <th>Telefono</th>
        </tr>
        <?php while ($fila = mysqli_fetch_assoc($resultado)): ?>
        <tr>
          <td><?php echo $fila['nombre']; ?></td>
          <td><?php echo $fila['apellido']; ?></td>
          <td><?php echo $fila['email']; ?></td>
          <td><?php echo $fila['telefono']; ?></td>
        </tr>
        <?php endwhile; ?>
      </table>
    </div>
  </main>

</div>

</body>
</html>
