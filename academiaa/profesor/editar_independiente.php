<?php
include '../db/sesion.php';
include '../db/conexion.php';
requerir_rol('profesor');

$profesor_id = $_SESSION['usuario_id'];
$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['actualizar_nota'])) {
    $id_reg = mysqli_real_escape_string($conexion, $_POST['id_calificacion']);
    $nueva_nota = mysqli_real_escape_string($conexion, $_POST['nota']);

    $sql_update = "UPDATE calificaciones SET calificacion = '$nueva_nota' WHERE id = '$id_reg'";
    if (mysqli_query($conexion, $sql_update)) {
        $mensaje = "Calificación guardada correctamente.";
    } else {
        $mensaje = "Error al guardar: " . mysqli_error($conexion);
    }
}

$query = "SELECT c.id as calif_id, e.nombre, e.apellido, m.nombre as nombre_materia, c.calificacion
          FROM calificaciones c
          JOIN estudiantes e ON c.estudiante_id = e.id
          JOIN materias m ON c.materia_id = m.id
          JOIN profesor_grupo_materia pgm ON pgm.materia_id = m.id
          JOIN alumno_grupo ag ON ag.alumno_id = e.id AND ag.grupo_id = pgm.grupo_id
          WHERE pgm.profesor_id = '$profesor_id'
          GROUP BY c.id
          ORDER BY e.apellido ASC";
$resultado = mysqli_query($conexion, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Buscador de Calificaciones</title>
  <link rel="stylesheet" href="../assets/estilos.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined">
</head>
<body>

<div class="panel">
  <nav class="menuprinci">
    <div class="perfil"><?php echo $_SESSION['usuario']; ?></div>
    <p style="color: #cdaa80; font-size: 12px; margin-bottom: 10px;"><?php echo $_SESSION['tipo']; ?></p>
    <ul>
      <li onclick="window.location.href='../comun/registro.php'"><span class="material-symbols-outlined icono">home</span> Inicio</li>
      <li onclick="window.location.href='subir_calificaciones.php'"><span class="material-symbols-outlined icono">arrow_back</span> Volver</li>
      <li onclick="window.location.href='../comun/cerrar_sesion.php'"><span class="material-symbols-outlined icono">logout</span> Cerrar Sesion</li>
    </ul>
  </nav>

  <main class="contenido">
    <div class="formulario" style="width: 90%; max-width: 1000px;">
      <h2>Buscar y Editar Calificaciones</h2>

      <div style="margin-bottom: 20px; background: #f9f9f9; padding: 15px; border-radius: 8px; border-left: 5px solid #cdaa80;">
          <label style="font-weight: bold; color: #0f1e3f; display: block; margin-bottom: 5px;">Escribe el nombre del alumno:</label>
          <input type="text" id="buscador" onkeyup="filtrarTabla()" placeholder="Ej. Ana Camila..." style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
      </div>

      <?php if ($mensaje != ''): ?>
        <p style="background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 15px;"><?php echo $mensaje; ?></p>
      <?php endif; ?>

      <table class="tabla-lista" id="tablaDatos">
        <thead>
          <tr>
            <th>Estudiante</th>
            <th>Materia</th>
            <th>Nota</th>
            <th>Acción</th>
          </tr>
        </thead>
        <tbody>
          <?php while($row = mysqli_fetch_assoc($resultado)): ?>
          <tr>
            <td class="col-nombre"><?php echo $row['apellido'] . " " . $row['nombre']; ?></td>
            <td><?php echo $row['nombre_materia']; ?></td>
            <form method="POST">
              <td>
                <input type="hidden" name="id_calificacion" value="<?php echo $row['calif_id']; ?>">
                <input type="number" name="nota" value="<?php echo $row['calificacion']; ?>" step="0.1" min="0" max="10" style="width: 60px; padding: 5px; text-align: center;">
              </td>
              <td>
                <button type="submit" name="actualizar_nota" class="boton" style="padding: 5px 10px;">
                  <span class="material-symbols-outlined">save</span>
                </button>
              </td>
            </form>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </main>
</div>

<script>
function filtrarTabla() {
  var input = document.getElementById("buscador");
  var filter = input.value.toUpperCase();
  var table = document.getElementById("tablaDatos");
  var tr = table.getElementsByTagName("tr");

  for (var i = 1; i < tr.length; i++) {
    var td = tr[i].getElementsByClassName("col-nombre")[0];
    if (td) {
      var txtValue = td.textContent || td.innerText;
      tr[i].style.display = (txtValue.toUpperCase().indexOf(filter) > -1) ? "" : "none";
    }
  }
}
</script>

</body>
</html>
