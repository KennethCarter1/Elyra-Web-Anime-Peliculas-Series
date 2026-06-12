<?php
class Preferencia {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }


    public function agregar($idUsuario, $idGenero) {
        $stmt = $this->conexion->prepare(
            "CALL sp_agregar_preferencia(:idUsuario, :idGenero)"
        );
        $stmt->bindParam(':idUsuario', $idUsuario);
        $stmt->bindParam(':idGenero', $idGenero);
        $stmt->execute();
    }


    public function eliminar($idUsuario, $idGenero) {
        $stmt = $this->conexion->prepare(
            "CALL sp_eliminar_preferencia(:idUsuario, :idGenero)"
        );
        $stmt->bindParam(':idUsuario', $idUsuario);
        $stmt->bindParam(':idGenero', $idGenero);
        $stmt->execute();
    }

  
    public function listar($idUsuario) {
        $stmt = $this->conexion->prepare(
            "CALL sp_listar_preferencias(:idUsuario)"
        );
        $stmt->bindParam(':idUsuario', $idUsuario);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}