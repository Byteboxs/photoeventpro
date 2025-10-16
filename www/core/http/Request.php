<?php

namespace app\core\http;

use app\core\SecureSession;
use app\core\Session;
use app\core\Str;

class Request
{
    public const HEADER_FORWARDED = 0b000001; // When using RFC 7239
    public const HEADER_X_FORWARDED_FOR = 0b000010;
    public const HEADER_X_FORWARDED_HOST = 0b000100;
    public const HEADER_X_FORWARDED_PROTO = 0b001000;
    public const HEADER_X_FORWARDED_PORT = 0b010000;
    public const HEADER_X_FORWARDED_PREFIX = 0b100000;

    public const HEADER_X_FORWARDED_AWS_ELB = 0b0011010; // AWS ELB doesn't send X-Forwarded-Host
    public const HEADER_X_FORWARDED_TRAEFIK = 0b0111110; // All "X-Forwarded-*" headers sent by Traefik reverse proxy

    public const METHOD_HEAD = 'HEAD';
    public const METHOD_GET = 'GET';
    public const METHOD_POST = 'POST';
    public const METHOD_PUT = 'PUT';
    public const METHOD_PATCH = 'PATCH';
    public const METHOD_DELETE = 'DELETE';
    public const METHOD_PURGE = 'PURGE';
    public const METHOD_OPTIONS = 'OPTIONS';
    public const METHOD_TRACE = 'TRACE';
    public const METHOD_CONNECT = 'CONNECT';
    private $query = [];
    private $cookies = [];
    private $data = [];
    private $path;
    private $queryString;
    private $host;
    private $scheme;
    private $method;
    private static $trustedHosts = [];
    private static int $trustedHeaderSet = -1;
    protected ?array $acceptableContentTypes = null;
    private bool $isForwardedValid = true;
    private const TRUSTED_HEADERS = [
        self::HEADER_FORWARDED => 'FORWARDED',
        self::HEADER_X_FORWARDED_FOR => 'X_FORWARDED_FOR',
        self::HEADER_X_FORWARDED_HOST => 'X_FORWARDED_HOST',
        self::HEADER_X_FORWARDED_PROTO => 'X_FORWARDED_PROTO',
        self::HEADER_X_FORWARDED_PORT => 'X_FORWARDED_PORT',
        self::HEADER_X_FORWARDED_PREFIX => 'X_FORWARDED_PREFIX',
    ];
    private const FORWARDED_PARAMS = [
        self::HEADER_X_FORWARDED_FOR => 'for',
        self::HEADER_X_FORWARDED_HOST => 'host',
        self::HEADER_X_FORWARDED_PROTO => 'proto',
        self::HEADER_X_FORWARDED_PORT => 'host',
    ];
    protected static array $trustedProxies = [];
    private static $trustedHostPatterns = [];
    protected static bool $httpMethodParameterOverride = false;
    protected array $parameters;
    private array $trustedValuesCache = [];

    public function __construct()
    {
        $this->init();
    }

    /**
     * This is an initialization method in a PHP class, likely a Request class, 
     * that sets various properties based on the current HTTP request.
     * @return void
     */
    private function init()
    {
        $this->query = $_GET;
        $this->data = $this->getData();
        $this->path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->queryString = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
        $this->host = $_SERVER['HTTP_HOST'];
        $this->scheme = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http';
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->cookies = $_COOKIE;
    }

    /**
     * This is a getter method in a PHP class that returns the value of the 
     * $path property, likely representing the path of the current HTTP request.
     * @return array|bool|int|string
     */
    public function path()
    {
        return $this->path;
    }

    function removeAppDirectoryFromUri($uri, $appDirectory)
    {
        if ($appDirectory === '/') {
            // Si el directorio de la aplicación es la raíz, devolvemos la URI original
            return $uri;
        }

        // Asegurarnos de que el directorio de la aplicación esté al inicio de la URI
        if (strpos($uri, $appDirectory) !== 0) {
            return $uri; // Si no coincide, devolvemos la URI original
        }

        // Eliminar el directorio de la aplicación de la URI
        return substr($uri, strlen($appDirectory));
    }
    /**
     * This method checks if the current request path matches a given pattern. 
     * It uses the Str::is method
     * @param mixed $expresion
     * @return bool
     */
    function is($expresion)
    {
        return Str::is($expresion, $this->path());
    }

    /**
     * This PHP method, host(), returns the trusted host of the current HTTP request.
     * @return string
     */
    public function host(): string
    {
        $host = $this->getTrustedHostFromHeaders();

        // Trim and remove port number from host
        $host = strtolower(preg_replace('/:\d+$/', '', trim($host)));

        // Validate the host
        $this->validateHost($host);

        return $host;
    }
    /**
     * This PHP method, httpHost, returns the HTTP host of the current request, including the port number if it's not the default port for the scheme (80 for HTTP, 443 for HTTPS).
     * @return string
     */
    public function httpHost(): string
    {
        $scheme = $this->scheme();
        $port = $this->port();

        if (('http' === $scheme && 80 == $port) || ('https' === $scheme && 443 == $port)) {
            return $this->host();
        }

        return $this->host() . ':' . $port;
    }
    /**
     * This PHP method, scheme(), returns the scheme of the current HTTP request, either 'https' or 'http', based on whether the request is secure (isSecure() returns true) or not.Summary of scheme
     * @return string
     */
    private function scheme(): string
    {
        return $this->isSecure() ? 'https' : 'http';
    }
    /**
     * This code snippet is a PHP method named isSecure() that returns a boolean value. It checks if the current HTTP request is secure (HTTPS) or not.
     * @return bool
     */
    public function isSecure(): bool
    {
        // Check if the request is from a trusted proxy
        if ($this->isFromTrustedProxy() && $proto = $this->getTrustedValues(self::HEADER_X_FORWARDED_FOR)) {
            return in_array(strtolower($proto[0]), ['https', 'on', 'ssl', '1'], true);
        }

        $https = isset($_SERVER['HTTPS']) ? $_SERVER['HTTPS'] : null;

        return !empty($https) && 'off' !== strtolower($https);
    }
    /**
     * This PHP method, port(), returns the port number of the current HTTP request. It first tries to extract the port from the host string in the request headers. If that fails, it falls back to the SERVER_PORT server variable. If that's not set, it returns the default port for the scheme (443 for HTTPS, 80 for HTTP).
     * @return int|string|null
     */
    public function port(): int|string|null
    {
        $host = $this->getTrustedHostFromHeaders();

        if ('[' === $host[0]) {
            $pos = strpos($host, ':', strrpos($host, ']'));
        } else {
            $pos = strrpos($host, ':');
        }

        if (false !== $pos && $port = substr($host, $pos + 1)) {
            return (int) $port;
        }

        return isset($_SERVER['SERVER_PORT']) ? (int) $_SERVER['SERVER_PORT'] : ('https' === $this->scheme() ? 443 : 80);
    }
    /**
     * This PHP method, getTrustedHostFromHeaders, returns the trusted host of the current HTTP request.
     * @return string
     */
    private function getTrustedHostFromHeaders(): string
    {
        $host = '';

        // Check if the request is from a trusted proxy
        if ($this->isFromTrustedProxy() && $trustedValues = $this->getTrustedValues(self::HEADER_X_FORWARDED_FOR)) {
            $host = $trustedValues[0];
        } elseif (isset($_SERVER['HTTP_HOST'])) {
            $host = $_SERVER['HTTP_HOST'];
        } elseif (isset($_SERVER['SERVER_NAME'])) {
            $host = $_SERVER['SERVER_NAME'];
        } elseif (isset($_SERVER['SERVER_ADDR'])) {
            $host = $_SERVER['SERVER_ADDR'];
        }

        return $host;
    }
    /**
     * This PHP method, isFromTrustedProxy, checks if the request is from a trusted proxy.
     * @return bool
     */
    private function isFromTrustedProxy(): bool
    {
        // Implement your logic to determine if the request is from a trusted proxy
        // For simplicity, we assume it's always true in this example
        return true;
    }

    // private function getTrustedValues(string $header): array
    // {
    //     if (function_exists('getallheaders')) {
    //         $allHeaders = getallheaders();
    //         return isset($allHeaders[$header]) ? explode(',', $allHeaders[$header]) : [];
    //     }

    //     // Fallback for environments where getallheaders() is not available
    //     return isset($_SERVER[$header]) ? explode(',', $_SERVER[$header]) : [];
    // }
    /**
     * his PHP method, getTrustedValues, retrieves trusted values from HTTP headers. It checks for the presence of specific headers (X-Forwarded-For, X-Forwarded-Port, etc.) and extracts their values. The method also handles cases where the headers are not present or are invalid. It uses caching to store the results and throws an exception if there are conflicting headers. The method is part of a class that handles HTTP requests and is used to determine the trusted IP address of the client.
     * @param int $type
     * @param string $ip
     * @throws \Exception
     * @return array
     */
    private function getTrustedValues(int $type, string $ip = null): array
    {
        $headers = function_exists('getallheaders') ? getallheaders() : [];

        $cacheKey = $type . "\0" . ((self::$trustedHeaderSet & $type) ? ($headers[self::TRUSTED_HEADERS[$type]] ?? '') : '');
        $cacheKey .= "\0" . $ip . "\0" . ($headers[self::TRUSTED_HEADERS[self::HEADER_FORWARDED]] ?? '');

        if (isset($this->trustedValuesCache[$cacheKey])) {
            return $this->trustedValuesCache[$cacheKey];
        }

        $clientValues = [];
        $forwardedValues = [];

        if ((self::$trustedHeaderSet & $type) && isset($headers[self::TRUSTED_HEADERS[$type]])) {
            foreach (explode(',', $headers[self::TRUSTED_HEADERS[$type]]) as $v) {
                $clientValues[] = (self::HEADER_X_FORWARDED_PORT === $type ? '0.0.0.0:' : '') . trim($v);
            }
        }

        if ((self::$trustedHeaderSet & self::HEADER_FORWARDED) && (isset(self::FORWARDED_PARAMS[$type])) && isset($headers[self::TRUSTED_HEADERS[self::HEADER_FORWARDED]])) {
            $forwarded = $headers[self::TRUSTED_HEADERS[self::HEADER_FORWARDED]];
            $parts = HeaderUtils::split($forwarded, ',;=');
            $param = self::FORWARDED_PARAMS[$type];
            foreach ($parts as $subParts) {
                if (null === $v = HeaderUtils::combine($subParts)[$param] ?? null) {
                    continue;
                }
                if (self::HEADER_X_FORWARDED_PORT === $type) {
                    if (str_ends_with($v, ']') || false === $v = strrchr($v, ':')) {
                        $v = $this->isSecure() ? ':443' : ':80';
                    }
                    $v = '0.0.0.0' . $v;
                }
                $forwardedValues[] = $v;
            }
        }

        if (null !== $ip) {
            $clientValues = $this->normalizeAndFilterClientIps($clientValues, $ip);
            $forwardedValues = $this->normalizeAndFilterClientIps($forwardedValues, $ip);
        }

        if ($forwardedValues === $clientValues || !$clientValues) {
            return $this->trustedValuesCache[$cacheKey] = $forwardedValues;
        }

        if (!$forwardedValues) {
            return $this->trustedValuesCache[$cacheKey] = $clientValues;
        }

        if (!$this->isForwardedValid) {
            return $this->trustedValuesCache[$cacheKey] = null !== $ip ? ['0.0.0.0', $ip] : [];
        }
        $this->isForwardedValid = false;

        throw new \Exception(sprintf('The request has both a trusted "%s" header and a trusted "%s" header, conflicting with each other. You should either configure your proxy to remove one of them, or configure your project to distrust the offending one.', self::TRUSTED_HEADERS[self::HEADER_FORWARDED], self::TRUSTED_HEADERS[$type]));
    }

    /**
     * This PHP function normalizeAndFilterClientIps takes an array of client IPs and a single IP address as input.
     * @param array $clientIps
     * @param string $ip
     * @return array
     */
    private function normalizeAndFilterClientIps(array $clientIps, string $ip): array
    {
        if (!$clientIps) {
            return [];
        }
        $clientIps[] = $ip; // Complete the IP chain with the IP the request actually came from
        $firstTrustedIp = null;

        foreach ($clientIps as $key => $clientIp) {
            if (strpos($clientIp, '.')) {
                // Strip :port from IPv4 addresses. This is allowed in Forwarded
                // and may occur in X-Forwarded-For.
                $i = strpos($clientIp, ':');
                if ($i) {
                    $clientIps[$key] = $clientIp = substr($clientIp, 0, $i);
                }
            } elseif (str_starts_with($clientIp, '[')) {
                // Strip brackets and :port from IPv6 addresses.
                $i = strpos($clientIp, ']', 1);
                $clientIps[$key] = $clientIp = substr($clientIp, 1, $i - 1);
            }

            if (!filter_var($clientIp, \FILTER_VALIDATE_IP)) {
                unset($clientIps[$key]);

                continue;
            }

            if (IpUtils::checkIp($clientIp, self::$trustedProxies)) {
                unset($clientIps[$key]);

                // Fallback to this when the client IP falls into the range of trusted proxies
                $firstTrustedIp ??= $clientIp;
            }
        }

        // Now the IP chain contains only untrusted proxies and the client IP
        return $clientIps ? array_reverse($clientIps) : [$firstTrustedIp];
    }

    /**
     * This PHP function validateHost checks if a given host is valid and trusted.
     * @param string $host
     * @throws \Exception
     * @return void
     */
    private function validateHost(string $host): void
    {
        if (!$host || '' !== preg_replace('/(?:^\[)?[a-zA-Z0-9-:\]_]+\.?/', '', $host)) {
            throw new \Exception(sprintf('Invalid Host "%s".', $host));
        }

        if (\count(self::$trustedHostPatterns) > 0) {
            if (\in_array($host, self::$trustedHosts)) {
                return;
            }

            foreach (self::$trustedHostPatterns as $pattern) {
                if (preg_match($pattern, $host)) {
                    self::$trustedHosts[] = $host;
                    return;
                }
            }

            throw new \Exception(sprintf('Untrusted Host "%s".', $host));
        }
    }

    /**
     * This PHP method, schemeAndHttpHost, returns a string representing the scheme (e.g., "http" or "https") and HTTP host (e.g., "example.com") of a request, concatenated with "://". It's essentially building the base URL of the request.
     * @return string
     */
    public function schemeAndHttpHost()
    {
        return $this->scheme() . '://' . $this->httpHost();
    }

    /**
     * This PHP method, getAcceptableContentTypes, returns an array of acceptable content types from the 'Accept' header of an HTTP request.
     * @return array
     */
    public function getAcceptableContentTypes(): array
    {
        // return $this->acceptableContentTypes = $this->header('Accept');
        $out =  $this->acceptableContentTypes ??= array_map(
            'strval',
            array_keys(AcceptHeader::fromString(
                // $this->headers->get('Accept')
                $this->header('Accept')
            )->all())
        );
        return $out;
    }

    /**
     * This PHP method, getMethod, determines the HTTP request method of the current request.
     * @throws \Exception
     * @return string
     */
    public function getMethod(): string
    {
        if (null !== $this->method) {
            return $this->method;
        }

        $this->method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
        //ANOTACION 2
        // echo 'METODO DEL SERVIDOR: ';
        // var_dump($this->method);

        if ('POST' !== $this->method) {
            return $this->method;
        }

        $method = $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'] ?? '';

        if (!$method && self::$httpMethodParameterOverride) {
            $method = $_REQUEST['_method'] ?? 'POST';
        }

        if (!is_string($method)) {
            return $this->method;
        }

        $method = strtoupper($method);

        if (in_array($method, ['GET', 'HEAD', 'POST', 'PUT', 'DELETE', 'CONNECT', 'OPTIONS', 'PATCH', 'PURGE', 'TRACE'], true)) {
            return $this->method = $method;
        }

        if (!preg_match('/^[A-Z]++$/D', $method)) {
            throw new \Exception(sprintf('Invalid method override "%s".', $method));
        }

        return $this->method = $method;
    }

    /**
     * This PHP method, getRealMethod, returns the actual HTTP request method (e.g., GET, POST, PUT, etc.) as an uppercase string. If the REQUEST_METHOD server variable is not set, it defaults to 'GET'.
     * @return string
     */
    public function getRealMethod(): string
    {
        return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
    }

    /**
     * This function retrieves a specific HTTP header value by its key. If the key is not found, it returns the provided default value.
     * @param mixed $key
     * @param mixed $default
     * @return mixed
     */
    public function header($key, $default = null)
    {
        $headers = $this->getAllHeaders();
        return $headers[$key] ?? $default;
    }

    /**
     * Checks if a specific HTTP header exists in the request. It returns true if the header is present, and false otherwise.
     * @param mixed $key
     * @return bool
     */
    public function hasHeader($key)
    {
        $headers = $this->getAllHeaders();
        return isset($headers[$key]);
    }

    /**
     * This method retrieves the value of the Authorization header from the HTTP request. If the header exists and starts with the string "Bearer ", it returns the substring of the header value starting from the 7th character. Otherwise, it returns an empty string.
     * @return string
     */
    public function bearerToken()
    {
        $authorizationHeader = $this->header('Authorization');
        if ($authorizationHeader && strpos($authorizationHeader, 'Bearer ') === 0) {
            return substr($authorizationHeader, 7);
        }
        return '';
    }

    /**
     * 
     * @return array
     */
    public function getAllHeaders()
    {
        if (function_exists('getallheaders')) {
            return getallheaders();
        }

        // Fallback for environments where getallheaders() is not available
        $headers = [];
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }

    /**
     * Check if the given content types are accepted.
     *
     * @param array<string> $contentTypes The content types to check.
     * @return bool True if at least one of the content types is accepted, false otherwise.
     */
    public function accepts(array $contentTypes): bool
    {
        // $acceptableContentTypes = $this->getAcceptableContentTypes();

        // if (is_array($contentTypes) && count($contentTypes) > 0) {
        //     foreach ($contentTypes as $contentType) {
        //         $match = in_array($contentType, $acceptableContentTypes, true);
        //         if ($match) {
        //             return true;
        //         }
        //     }
        // }
        // return false;
        $acceptableContentTypes = $this->getAcceptableContentTypes();

        return !empty(array_intersect($contentTypes, $acceptableContentTypes));
    }

    public function expectsJson()
    {
        return $this->accepts(['application/json']);
    }

    public function url()
    {
        return $this->path;
    }

    /**
     * Retrieves data from the request.
     *
     * @return array<string,string> The sanitized data.
     */
    public function getData(): array
    {
        $data = [];
        if ($this->method() === 'GET') {
            foreach ($_GET as $key => $value) {
                $data[$key] = isset($_GET[$key]) ? filter_var($_GET[$key], FILTER_SANITIZE_SPECIAL_CHARS) : null;
            }
        }

        if ($this->method() === 'POST') {
            //ANOTACION 1
            // echo 'METODO POST';
            foreach ($_POST as $key => $value) {
                $data[$key] = isset($_POST[$key]) ? filter_var($_POST[$key], FILTER_SANITIZE_SPECIAL_CHARS) : null;
            }
        }

        return $data;
    }

    public function cookie($key)
    {
        return isset($this->cookies[$key]) ? $this->cookies[$key] : null;
    }

    /**
     * Retrieves a value from the query array.
     *
     * @param string|null $key The key of the value to retrieve.
     * @param mixed|null $default The default value to return if the key is not found.
     *
     * @return mixed The value from the query array with the given key, or the default value if the key is not found.
     */
    public function query(?string $key = null, mixed $default = null): mixed
    {
        return $key === null ? $this->query : ($this->query[$key] ?? $default);
        // if ($key === null) {
        //     return $this->query;
        // }
        // return isset($this->query[$key]) ? $this->query[$key] : $default;
        // $numArgs = count($args);
        // if ($numArgs === 0) {
        //     return $this->query;
        // } else if ($numArgs === 2) {
        //     return $this->query[$args[0]] ?? $args[1];
        // } else if ($numArgs === 1) {
        //     return $this->query;
        // } else {
        //     throw new \Exception("Invalid number of arguments");
        // }
    }

    public function queryMap() {}

    public function all()
    {
        $this->data = $this->getData();
        return $this->data;
    }

    public function get($key, $default = null)
    {
        $this->data = $this->getData();
        return isset($this->data[$key]) ? $this->data[$key] : $default;
    }

    public function input(?string $key = null, $default = null): mixed
    {
        if ($key === null) {
            $this->data = $this->getData();
            return $this->data;
        }

        if (strpos($key, '.') !== false) {
            return $this->getValueWithDotNotation($key);
        }

        return $this->getValueWithSimpleKey($key, $default);
    }

    private function getValueWithDotNotation(string $key): mixed
    {
        $keys = explode('.', $key);
        $this->data = $this->getData();
        $value = $this->data;
        foreach ($keys as $innerKey) {
            if (isset($value[$innerKey])) {
                $value = $value[$innerKey];
            } else {
                return null;
            }
        }
        return $value;
    }

    private function getValueWithSimpleKey(string $key, mixed $default): mixed
    {
        return isset($this->data[$key]) ? $this->data[$key] : $default;
    }

    public function has($key)
    {
        return isset($this->data[$key]);
    }



    public function fullUrl()
    {

        $url = $this->path;
        if ($this->queryString) {
            $url .= '?' . $this->queryString;
        }
        return $url;
    }



    public function method()
    {
        return $this->method;
    }

    public function isMethod($method)
    {
        return strtoupper($method) === strtoupper($this->method);
    }

    public function ip(): ?string
    {
        $ipAddresses = $this->ips();

        return $ipAddresses[0];
    }

    public function ips(): array
    {
        $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null;

        if (!$this->isFromTrustedProxy()) {
            return [$ip];
        }

        return $this->getTrustedValues(self::HEADER_X_FORWARDED_FOR, $ip) ?: [$ip];
    }

    /**
     * 
     * @return Session
     */
    public function session()
    {
        return Session::getInstance();
    }
}
