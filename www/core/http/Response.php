<?php

namespace app\core\http;

use app\controllers\BaseController;
use app\core\exceptions\HeadersAleadySentException;
use app\core\views\View;

class Response
{
    public const HTTP_CONTINUE = 100;
    public const HTTP_SWITCHING_PROTOCOLS = 101;
    public const HTTP_PROCESSING = 102; // RFC2518
    public const HTTP_EARLY_HINTS = 103; // RFC8297
    public const HTTP_OK = 200;
    public const HTTP_CREATED = 201;
    public const HTTP_ACCEPTED = 202;
    public const HTTP_NON_AUTHORITATIVE_INFORMATION = 203;
    public const HTTP_NO_CONTENT = 204;
    public const HTTP_RESET_CONTENT = 205;
    public const HTTP_PARTIAL_CONTENT = 206;
    public const HTTP_MULTI_STATUS = 207; // RFC4918
    public const HTTP_ALREADY_REPORTED = 208; // RFC5842
    public const HTTP_IM_USED = 226; // RFC3229
    public const HTTP_MULTIPLE_CHOICES = 300;
    public const HTTP_MOVED_PERMANENTLY = 301;
    public const HTTP_FOUND = 302;
    public const HTTP_SEE_OTHER = 303;
    public const HTTP_NOT_MODIFIED = 304;
    public const HTTP_USE_PROXY = 305;
    public const HTTP_RESERVED = 306;
    public const HTTP_TEMPORARY_REDIRECT = 307;
    public const HTTP_PERMANENTLY_REDIRECT = 308; // RFC7238
    public const HTTP_BAD_REQUEST = 400;
    public const HTTP_UNAUTHORIZED = 401;
    public const HTTP_PAYMENT_REQUIRED = 402;
    public const HTTP_FORBIDDEN = 403;
    public const HTTP_NOT_FOUND = 404;
    public const HTTP_METHOD_NOT_ALLOWED = 405;
    public const HTTP_NOT_ACCEPTABLE = 406;
    public const HTTP_PROXY_AUTHENTICATION_REQUIRED = 407;
    public const HTTP_REQUEST_TIMEOUT = 408;
    public const HTTP_CONFLICT = 409;
    public const HTTP_GONE = 410;
    public const HTTP_LENGTH_REQUIRED = 411;
    public const HTTP_PRECONDITION_FAILED = 412;
    public const HTTP_REQUEST_ENTITY_TOO_LARGE = 413;
    public const HTTP_REQUEST_URI_TOO_LONG = 414;
    public const HTTP_UNSUPPORTED_MEDIA_TYPE = 415;
    public const HTTP_REQUESTED_RANGE_NOT_SATISFIABLE = 416;
    public const HTTP_EXPECTATION_FAILED = 417;
    public const HTTP_I_AM_A_TEAPOT = 418; // RFC2324
    public const HTTP_MISDIRECTED_REQUEST = 421; // RFC7540
    public const HTTP_UNPROCESSABLE_ENTITY = 422; // RFC4918
    public const HTTP_LOCKED = 423; // RFC4918
    public const HTTP_FAILED_DEPENDENCY = 424; // RFC4918
    public const HTTP_TOO_EARLY = 425; // RFC-ietf-httpbis-replay-04
    public const HTTP_UPGRADE_REQUIRED = 426; // RFC2817
    public const HTTP_PRECONDITION_REQUIRED = 428; // RFC6585
    public const HTTP_TOO_MANY_REQUESTS = 429; // RFC6585
    public const HTTP_REQUEST_HEADER_FIELDS_TOO_LARGE = 431; // RFC6585
    public const HTTP_UNAVAILABLE_FOR_LEGAL_REASONS = 451; // RFC7725
    public const HTTP_INTERNAL_SERVER_ERROR = 500;
    public const HTTP_NOT_IMPLEMENTED = 501;
    public const HTTP_BAD_GATEWAY = 502;
    public const HTTP_SERVICE_UNAVAILABLE = 503;
    public const HTTP_GATEWAY_TIMEOUT = 504;
    public const HTTP_VERSION_NOT_SUPPORTED = 505;
    public const HTTP_VARIANT_ALSO_NEGOTIATES_EXPERIMENTAL = 506; // RFC2295
    public const HTTP_INSUFFICIENT_STORAGE = 507; // RFC4918
    public const HTTP_LOOP_DETECTED = 508; // RFC5842
    public const HTTP_NOT_EXTENDED = 510; // RFC2774
    public const HTTP_NETWORK_AUTHENTICATION_REQUIRED = 511;

    public static $statusTexts = [
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        // RFC2518
        103 => 'Early Hints',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',
        // RFC4918
        208 => 'Already Reported',
        // RFC5842
        226 => 'IM Used',
        // RFC3229
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect',
        // RFC7238
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Content Too Large',
        // RFC-ietf-httpbis-semantics
        414 => 'URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Range Not Satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot',
        // RFC2324
        421 => 'Misdirected Request',
        // RFC7540
        422 => 'Unprocessable Content',
        // RFC-ietf-httpbis-semantics
        423 => 'Locked',
        // RFC4918
        424 => 'Failed Dependency',
        // RFC4918
        425 => 'Too Early',
        // RFC-ietf-httpbis-replay-04
        426 => 'Upgrade Required',
        // RFC2817
        428 => 'Precondition Required',
        // RFC6585
        429 => 'Too Many Requests',
        // RFC6585
        431 => 'Request Header Fields Too Large',
        // RFC6585
        451 => 'Unavailable For Legal Reasons',
        // RFC7725
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates',
        // RFC2295
        507 => 'Insufficient Storage',
        // RFC4918
        508 => 'Loop Detected',
        // RFC5842
        510 => 'Not Extended',
        // RFC2774
        511 => 'Network Authentication Required',
        // RFC6585
    ];

    private $content;
    private int $status;
    private array $headers;
    private ?Cookie $cookie;
    private $redirect;
    private $error;
    public function __construct()
    {
        $this->cookie = null;
        $this->headers = [];
        $this->status = self::HTTP_OK;
        $this->content = '';
        $this->error = false;
    }

    /**
     * Set a header value.
     *
     * @param string $name The name of the header.
     * @param mixed $value The value of the header.
     * @return $this
     */
    public function header(string $name, mixed $value): self
    {
        $this->error = false;
        $this->headers[$name] = $value;
        return $this;
    }

    /**
     * @param array<string, string> $headers
     * @return self
     */
    public function withHeaders(array $headers = []): self
    {
        $this->error = false;
        $this->headers = array_merge($this->headers, $headers);
        return $this;
    }

    /**
     * 
     * @param string $content
     * @return \app\core\http\Response
     */
    public function content(string $content): self
    {
        $this->error = false;
        $this->content = $content;
        return $this;
    }

    public function status(int $status): self
    {
        $this->error = false;
        $this->status = $status;
        return $this;
    }

    public function cookie(...$args)
    {
        $numArgs = count($args);
        if ($numArgs === 1 && $args[0] instanceof Cookie) {
            $this->cookie = $args[0];
        } else if ($numArgs >= 2) {
            $name = $args[0];
            $value = $args[1];
            $this->cookie = new Cookie($name, $value);
            if (isset($args[3])) {
                $this->cookie->setExpiration($args[3]);
            }
            if (isset($args[4])) {
                $this->cookie->setPath($args[4]);
            }
            if (isset($args[5])) {
                $this->cookie->setDomain($args[5]);
            }
            if (isset($args[6])) {
                $this->cookie->setSecure($args[6]);
            }
            if (isset($args[7])) {
                $this->cookie->setHttpOnly($args[7]);
            }
        } else {
            throw new \InvalidArgumentException('Invalid number of arguments');
        }
    }

    public function view(...$args): self
    {
        try {
            if (count($args) < 1) {
                throw new \InvalidArgumentException('Invalid arguments for the view method, at least one parameter is required.');
            }

            if ($args[0] instanceof View) {
                $view = $args[0];
                $status = $args[1] ?? Response::HTTP_OK;
            } elseif (is_string($args[0]) && count($args) == 2) {
                echo 'Si el primer argumento es una cadena, asumimos que es el nombre de la vista<br>';
                $name = $args[0];
                $data = $args[1] ?? [];
                $view = new View($name, $data);
                $status = $args[2] ?? Response::HTTP_OK;
            } else if (is_string($args[0]) && count($args) == 1) {
                $data = $args[0];
                $this->status = Response::HTTP_BAD_REQUEST;
                $this->content = $data;
                return $this;
            } else {
                throw new \InvalidArgumentException('Invalid arguments for the view method.');
            }
            $this->content = $view->render();
            $this->status = $status;
        } catch (\InvalidArgumentException $e) {
            $this->error = true;
            (new BaseController())->handleInvalidArgumentException($e);
        }

        return $this;
    }


    public function redirect()
    {
        return new Redirect($this);
    }

    public function json(array $data = [], int $status = Response::HTTP_OK): self
    {
        $this->headers = [];
        $this->error = false;
        $this->content = json_encode($data);
        $this->status = $status;
        $this->header('Content-Type', 'application/json');
        return $this;
    }

    public function download(string $path, ?string $name = null, array $headers = []): self
    {
        $this->error = false;
        // Si no se proporciona un nombre, usa el nombre del archivo en la ruta
        $name = $name ?? basename($path);

        // Configura los encabezados necesarios para la descarga
        $this->header('Content-Type', 'application/octet-stream');
        $this->header('Content-Disposition', 'attachment; filename="' . $name . '"');
        $this->header('Content-Transfer-Encoding', 'binary');
        $this->header('Expires', '0');
        $this->header('Cache-Control', 'must-revalidate, post-check=0, pre-check=0');
        $this->header('Pragma', 'public');

        // Agrega los encabezados personalizados proporcionados
        $this->withHeaders($headers);

        // Lee el contenido del archivo y establece el contenido de la respuesta
        $this->content = file_get_contents($path);

        // Establece el código de estado de la respuesta
        $this->status = self::HTTP_OK;

        return $this;
    }

    public function file(string $path, array $headers = []): self
    {
        $this->error = false;
        // Verificar si el archivo existe
        if (!file_exists($path)) {
            throw new \InvalidArgumentException('File not found');
        }

        // Configurar los encabezados
        foreach ($headers as $name => $value) {
            $this->header($name, $value);
        }

        // Establecer el tipo de contenido basado en la extensión del archivo
        $this->header('Content-Type', mime_content_type($path));

        // Leer el contenido del archivo
        $this->content = file_get_contents($path);

        // Establecer el código de estado de la respuesta
        $this->status = self::HTTP_OK;

        return $this;
    }


    public function send()
    {

        if (!$this->error) {
            if ($this->cookie) {
                $this->cookie->send();
            }
            // if (!headers_sent()) {
            foreach ($this->headers as $name => $value) {
                header("$name: $value");
            }
            // echo "Los headers ya han sido enviados.<br>";
            // }
            // Configurar los encabezados de la respuesta


            // Establecer el código de estado de la respuesta
            http_response_code($this->status);

            echo $this->content;
        }
    }
}
