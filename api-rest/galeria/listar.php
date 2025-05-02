<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include_once '../../includes/DatabaseClass.php';
include_once '../../includes/galeriaClass.php';

$db = (new Database())->getConnection();
$galeria = new Galeria($db);

$result = $galeria->getAll();

$datos = [];

while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $host = $_SERVER['HTTP_HOST'];
    $row['archivo'] = 'http://localhost/Api_Gestion/uploads/' . $row['archivo'];
    $datos[] = $row;
}

echo json_encode($datos);
