<?php
class Sesion {
    public static function tomarMensaje($clave, $valorDefecto = '')
    {
        $mensaje = $valorDefecto;

        if (isset($_SESSION[$clave])) {
            $mensaje = $_SESSION[$clave];
            unset($_SESSION[$clave]);
        }

        return $mensaje;
    }

    public static function cerrarSesion()
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $parametros = session_get_cookie_params();
            $sameSite = 'Lax';

            if (isset($parametros['samesite'])) {
                $sameSite = $parametros['samesite'];
            }

            setcookie(session_name(), '', [
                'expires' => time() - 42000,
                'path' => $parametros['path'],
                'domain' => $parametros['domain'],
                'secure' => $parametros['secure'],
                'httponly' => $parametros['httponly'],
                'samesite' => $sameSite
            ]);
        }

        if (isset($_COOKIE['usuario_login'])) {
            setcookie('usuario_login', '', time() - 42000, '/');
        }

        session_destroy();
    }
}
?>
