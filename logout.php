<?php
if (!isset($_SESSION)) {
    session_start();
}

session_destroy();
header("Location: pages/home-logado.php");
