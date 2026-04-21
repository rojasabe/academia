<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function ruta_base() {
    $script = str_replace('\\', '/', $_SERVER['PHP_SELF']); //ruta del archivo actual y str_replace normaliza barras invertidas
    $subs = array('/admin/', '/coordinador/', '/alumno/', '/profesor/', '/comun/');
    foreach ($subs as $s) {
        if (strpos($script, $s) !== false) return '../'; //si el archivo está dentro de una subcarpeta sube un nivel con ../
    }
    return '';
}

function requerir_login() {
    if (!isset($_SESSION['usuario'])) {
        header("Location: " . ruta_base() . "index.php");
        exit;
    }
}

function requerir_rol($roles) {
    requerir_login();
    if (!is_array($roles)) {
        $roles = array($roles);
    }
    if (!in_array($_SESSION['tipo'], $roles)) {
        header("Location: " . ruta_base() . "comun/registro.php");
        exit;
    }
}

function cerrar_sesion() {
    $_SESSION = array();
    session_destroy(); //aquí hacemos el destroy para que cuando salgamos no vuelvas a entrar al mismo link y te deje entrar o entres a otro que no está permitido
    header("Location: " . ruta_base() . "index.php");//si lo haces te regresa a index.php o ruta base
    exit;
}
?>
