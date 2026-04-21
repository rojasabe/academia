<?php
include '../db/sesion.php';
include '../db/conexion.php';
requerir_rol('profesor');

if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $stmt = $conexion->prepare("DELETE FROM calificaciones WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: subir_calificaciones.php?msg=eliminado");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion']) && $_POST['accion'] == 'editar_inplace') {
    $id = $_POST['id'];
    $nota = $_POST['calificacion'];

    $stmt = $conexion->prepare("UPDATE calificaciones SET calificacion = ? WHERE id = ?");
    $stmt->bind_param("di", $nota, $id);
    $stmt->execute();
    header("Location: subir_calificaciones.php?msg=actualizado");
    exit;
}
?>
