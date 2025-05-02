<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

include_once '../../includes/DatabaseClass.php';
include_once '../../includes/galeriaClass.php';

$db = (new Database())->getConnection();
$galeria = new Galeria($db);

// Datos del formulario
$titulo = $_POST['titulo'] ?? '';
$descripcion = $_POST['descripcion'] ?? '';
$categoria = $_POST['categoria'] ?? '';
$archivo = '';

if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
    $nombreImagen = uniqid() . "_" . basename($_FILES['imagen']['name']);
    $ruta = '../../uploads/' . $nombreImagen;

    if (move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta)) {
        $archivo = $nombreImagen;
    } else {
        http_response_code(500);
        echo json_encode(["success" => false, "message" => "Error al mover la imagen al servidor"]);
        exit;
    }
} else {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Imagen no válida o faltante"]);
    exit;
}

$success = $galeria->create($titulo, $categoria, $archivo, $descripcion);
echo json_encode(["success" => $success]);
