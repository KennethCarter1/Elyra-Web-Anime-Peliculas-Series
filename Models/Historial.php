<?php
class Historial {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    
    public function agregar($idUsuario, $idContenido) {
        $stmt = $this->conexion->prepare(
            "CALL sp_agregar_historial(:idUsuario, :idContenido)"
        );
        $stmt->bindParam(':idUsuario', $idUsuario);
        $stmt->bindParam(':idContenido', $idContenido);
        $stmt->execute();
    }


    public function listar($idUsuario) {
        $stmt = $this->conexion->prepare(
            "CALL sp_listar_historial(:idUsuario)"
        );
        $stmt->bindParam(':idUsuario', $idUsuario);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}