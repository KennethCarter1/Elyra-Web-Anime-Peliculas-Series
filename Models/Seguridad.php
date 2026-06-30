<?php
class Seguridad
{
    private static $envCargado = false;

    public static function tokenCsrf()
    {
        self::asegurarSesion();

        if (!isset($_SESSION['csrf_token']) || $_SESSION['csrf_token'] === '') {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }

    public static function campoCsrf()
    {
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(self::tokenCsrf(), ENT_QUOTES, 'UTF-8') . '">';
    }

    public static function csrfValido($post)
    {
        self::asegurarSesion();

        if (!isset($_SESSION['csrf_token']) || $_SESSION['csrf_token'] === '') {
            return false;
        }

        if (!isset($post['csrf_token']) || $post['csrf_token'] === '') {
            return false;
        }

        if (hash_equals($_SESSION['csrf_token'], (string)$post['csrf_token'])) {
            return true;
        }

        return false;
    }

    public static function requerirPost($urlRedireccion)
    {
        if (!isset($_SERVER['REQUEST_METHOD']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . $urlRedireccion);
            exit;
        }
    }

    public static function validarCsrfPost($urlRedireccion, $claveMensaje = '', $claveTipo = '')
    {
        self::asegurarSesion();

        if (self::csrfValido($_POST)) {
            return;
        }

        if ($claveMensaje !== '') {
            $_SESSION[$claveMensaje] = 'Solicitud no válida. Recarga la página e inténtalo de nuevo.';
        }

        if ($claveTipo !== '') {
            $_SESSION[$claveTipo] = 'error';
        }

        header('Location: ' . $urlRedireccion);
        exit;
    }

    public static function tokenSoapInterno()
    {
        self::cargarEnv();

        if (isset($_ENV['SOAP_INTERNAL_TOKEN']) && trim($_ENV['SOAP_INTERNAL_TOKEN']) !== '') {
            return trim($_ENV['SOAP_INTERNAL_TOKEN']);
        }

        $base = realpath(__DIR__ . '/..');
        if ($base === false) {
            $base = __DIR__ . '/..';
        }

        return hash('sha256', $base . '|elyra-soap-interno');
    }

    public static function soapInternoValido($servidor)
    {
        $tokenRecibido = '';

        if (isset($servidor['HTTP_X_ELYRA_SOAP_TOKEN'])) {
            $tokenRecibido = trim($servidor['HTTP_X_ELYRA_SOAP_TOKEN']);
        }

        if ($tokenRecibido === '') {
            return false;
        }

        if (hash_equals(self::tokenSoapInterno(), $tokenRecibido)) {
            return true;
        }

        return false;
    }

    private static function cargarEnv()
    {
        if (self::$envCargado) {
            return;
        }

        self::$envCargado = true;
        $archivoEnv = __DIR__ . '/../.env';

        if (!file_exists($archivoEnv)) {
            return;
        }

        $lineas = file($archivoEnv, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lineas as $linea) {
            $linea = trim($linea);

            if ($linea === '') {
                continue;
            }

            if (strpos($linea, '#') === 0) {
                continue;
            }

            if (strpos($linea, '=') === false) {
                continue;
            }

            list($nombre, $valor) = explode('=', $linea, 2);
            $nombre = trim($nombre);

            if ($nombre === '') {
                continue;
            }

            if (!isset($_ENV[$nombre]) || $_ENV[$nombre] === '') {
                $_ENV[$nombre] = trim($valor);
            }
        }
    }

    private static function asegurarSesion()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }
}
?>
