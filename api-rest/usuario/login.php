<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
// Permitir solicitudes CORS desde cualquier origen (o especifica uno seguro)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Manejar preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

include_once '../../includes/DatabaseClass.php';
include_once '../../includes/usuariosClass.php';

$db = (new Database())->getConnection();
$usuario = new Usuario($db);

$data = json_decode(file_get_contents("php://input"));

if (!isset($data->usuario) || !isset($data->clave)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Datos incompletos"]);
    exit;
}

$resultado = $usuario->login($data->usuario, $data->clave);

if ($resultado["status"] === "ok") {
    http_response_code(200);
} else {
    http_response_code(401);
}

echo json_encode($resultado);
