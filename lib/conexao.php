<?php
# criar arquivo .env para projetos que precisam de maior segurança.
$host = "localhost";
$username = "root";
$password = "";
$database = "doa_pe";

$pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
