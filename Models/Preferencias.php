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

    public function listarPorUsuario($usuario) {
        $stmt = $this->conexion->prepare(
            "CALL sp_listar_preferencias_usuario(:usuario)"
        );
        $stmt->execute([
            ':usuario' => $usuario
        ]);

        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        if ($resultado) {
            return $resultado;
        }

        return [];
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

    public static function normalizarListado($preferencias) {
        $resultado = [];

        if (empty($preferencias)) {
            return $resultado;
        }

        if (!is_array($preferencias)) {
            $preferencias = [$preferencias];
        }

        foreach ($preferencias as $preferencia) {
            $nombreGenero = self::obtenerNombreGenero($preferencia);

            if ($nombreGenero !== '') {
                $resultado[] = self::normalizarNombre($nombreGenero);
            }
        }

        return $resultado;
    }

    public static function atributoChecked($preferencias, $nombreGenero) {
        $nombreGenero = self::normalizarNombre($nombreGenero);

        if (in_array($nombreGenero, $preferencias, true)) {
            return ' checked';
        }

        return '';
    }

    private static function obtenerNombreGenero($preferencia) {
        if (is_object($preferencia)) {
            if (isset($preferencia->nombreGenero)) {
                return trim((string)$preferencia->nombreGenero);
            }

            if (isset($preferencia->nombre_genero)) {
                return trim((string)$preferencia->nombre_genero);
            }

            if (isset($preferencia->genero)) {
                return trim((string)$preferencia->genero);
            }
        }

        if (is_array($preferencia)) {
            if (isset($preferencia['nombreGenero'])) {
                return trim((string)$preferencia['nombreGenero']);
            }

            if (isset($preferencia['nombre_genero'])) {
                return trim((string)$preferencia['nombre_genero']);
            }

            if (isset($preferencia['genero'])) {
                return trim((string)$preferencia['genero']);
            }
        }

        return trim((string)$preferencia);
    }

    private static function normalizarNombre($nombreGenero) {
        $nombreGenero = trim((string)$nombreGenero);

        if (function_exists('mb_strtolower')) {
            return mb_strtolower($nombreGenero, 'UTF-8');
        }

        return strtolower($nombreGenero);
    }
}
