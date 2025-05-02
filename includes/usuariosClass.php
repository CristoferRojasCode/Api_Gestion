<?php
class Usuario {
    private $conn;
    private $table = "usuarios";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function login($usuario, $clave) {
        $query = "SELECT * FROM " . $this->table . " WHERE usuario = :usuario AND registroEstado = 'Activo'";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":usuario", $usuario);
        $stmt->execute();
    
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
            // Validar la contraseña encriptada
            if (password_verify($clave, $row['clave'])) {
                // No devuelvas la clave ni datos sensibles
                return [
                    "status" => "ok",
                    "message" => "Inicio de sesión exitoso",
                    "user" => [
                        "id" => $row['id'],
                        "usuario" => $row['usuario'],
                        "clave" => $row['clave']
                    ]
                ];
            } else {
                return ["status" => "error", "message" => "Contraseña incorrecta"];
            }
        }
    
        return ["status" => "error", "message" => "Usuario no encontrado"];
    }
    

    public function create($usuario, $clave) {
        $query = "INSERT INTO " . $this->table . " (usuario, clave)
                  VALUES (:usuario, :clave)";
        $stmt = $this->conn->prepare($query);
    
        // Encriptar la contraseña antes de guardar
        $hashedPassword = password_hash($clave, PASSWORD_DEFAULT);
    
        $stmt->bindParam(":usuario", $usuario);
        $stmt->bindParam(":clave", $hashedPassword);
    
        return $stmt->execute();
    }
    

    public function update($id, $usuario, $clave) {
        $query = "UPDATE " . $this->table . " SET usuario = :usuario, clave = :clave WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        // Hash la nueva clave
        $hashedPassword = password_hash($clave, PASSWORD_DEFAULT);

        $stmt->bindParam(":usuario", $usuario);
        $stmt->bindParam(":clave", $hashedPassword);
        $stmt->bindParam(":id", $id);

        return $stmt->execute();
    }
    
    public function getAll() {
        $query = "SELECT id, usuario FROM " . $this->table;
        return $this->conn->query($query);
    }
}
?>