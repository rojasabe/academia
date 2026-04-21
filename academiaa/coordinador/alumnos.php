<?php
include '../db/sesion.php';
include '../db/conexion.php';
requerir_rol('coordinador');

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['actualizar'])) {
    $id = mysqli_real_escape_string($conexion, $_POST['id']);
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $apellido = mysqli_real_escape_string($conexion, $_POST['apellido']);
    $email = mysqli_real_escape_string($conexion, $_POST['email']);
    $telefono = mysqli_real_escape_string($conexion, $_POST['telefono']);

    $sql = "UPDATE estudiantes SET nombre='$nombre', apellido='$apellido', email='$email', telefono='$telefono'
            WHERE id='$id' AND tipo='alumno'";
    if (mysqli_query($conexion, $sql)) {
        $mensaje = "Alumno actualizado";
    } else {
        $mensaje = "Error al actualizar";
    }
}

$alumnos = mysqli_query($conexion, "SELECT * FROM estudiantes WHERE tipo = 'alumno' ORDER BY apellido ASC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Alumnos</title>
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
    <div class="formulario" style="width: 900px; max-width: 95%;">
      <h2>Alumnos</h2>

      <?php if ($mensaje != ''): ?>
        <p class="mensaje"><?php echo $mensaje; ?></p>
      <?php endif; ?>

      <table class="tabla-lista" style="margin-top: 10px;">
        <tr>
          <th>Nombre</th>
          <th>Apellido</th>
          <th>Email</th>
          <th>Telefono</th>
          <th>Acciones</th>
        </tr>
        <?php while ($a = mysqli_fetch_assoc($alumnos)): ?>
        <tr>
          <form method="POST">
            <input type="hidden" name="id" value="<?php echo $a['id']; ?>">
            <td><input type="text" name="nombre" value="<?php echo $a['nombre']; ?>" style="width:110px; padding:5px;"></td>
            <td><input type="text" name="apellido" value="<?php echo $a['apellido']; ?>" style="width:110px; padding:5px;"></td>
            <td><input type="text" name="email" value="<?php echo $a['email']; ?>" style="width:160px; padding:5px;"></td>
            <td><input type="text" name="telefono" value="<?php echo $a['telefono']; ?>" style="width:110px; padding:5px;"></td>
            <td>
              <button type="submit" name="actualizar" class="boton" style="padding:5px 10px; margin:0;">
                <span class="material-symbols-outlined icono">save</span>
              </button>
            </td>
          </form>
        </tr>
        <?php endwhile; ?>
      </table>
    </div>
  </main>
</div>

</body>
</html>
