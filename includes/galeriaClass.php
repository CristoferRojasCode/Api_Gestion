<?php
class Galeria {
    private $conn;
    private $table = "galeria";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $query = "SELECT * FROM " . $this->table;
        return $this->conn->query($query);
    }

    public function create($titulo, $categoria, $archivo, $descripcion) {
        $query = "INSERT INTO " . $this->table . " (titulo, categoria, archivo, descripcion)
                  VALUES (:titulo, :categoria, :archivo, :descripcion)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":titulo", $titulo);
        $stmt->bindParam(":categoria", $categoria);
        $stmt->bindParam(":archivo", $archivo);
        $stmt->bindParam(":descripcion", $descripcion);

        return $stmt->execute();
    }

    public function update($id, $titulo, $categoria, $archivo) {
        $query = "UPDATE " . $this->table . " SET titulo = :titulo, categoria = :categoria, archivo = :archivo, descripcion = :descripcion
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":titulo", $titulo);
        $stmt->bindParam(":categoria", $categoria);
        $stmt->bindParam(":archivo", $archivo);
        $stmt->bindParam(":descripcion", $descripcion);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }
}
?>