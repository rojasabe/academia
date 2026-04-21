<?php
include '../db/sesion.php';
include '../db/conexion.php';
requerir_rol('administrador');

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nuevo'])) {
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $sql = "INSERT INTO grupos (nombre) VALUES ('$nombre')";
    //crea grupo con el nombre indicado en el post nombre
    if (mysqli_query($conexion, $sql)) {
        $mensaje = "Grupo registrado";
    } else {
        $mensaje = "Error al registrar";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['asignar_alumno'])) {
    $alumno_id = mysqli_real_escape_string($conexion, $_POST['alumno_id']);
    $grupo_id = mysqli_real_escape_string($conexion, $_POST['grupo_id']);
    $sql = "INSERT INTO alumno_grupo (alumno_id, grupo_id) VALUES ('$alumno_id', '$grupo_id')";
    //asigna al alumno a un grupo seleccionado en alumno_id y grupo_id
    if (mysqli_query($conexion, $sql)) {
        $mensaje = "Alumno asignado al grupo";
    } else {
        $mensaje = "Error al asignar";
    }
}

if (isset($_GET['eliminar'])) {
    $id = mysqli_real_escape_string($conexion, $_GET['eliminar']);
    //al eliminar desvincula los alumnos del grupo antes de eliminarlo
    mysqli_query($conexion, "DELETE FROM alumno_grupo WHERE grupo_id = $id");
    //tambien profesores y materias
    mysqli_query($conexion, "DELETE FROM profesor_grupo_materia WHERE grupo_id = $id");
    //y de la tabla principal
    mysqli_query($conexion, "DELETE FROM grupos WHERE id = $id");
    header("Location: grupos.php");
    exit;
}

$grupos = mysqli_query($conexion, "SELECT * FROM grupos ORDER BY nombre ASC");
$alumnos = mysqli_query($conexion, "SELECT id, nombre, apellido FROM estudiantes WHERE tipo = 'alumno' ORDER BY apellido ASC");
$grupos_sel = mysqli_query($conexion, "SELECT * FROM grupos ORDER BY nombre ASC");

//obtiene todos los alumnos con su grupo asignado, uniendo alumno_grupo con estudiantes y grupos
$asignaciones = mysqli_query($conexion, "SELECT ag.id, e.nombre, e.apellido, g.nombre AS grupo
  FROM alumno_grupo ag
  INNER JOIN estudiantes e ON ag.alumno_id = e.id
  INNER JOIN grupos g ON ag.grupo_id = g.id
  ORDER BY g.nombre, e.apellido ASC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Grupos</title>
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
      <li onclick="window.location.href='configuracion.php'">
        <span class="material-symbols-outlined icono">manage_accounts</span> Usuarios
      </li>
      <li onclick="window.location.href='grupos.php'">
        <span class="material-symbols-outlined icono">groups</span> Grupos
      </li>
      <li onclick="window.location.href='../comun/alta_materias.php'">
        <span class="material-symbols-outlined icono">menu_book</span> Materias
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
      <h2>Gestion de Grupos</h2>

      <?php if ($mensaje != ''): ?>
        <p class="mensaje"><?php echo $mensaje; ?></p>
      <?php endif; ?>

      <form method="POST">
        <input type="hidden" name="nuevo" value="1">
        <div class="pregunta">
          <label>Nombre del Grupo</label>
          <input type="text" name="nombre" required>
        </div>
        <button type="submit" class="boton">
          <span class="material-symbols-outlined icono">save</span> Guardar
        </button>
      </form>

      <h3 style="margin-top: 30px; color: #0f1e3f;">Grupos Registrados</h3>
      <table class="tabla-lista" style="margin-top: 10px;">
        <tr>
          <th>ID</th>
          <th>Nombre</th>
          <th>Acciones</th>
        </tr>
        <?php while ($g = mysqli_fetch_assoc($grupos)): ?>
        <tr>
          <td><?php echo $g['id']; ?></td>
          <td><?php echo $g['nombre']; ?></td>
          <td>
            <a href="grupos.php?eliminar=<?php echo $g['id']; ?>"
               onclick="return confirm('¿Eliminar grupo?')"
               class="btn-eliminar">
              <span class="material-symbols-outlined icono">delete</span>
            </a>
          </td>
        </tr>
        <?php endwhile; ?>
      </table>

      <h3 style="margin-top: 30px; color: #0f1e3f;">Asignar Alumno a Grupo</h3>
      <form method="POST">
        <input type="hidden" name="asignar_alumno" value="1">
        <div class="pregunta">
          <label>Alumno</label>
          <select name="alumno_id" required>
            <option value="">Seleccionar</option>
            <?php while ($a = mysqli_fetch_assoc($alumnos)): ?>
              <option value="<?php echo $a['id']; ?>"><?php echo $a['apellido'].' '.$a['nombre']; ?></option>
            <?php endwhile; ?>
          </select>
        </div>
        <div class="pregunta">
          <label>Grupo</label>
          <select name="grupo_id" required>
            <option value="">Seleccionar</option>
            <?php while ($g = mysqli_fetch_assoc($grupos_sel)): ?>
              <option value="<?php echo $g['id']; ?>"><?php echo $g['nombre']; ?></option>
            <?php endwhile; ?>
          </select>
        </div>
        <button type="submit" class="boton">
          <span class="material-symbols-outlined icono">save</span> Asignar
        </button>
      </form>

      <h3 style="margin-top: 30px; color: #0f1e3f;">Alumnos por Grupo</h3>
      <table class="tabla-lista" style="margin-top: 10px;">
        <tr>
          <th>Grupo</th>
          <th>Alumno</th>
        </tr>
        <?php while ($r = mysqli_fetch_assoc($asignaciones)): ?>
        <tr>
          <td><?php echo $r['grupo']; ?></td>
          <td><?php echo $r['apellido'].' '.$r['nombre']; ?></td>
        </tr>
        <?php endwhile; ?>
      </table>
    </div>
  </main>
</div>

</body>
</html>
