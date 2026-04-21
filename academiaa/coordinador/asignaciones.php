<?php
include '../db/sesion.php';
include '../db/conexion.php';
requerir_rol('coordinador');

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['asignar'])) {
    $profesor_id = mysqli_real_escape_string($conexion, $_POST['profesor_id']);
    $grupo_id = mysqli_real_escape_string($conexion, $_POST['grupo_id']);
    $materia_id = mysqli_real_escape_string($conexion, $_POST['materia_id']);

    $sql = "INSERT INTO profesor_grupo_materia (profesor_id, grupo_id, materia_id)
            VALUES ('$profesor_id', '$grupo_id', '$materia_id')";
    if (mysqli_query($conexion, $sql)) {
        $mensaje = "Materia asignada al profesor";
    } else {
        $mensaje = "Error al asignar";
    }
}

if (isset($_GET['quitar'])) {
    $id = mysqli_real_escape_string($conexion, $_GET['quitar']);
    mysqli_query($conexion, "DELETE FROM profesor_grupo_materia WHERE id = $id");
    header("Location: asignaciones.php");
    exit;
}

$profesores = mysqli_query($conexion, "SELECT id, nombre, apellido FROM estudiantes WHERE tipo = 'profesor' ORDER BY apellido ASC");
$grupos = mysqli_query($conexion, "SELECT * FROM grupos ORDER BY nombre ASC");
$materias = mysqli_query($conexion, "SELECT * FROM materias ORDER BY nombre ASC");

$asignaciones = mysqli_query($conexion, "SELECT pgm.id, e.nombre, e.apellido, g.nombre AS grupo, m.nombre AS materia
  FROM profesor_grupo_materia pgm
  INNER JOIN estudiantes e ON pgm.profesor_id = e.id
  INNER JOIN grupos g ON pgm.grupo_id = g.id
  INNER JOIN materias m ON pgm.materia_id = m.id
  ORDER BY e.apellido ASC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Asignaciones</title>
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
    <div class="formulario" style="width: 850px; max-width: 95%;">
      <h2>Asignaciones de Profesor</h2>

      <?php if ($mensaje != ''): ?>
        <p class="mensaje"><?php echo $mensaje; ?></p>
      <?php endif; ?>

      <form method="POST">
        <input type="hidden" name="asignar" value="1">
        <div class="pregunta">
          <label>Profesor</label>
          <select name="profesor_id" required>
            <option value="">Seleccionar</option>
            <?php while ($p = mysqli_fetch_assoc($profesores)): ?>
              <option value="<?php echo $p['id']; ?>"><?php echo $p['apellido'].' '.$p['nombre']; ?></option>
            <?php endwhile; ?>
          </select>
        </div>
        <div class="pregunta">
          <label>Grupo</label>
          <select name="grupo_id" required>
            <option value="">Seleccionar</option>
            <?php while ($g = mysqli_fetch_assoc($grupos)): ?>
              <option value="<?php echo $g['id']; ?>"><?php echo $g['nombre']; ?></option>
            <?php endwhile; ?>
          </select>
        </div>
        <div class="pregunta">
          <label>Materia</label>
          <select name="materia_id" required>
            <option value="">Seleccionar</option>
            <?php while ($m = mysqli_fetch_assoc($materias)): ?>
              <option value="<?php echo $m['id']; ?>"><?php echo $m['nombre']; ?></option>
            <?php endwhile; ?>
          </select>
        </div>
        <button type="submit" class="boton">
          <span class="material-symbols-outlined icono">save</span> Asignar
        </button>
      </form>

      <h3 style="margin-top: 30px; color: #0f1e3f;">Asignaciones Actuales</h3>
      <table class="tabla-lista" style="margin-top: 10px;">
        <tr>
          <th>Profesor</th>
          <th>Grupo</th>
          <th>Materia</th>
          <th>Acciones</th>
        </tr>
        <?php while ($a = mysqli_fetch_assoc($asignaciones)): ?>
        <tr>
          <td><?php echo $a['apellido'].' '.$a['nombre']; ?></td>
          <td><?php echo $a['grupo']; ?></td>
          <td><?php echo $a['materia']; ?></td>
          <td>
            <a href="asignaciones.php?quitar=<?php echo $a['id']; ?>"
               onclick="return confirm('¿Quitar asignacion?')"
               class="btn-eliminar">
              <span class="material-symbols-outlined icono">delete</span>
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
