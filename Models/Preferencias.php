<?php
class Preferencia {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }  
    public function listar($idUsuario) {
        $stmt = $this->conexion->prepare(
            "CALL sp_listar_preferencias(:idUsuario)"
        );
        $stmt->bindParam(':idUsuario', $idUsuario);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function actualizarPorUsuario($usuario, $generos) {
        if (is_array($generos)) {
            $generos = implode(',', $generos);
        }

        $stmt = $this->conexion->prepare(
            "CALL sp_actualizar_preferencias_usuario(:usuario, :generos)"
        );
        $stmt->execute([
            ':usuario' => $usuario,
            ':generos' => $generos
        ]);

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        if ($resultado) {
            return $resultado;
        }

        return [
            'exito' => 0,
            'mensaje' => 'No se pudieron actualizar las preferencias',
            'total' => 0
        ];
    }
}
