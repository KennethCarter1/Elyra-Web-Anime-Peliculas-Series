<?php
class Usuario{
    private $conexion;

    public function __construct($conexion){
        $this->conexion = $conexion;
    }

    public function registrar($nombre, $usuario, $correo, $contrasena,$fechaNacimiento){
        
        $hash=password_hash($contrasena, PASSWORD_DEFAULT);
        $consulta = $this->conexion->prepare(
            "CALL sp_registrar_usuario(:nombre, :usuario, :correo, :contrasena, :fechaNacimiento)"
        );
        $consulta->bindParam(':nombre', $nombre);
        $consulta->bindParam(':usuario', $usuario);
        $consulta->bindParam(':correo', $correo);
        $consulta->bindParam(':contrasena', $hash);
        $consulta->bindParam(':fechaNacimiento', $fechaNacimiento);
        $consulta->execute();
    }

    public function obtenerPorUsuario($usuario, $rol = 'usuario') {
        $consulta = $this->conexion->prepare(
            "CALL sp_login_usuario(:usuario, :rol)"
        );
        $consulta->bindParam(':usuario', $usuario);
        $consulta->bindParam(':rol', $rol);
        $consulta->execute();
        return $consulta->fetch(PDO::FETCH_ASSOC);
    }

}

?>