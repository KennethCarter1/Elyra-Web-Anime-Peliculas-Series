<?php
class Genero {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }


    public function agregar($nombreGenero) {
        $stmt = $this->conexion->prepare(
            "CALL sp_agregar_genero(:nombreGenero)"
        );
        $stmt->bindParam(':nombreGenero', $nombreGenero);
        $stmt->execute();
    }


    public function editar($idGenero, $nombreGenero) {
        $stmt = $this->conexion->prepare(
            "CALL sp_editar_genero(:idGenero, :nombreGenero)"
        );
        $stmt->bindParam(':idGenero', $idGenero);
        $stmt->bindParam(':nombreGenero', $nombreGenero);
        $stmt->execute();
    }


    public function eliminar($idGenero) {
        $stmt = $this->conexion->prepare(
            "CALL sp_eliminar_genero(:idGenero)"
        );
        $stmt->bindParam(':idGenero', $idGenero);
        $stmt->execute();
    }

   
    public function listar() {
        $stmt = $this->conexion->prepare("CALL sp_listar_generos()");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

   
    public function obtenerPorId($idGenero) {
        $stmt = $this->conexion->prepare(
            "SELECT * FROM generos WHERE id_genero = :idGenero"
        );
        $stmt->bindParam(':idGenero', $idGenero);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}