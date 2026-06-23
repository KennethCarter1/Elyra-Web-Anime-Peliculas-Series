<?php
class Genero {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function listar() {
        $stmt = $this->conexion->prepare("CALL sp_listar_generos()");
        $stmt->execute();
        $generos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        return $generos;
    }

    public function obtenerIdPorNombre($nombreGenero) {
        $stmt = $this->conexion->prepare(
            "CALL sp_obtener_id_genero_por_nombre(:nombreGenero)"
        );
        $stmt->execute([':nombreGenero' => $nombreGenero]);
        $genero = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        if ($genero) {
            return (int)$genero['id_genero'];
        }

        return 0;
    }

    public function listarGestion() {
        $stmt = $this->conexion->prepare("CALL sp_listar_generos_gestion()");
        $stmt->execute();
        $generos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        return $generos;
    }

    public function crear($nombreGenero) {
        $stmt = $this->conexion->prepare("CALL sp_crear_genero(:nombreGenero)");
        $stmt->execute([
            ':nombreGenero' => $nombreGenero
        ]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        if ($resultado) {
            return $resultado;
        }

        return [
            'exito' => 0,
            'mensaje' => 'No se pudo agregar el género',
            'id_genero' => 0
        ];
    }

    public function actualizar($idGenero, $nombreGenero) {
        $stmt = $this->conexion->prepare("CALL sp_actualizar_genero(:idGenero, :nombreGenero)");
        $stmt->execute([
            ':idGenero' => $idGenero,
            ':nombreGenero' => $nombreGenero
        ]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        if ($resultado) {
            return $resultado;
        }

        return [
            'exito' => 0,
            'mensaje' => 'No se pudo actualizar el género',
            'id_genero' => $idGenero
        ];
    }

    public function desactivar($idGenero) {
        $stmt = $this->conexion->prepare("CALL sp_desactivar_genero(:idGenero)");
        $stmt->execute([
            ':idGenero' => $idGenero
        ]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        if ($resultado) {
            return $resultado;
        }

        return [
            'exito' => 0,
            'mensaje' => 'No se pudo desactivar el género',
            'id_genero' => $idGenero
        ];
    }

    public function activar($idGenero) {
        $stmt = $this->conexion->prepare("CALL sp_activar_genero(:idGenero)");
        $stmt->execute([
            ':idGenero' => $idGenero
        ]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        if ($resultado) {
            return $resultado;
        }

        return [
            'exito' => 0,
            'mensaje' => 'No se pudo activar el género',
            'id_genero' => $idGenero
        ];
    }

    public static function normalizarListado($generos)
    {
        if (!is_array($generos)) {
            return [];
        }

        $generosNormalizados = [];

        foreach ($generos as $genero) {
            if (is_object($genero) && isset($genero->nombre_genero)) {
                $genero = $genero->nombre_genero;
            }

            if (is_object($genero) && isset($genero->nombreGenero)) {
                $genero = $genero->nombreGenero;
            }

            if (is_array($genero) && isset($genero['nombre_genero'])) {
                $genero = $genero['nombre_genero'];
            }

            if (is_array($genero) && isset($genero['nombreGenero'])) {
                $genero = $genero['nombreGenero'];
            }

            $nombreGenero = trim((string)$genero);

            if ($nombreGenero !== '') {
                $generosNormalizados[] = $nombreGenero;
            }
        }

        return $generosNormalizados;
    }

    public static function normalizarGestion($generos)
    {
        if (!is_array($generos)) {
            return [];
        }

        $generosNormalizados = [];

        foreach ($generos as $genero) {
            $idGenero = self::leerCampo($genero, 'idGenero', 'id_genero');
            $nombreGenero = self::leerCampo($genero, 'nombreGenero', 'nombre_genero');
            $activo = self::leerCampo($genero, 'activo', 'activo');
            $totalContenido = self::leerCampo($genero, 'totalContenido', 'total_contenido');
            $totalPreferencias = self::leerCampo($genero, 'totalPreferencias', 'total_preferencias');

            if ($nombreGenero !== '') {
                $generosNormalizados[] = [
                    'id' => (int)$idGenero,
                    'nombre' => $nombreGenero,
                    'activo' => (int)$activo,
                    'estado' => self::textoEstado((int)$activo),
                    'estadoClase' => self::claseEstado((int)$activo),
                    'totalContenido' => (int)$totalContenido,
                    'totalPreferencias' => (int)$totalPreferencias
                ];
            }
        }

        return $generosNormalizados;
    }

    public static function normalizarUsuario($generos)
    {
        $generosGestion = self::normalizarGestion($generos);
        $generosUsuario = [];

        foreach ($generosGestion as $genero) {
            if ((int)$genero['activo'] === 1) {
                $generosUsuario[] = [
                    'id' => (int)$genero['id'],
                    'nombre' => $genero['nombre'],
                    'icono' => self::iconoUsuario($genero['nombre'])
                ];
            }
        }

        return $generosUsuario;
    }

    public static function nombreUsuarioPorId($generos, $idGenero)
    {
        if ((int)$idGenero <= 0) {
            return 'Todos los géneros';
        }

        foreach ($generos as $genero) {
            if ((int)$genero['id'] === (int)$idGenero) {
                return $genero['nombre'];
            }
        }

        return 'Género';
    }

    public static function listadoGestionInicial()
    {
        return [];
    }

    public static function valorBusqueda($get)
    {
        if (isset($get['busqueda'])) {
            return trim($get['busqueda']);
        }

        return '';
    }

    public static function filtrarGestion($generos, $busqueda)
    {
        $busqueda = strtolower(trim($busqueda));

        if ($busqueda === '') {
            return $generos;
        }

        $filtrados = [];

        foreach ($generos as $genero) {
            if (strpos(strtolower($genero['nombre']), $busqueda) !== false) {
                $filtrados[] = $genero;
            }
        }

        return $filtrados;
    }

    public static function valorSeguro($valor)
    {
        return htmlspecialchars((string)$valor, ENT_QUOTES, 'UTF-8');
    }

    public static function contarActivos($generos)
    {
        $total = 0;

        foreach ($generos as $genero) {
            if ((int)$genero['activo'] === 1) {
                $total++;
            }
        }

        return $total;
    }

    private static function iconoUsuario($nombreGenero)
    {
        $genero = strtolower(trim($nombreGenero));

        if ($genero === 'acción' || $genero === 'accion') {
            return 'fa-solid fa-bolt';
        }

        if ($genero === 'romance') {
            return 'fa-solid fa-heart';
        }

        if ($genero === 'comedia') {
            return 'fa-solid fa-face-laugh';
        }

        if ($genero === 'drama') {
            return 'fa-solid fa-masks-theater';
        }

        if ($genero === 'terror') {
            return 'fa-solid fa-ghost';
        }

        if ($genero === 'fantasía' || $genero === 'fantasia') {
            return 'fa-solid fa-wand-magic-sparkles';
        }

        if ($genero === 'aventura') {
            return 'fa-solid fa-compass';
        }

        if ($genero === 'isekai') {
            return 'fa-solid fa-torii-gate';
        }

        if ($genero === 'escolar') {
            return 'fa-solid fa-book-open';
        }

        if ($genero === 'ciencia ficción' || $genero === 'ciencia ficcion') {
            return 'fa-solid fa-rocket';
        }

        if ($genero === 'deportes') {
            return 'fa-solid fa-trophy';
        }

        return 'fa-solid fa-tag';
    }

    public static function contarInactivos($generos)
    {
        $total = 0;

        foreach ($generos as $genero) {
            if ((int)$genero['activo'] === 0) {
                $total++;
            }
        }

        return $total;
    }

    public static function totalUsoContenido($generos)
    {
        $total = 0;

        foreach ($generos as $genero) {
            $total += (int)$genero['totalContenido'];
        }

        return $total;
    }

    private static function leerCampo($origen, $campoObjeto, $campoArreglo)
    {
        if (is_object($origen) && isset($origen->$campoObjeto)) {
            return $origen->$campoObjeto;
        }

        if (is_array($origen) && isset($origen[$campoArreglo])) {
            return $origen[$campoArreglo];
        }

        if (is_array($origen) && isset($origen[$campoObjeto])) {
            return $origen[$campoObjeto];
        }

        return '';
    }

    private static function textoEstado($activo)
    {
        if ((int)$activo === 1) {
            return 'Activo';
        }

        return 'Desactivado';
    }

    private static function claseEstado($activo)
    {
        if ((int)$activo === 1) {
            return 'activo';
        }

        return 'desactivado';
    }
}
?>
