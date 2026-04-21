<?php
$conexion = mysqli_connect("localhost", "root", "", "siceacademia");
if (!$conexion) {
    die("No se pudo conectar a la base de datos");
}
//conexion que se llama por la varibale $conexion
//en todas empieza con session_start porque inicia una nueva sesión o reanuda una existente para que puedas ver
?>
