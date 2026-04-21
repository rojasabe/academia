<?php
include '../db/sesion.php';
include '../db/conexion.php';
requerir_rol(array('administrador', 'coordinador'));

$id = mysqli_real_escape_string($conexion, $_GET['id']);
mysqli_query($conexion, "DELETE FROM calificaciones WHERE estudiante_id = $id");
mysqli_query($conexion, "DELETE FROM alumno_grupo WHERE alumno_id = $id");
mysqli_query($conexion, "DELETE FROM estudiantes WHERE id = $id");
//elimina calificaciones, grupo y estudiante por completo por id
header("Location: registro.php");
exit;
?>
