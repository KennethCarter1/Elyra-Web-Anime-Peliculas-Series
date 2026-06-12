<?php
class Contenido {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }


    public function agregar($titulo, $descripcion, $idGenero, $urlImagen) {
        $stmt = $this->conexion->prepare(
            "CALL sp_agregar_pelicula(:titulo, :descripcion, :idGenero, :urlImagen)"
        );
        $stmt->bindParam(':titulo', $titulo);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':idGenero', $idGenero);
        $stmt->bindParam(':urlImagen', $urlImagen);
        $stmt->execute();
    }


    public function editar($idContenido, $titulo, $descripcion, $idGenero, $urlImagen) {
        $stmt = $this->conexion->prepare(
            "CALL sp_editar_pelicula(:idContenido, :titulo, :descripcion, :idGenero, :urlImagen)"
        );
        $stmt->bindParam(':idContenido', $idContenido);
        $stmt->bindParam(':titulo', $titulo);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':idGenero', $idGenero);
        $stmt->bindParam(':urlImagen', $urlImagen);
        $stmt->execute();
    }


    public function eliminar($idContenido) {
        $stmt = $this->conexion->prepare("CALL sp_eliminar_pelicula(:idContenido)");
        $stmt->bindParam(':idContenido', $idContenido);
        $stmt->execute();
    }


    public function listar() {
        $stmt = $this->conexion->prepare("CALL sp_listar_peliculas()");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

  
    public function obtenerPorId($idContenido) {
        $stmt = $this->conexion->prepare(
            "SELECT * FROM peliculas_series WHERE id_pelicula = :idContenido"
        );
        $stmt->bindParam(':idContenido', $idContenido);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}