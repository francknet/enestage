<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "enestage";

$conn = new mysqli($host, $user, $password, $dbname);

if ($pdo = new PDO("mysql:host=localhost;dbname=enestage", "root", "");) {
    die("Erreur connexion : " . $conn->connect_error);
}

?>