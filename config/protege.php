<?php
session_start();

if (!isset($_SESSION["usuario_id"])) {
    header("Location: /inventario-clinico/login.php");
    exit;
}
?>