<?php
include '../db/sesion.php';
include '../db/conexion.php';
requerir_rol('profesor');

$profesor_id = $_SESSION['usuario_id'];
$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nueva_calif'])) {
    $estudiante_id = mysqli_real_escape_string($conexion, $_POST['estudiante_id']);
    $materia_id = mysqli_real_escape_string($conexion, $_POST['materia_id']);
    $calificacion = mysqli_real_escape_string($conexion, $_POST['calificacion']);

    if (!empty($estudiante_id) && !empty($materia_id) && $calificacion !== '') {
        $sql = "INSERT INTO calificaciones (estudiante_id, materia_id, calificacion)
                VALUES ('$estudiante_id', '$materia_id', '$calificacion')";

        if (mysqli_query($conexion, $sql)) {
            $mensaje = "Calificación registrada con éxito";
        } else {
            $mensaje = "Error al registrar: " . mysqli_error($conexion);
        }
    } else {
        $mensaje = "Error: Todos los campos son obligatorios";
    }
}

$estudiantes = mysqli_query($conexion, "SELECT DISTINCT e.id, e.nombre, e.apellido
  FROM estudiantes e
  INNER JOIN alumno_grupo ag ON e.id = ag.alumno_id
  INNER JOIN profesor_grupo_materia pgm ON ag.grupo_id = pgm.grupo_id
  WHERE e.tipo = 'alumno' AND pgm.profesor_id = '$profesor_id'
  ORDER BY e.apellido ASC");

$materias = mysqli_query($conexion, "SELECT DISTINCT m.id, m.nombre
  FROM materias m
  INNER JOIN profesor_grupo_materia pgm ON m.id = pgm.materia_id
  WHERE pgm.profesor_id = '$profesor_id'
  ORDER BY m.nombre ASC");

$calificaciones = mysqli_query($conexion, "SELECT c.id, e.nombre, e.apellido, m.nombre AS materia, c.calificacion
  FROM calificaciones c
  INNER JOIN estudiantes e ON c.estudiante_id = e.id
  INNER JOIN materias m ON c.materia_id = m.id
  INNER JOIN profesor_grupo_materia pgm ON pgm.materia_id = m.id
  INNER JOIN alumno_grupo ag ON ag.alumno_id = e.id AND ag.grupo_id = pgm.grupo_id
  WHERE pgm.profesor_id = '$profesor_id'
  GROUP BY c.id
  ORDER BY c.id DESC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Subir Calificaciones</title>
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
      <li onclick="window.location.href='../comun/ingresar_estudiante.php'">
        <span class="material-symbols-outlined icono">person_add</span> Ingresar
      </li>
      <li onclick="window.location.href='../comun/alta_materias.php'">
        <span class="material-symbols-outlined icono">menu_book</span> Materias
      </li>
      <li onclick="window.location.href='subir_calificaciones.php'">
        <span class="material-symbols-outlined icono">upload</span> Calificaciones
      </li>
      <li onclick="window.location.href='editar_independiente.php'">
        <span class="material-symbols-outlined icono">edit_note</span> Editar
      </li>
      <li onclick="window.location.href='../comun/cerrar_sesion.php'">
        <span class="material-symbols-outlined icono">logout</span> Cerrar Sesion
      </li>
    </ul>
  </nav>

  <main class="contenido">
    <div class="formulario" style="width: 850px; max-width: 90%; height: auto; min-height: 600px; overflow-y: auto;">
      <h2>Subir Calificaciones</h2>

      <?php if ($mensaje != ''): ?>
        <p class="mensaje"><?php echo $mensaje; ?></p>
      <?php endif; ?>

      <form method="POST">
        <input type="hidden" name="nueva_calif" value="1">
        <div class="pregunta">
          <label>Estudiante</label>
          <select name="estudiante_id" required>
            <option value="">Seleccionar estudiante</option>
            <?php while ($est = mysqli_fetch_assoc($estudiantes)): ?>
              <option value="<?php echo $est['id']; ?>">
                <?php echo $est['apellido'] . ' ' . $est['nombre']; ?>
              </option>
            <?php endwhile; ?>
          </select>
        </div>
        <div class="pregunta">
          <label>Materia</label>
          <select name="materia_id" required>
            <option value="">Seleccionar materia</option>
            <?php while ($mat = mysqli_fetch_assoc($materias)): ?>
              <option value="<?php echo $mat['id']; ?>">
                <?php echo $mat['nombre']; ?>
              </option>
            <?php endwhile; ?>
          </select>
        </div>
        <div class="pregunta">
          <label>Calificación (0-100)</label>
          <input type="number" name="calificacion" min="0" max="100" step="0.1" required>
        </div>
        <button type="submit" class="boton">
            <span class="material-symbols-outlined icono">upload</span> Subir
        </button>
      </form>

      <hr style="margin-top: 30px; border: 0.5px solid #d1d1d1; opacity: 0.3;">
      <h3 style="margin-top: 20px; color: #0f1e3f;">Calificaciones Registradas</h3>

      <table class="tabla-lista" style="margin-top: 15px;">
        <thead>
            <tr>
              <th>Estudiante</th>
              <th>Materia</th>
              <th>Calificación</th>
              <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($cal = mysqli_fetch_assoc($calificaciones)): ?>
            <tr>
              <td><?php echo $cal['apellido'] . ' ' . $cal['nombre']; ?></td>
              <td><?php echo $cal['materia']; ?></td>
              <td>
                <form action="procesar_calificacion.php" method="POST" id="f-<?php echo $cal['id']; ?>">
                    <input type="hidden" name="id" value="<?php echo $cal['id']; ?>">
                    <input type="hidden" name="accion" value="editar_inplace">
                    <span id="t-<?php echo $cal['id']; ?>"><?php echo $cal['calificacion']; ?></span>
                    <input type="number" step="0.1" name="calificacion" id="i-<?php echo $cal['id']; ?>"
                           value="<?php echo $cal['calificacion']; ?>"
                           style="display:none; width:65px; border: 1px solid var(--arena-claro); padding: 5px;">
                </form>
              </td>
              <td>
                <button type="button" class="boton" style="padding: 5px 10px; margin:0;" onclick="activarEdicion(<?php echo $cal['id']; ?>)" id="be-<?php echo $cal['id']; ?>">
                    <span class="material-symbols-outlined icono">edit</span>
                </button>
                <button type="submit" form="f-<?php echo $cal['id']; ?>" class="boton" id="bs-<?php echo $cal['id']; ?>" style="display:none; padding: 5px 10px; margin:0; background: green;">
                    <span class="material-symbols-outlined icono">save</span>
                </button>

                <a href="procesar_calificacion.php?eliminar=<?php echo $cal['id']; ?>"
                   onclick="return confirm('¿Desea eliminar esta calificación?')"
                   class="btn-eliminar" style="margin-left:10px;">
                   <span class="material-symbols-outlined icono">delete</span>
                </a>
              </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </main>
</div>

<script>
function activarEdicion(id) {
    document.getElementById('t-' + id).style.display = 'none';
    document.getElementById('be-' + id).style.display = 'none';
    document.getElementById('i-' + id).style.display = 'inline-block';
    document.getElementById('bs-' + id).style.display = 'inline-block';
}
</script>

</body>
</html>
