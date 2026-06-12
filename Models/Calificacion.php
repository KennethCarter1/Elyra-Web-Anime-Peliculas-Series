<?php
class Calificacion {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

  
    public function agregar($idUsuario, $idContenido, $calificacion) {
        $stmt = $this->conexion->prepare(
            "CALL sp_agregar_calificacion(:idUsuario, :idContenido, :calificacion)"
        );
        $stmt->bindParam(':idUsuario', $idUsuario);
        $stmt->bindParam(':idContenido', $idContenido);
        $stmt->bindParam(':calificacion', $calificacion);
        $stmt->execute();
    }

    public function editar($idCalificacion, $calificacion) {
        $stmt = $this->conexion->prepare(
            "CALL sp_editar_calificacion(:idCalificacion, :calificacion)"
        );
        $stmt->bindParam(':idCalificacion', $idCalificacion);
        $stmt->bindParam(':calificacion', $calificacion);
        $stmt->execute();
    }


    public function eliminar($idCalificacion) {
        $stmt = $this->conexion->prepare(
            "CALL sp_eliminar_calificacion(:idCalificacion)"
        );
        $stmt->bindParam(':idCalificacion', $idCalificacion);
        $stmt->execute();
    }

   
    public function promedio($idContenido) {
        $stmt = $this->conexion->prepare(
            "CALL sp_promedio_calificacion(:idContenido)"
        );
        $stmt->bindParam(':idContenido', $idContenido);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}