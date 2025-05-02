<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Mostrar errores solo en desarrollo
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(["error" => "Método no permitido"]);
    exit;
}

include_once '../../includes/DatabaseClass.php';
include_once '../../includes/eventosClass.php';

$db = (new Database())->getConnection();
$evento = new Eventos($db); 

$result = $evento->getAll();

if (!$result) {
    http_response_code(500);
    echo json_encode(["error" => "Error en la consulta"]);
    exit;
}

$datos = [];

while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $row['imagen'] = 'http://localhost/Api_Gestion/uploads/' . $row['imagen'];

    $datos[] = [
        'id'            => (int)$row['id'],
        'titulo'        => $row['titulo'],
        'descripcion'   => $row['descripcion'],
        'fecha'         => $row['fecha'],
        'hora_inicio'   => $row['hora_inicio'],
        'hora_fin'      => $row['hora_fin'],
        'lugar'         => $row['lugar'],
        'categoria'     => $row['categoria'],
        'participantes' => (int)$row['participantes'],
        'imagen'        => $row['imagen'],
    ];
}

echo json_encode($datos);
