<?php
 
namespace Kaliba\Network;
use InvalidArgumentException;
use Exception;

/**
 *
 * Core base class for network communication.
 * This class is derived from CakePHP 
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 */
class Socket implements SocketInterface
{


    /**
     * Object description
     *
     * @var string
     */
    public $description = 'Remote DataSource Network Socket Interface';

    /**
     * Default configuration settings for the socket connection
     *
     * @var array
     */
    protected $config = [
        'persistent' => false,
        'host' => 'localhost',
        'protocol' => 'tcp',
        'port' => 80,
        'timeout' => 30
    ];

    /**
     * Reference to socket connection resource
     *
     * @var resource
     */
    public $connection = null;

    /**
     * This boolean contains the current state of the Socket class
     *
     * @var bool
     */
    public $connected = false;

    /**
     * This variable contains an array with the last error number (num) and string (str)
     *
     * @var array
     */
    public $lastError = [];

    /**
     * True if the socket stream is encrypted after a Cake\Network\Socket::enableCrypto() call
     *
     * @var bool
     */
    public $encrypted = false;

    /**
     * Contains all the encryption methods available
     *
     * @var array
     */
    protected $encryptMethods = [
        // @codingStandardsIgnoreStart
        'sslv2_client' => STREAM_CRYPTO_METHOD_SSLv2_CLIENT,
        'sslv3_client' => STREAM_CRYPTO_METHOD_SSLv3_CLIENT,
        'sslv23_client' => STREAM_CRYPTO_METHOD_SSLv23_CLIENT,
        'tls_client' => STREAM_CRYPTO_METHOD_TLS_CLIENT,
        'sslv2_server' => STREAM_CRYPTO_METHOD_SSLv2_SERVER,
        'sslv3_server' => STREAM_CRYPTO_METHOD_SSLv3_SERVER,
        'sslv23_server' => STREAM_CRYPTO_METHOD_SSLv23_SERVER,
        'tls_server' => STREAM_CRYPTO_METHOD_TLS_SERVER
        // @codingStandardsIgnoreEnd
    ];

    /**
     * Used to capture connection warnings which can happen when there are
     * SSL errors for example.
     *
     * @var array
     */
    protected $connectionErrors = [];

    /**
     * Constructor.
     *
     * @param array $config Socket configuration, which will be merged with the base configuration
     */
    public function __construct(array $config = [])
    {
        $this->config  = array_merge($this->config, $config);
    }

    /**
     * Connect the socket to the given host and port.
     *
     * @return bool Success
     * @throws \Kaliba\Network\SocketException
     */
    public function connect()
    {
        if ($this->connection) {
            $this->disconnect();
        }

        $hasProtocol = strpos($this->config['host'], '://') !== false;
        if ($hasProtocol) {
            list($this->config['protocol'], $this->config['host']) = explode('://', $this->config['host']);
        }
        $scheme = null;
        if (!empty($this->config['protocol'])) {
            $scheme = $this->config['protocol'] . '://';
        }

        $this->setSSLContext($this->config['host']);
        if (!empty($this->config['context'])) {
            $context = stream_context_create($this->config['context']);
        } else {
            $context = stream_context_create();
        }

        $connectAs = STREAM_CLIENT_CONNECT;
        if ($this->config['persistent']) {
            $connectAs |= STREAM_CLIENT_PERSISTENT;
        }

        set_error_handler([$this, 'connectionErrorHandler']);
        $this->connection = stream_socket_client(
            $scheme . $this->config['host'] . ':' . $this->config['port'],
            $errNum,
            $errStr,
            $this->config['timeout'],
            $connectAs,
            $context
        );
        restore_error_handler();

        if (!empty($errNum) || !empty($errStr)) {
            $this->setLastError($errNum, $errStr);
            throw new SocketException($errStr, $errNum);
        }

        if (!$this->connection && $this->connectionErrors) {
            $message = implode("\n", $this->connectionErrors);
            throw new SocketException($message, E_WARNING);
        }

        $this->connected = is_resource($this->connection);
        if ($this->connected) {
            stream_set_timeout($this->connection, $this->config['timeout']);
        }
        return $this->connected;
    }

    /**
     * Configure the SSL context options.
     *
     * @param string $host The host name being connected to.
     * @return void
     */
    protected function setSSLContext($host)
    {
        foreach ($this->config as $key => $value) {
            if (substr($key, 0, 4) !== 'ssl_') {
                continue;
            }
            $contextKey = substr($key, 4);
            if (empty($this->config['context']['ssl'][$contextKey])) {
                $this->config['context']['ssl'][$contextKey] = $value;
            }
            unset($this->config[$key]);
        }
        if (!isset($this->config['context']['ssl']['SNI_enabled'])) {
            $this->config['context']['ssl']['SNI_enabled'] = true;
        }
        if (version_compare(PHP_VERSION, '5.6.0', '>=')) {
            if (empty($this->config['context']['ssl']['peer_name'])) {
                $this->config['context']['ssl']['peer_name'] = $host;
            }
        } else {
            if (empty($this->config['context']['ssl']['SNI_server_name'])) {
                $this->config['context']['ssl']['SNI_server_name'] = $host;
            }
        }
        if (empty($this->config['context']['ssl']['cafile'])) {
            $dir = dirname(dirname(__DIR__));
            $this->config['context']['ssl']['cafile'] = $dir . DIRECTORY_SEPARATOR .
                'config' . DIRECTORY_SEPARATOR . 'cacert.pem';
        }
        if (!empty($this->config['context']['ssl']['verify_host'])) {
            $this->config['context']['ssl']['CN_match'] = $host;
        }
        unset($this->config['context']['ssl']['verify_host']);
    }

    /**
     * socket_stream_client() does not populate errNum, or $errStr when there are
     * connection errors, as in the case of SSL verification failure.
     *
     * Instead we need to handle those errors manually.
     *
     * @param int $code Code number.
     * @param string $message Message.
     * @return void
     */
    protected function connectionErrorHandler($code, $message)
    {
        $this->connectionErrors[] = $message;
    }

    /**
     * Get the connection context.
     *
     * @return null|array Null when there is no connection, an array when there is.
     */
    public function context()
    {
        if (!$this->connection) {
            return null;
        }
        return stream_context_get_options($this->connection);
    }

    /**
     * Get the host name of the current connection.
     *
     * @return string Host name
     */
    public function host()
    {
        if ($this->ip($this->config['host'])) {
            return gethostbyaddr($this->config['host']);
        }
        return gethostbyaddr($this->address());
    }

    /**
     * Get the IP address of the current connection.
     *
     * @return string IP address
     */
    public function address()
    {
        if ($this->ip($this->config['host'])) {
            return $this->config['host'];
        }
        return gethostbyname($this->config['host']);
    }

    /**
     * Get all IP addresses associated with the current connection.
     *
     * @return array IP addresses
     */
    public function addresses()
    {
        if ($this->ip($this->config['host'])) {
            return [$this->config['host']];
        }
        return gethostbynamel($this->config['host']);
    }
    
    /**
     * Validation of an IP address.
     *
     * @param string $check The string to test.
     * @param string $type The IP Protocol version to validate against
     * @return bool Success
     */
    private  function ip($check, $type = 'both')
    {
        $type = strtolower($type);
        $flags = 0;
        if ($type === 'ipv4') {
            $flags = FILTER_FLAG_IPV4;
        }
        if ($type === 'ipv6') {
            $flags = FILTER_FLAG_IPV6;
        }
        return (bool)filter_var($check, FILTER_VALIDATE_IP, ['flags' => $flags]);
    }
    
    /**
     * Get the last error as a string.
     *
     * @return string|null Last error
     */
    public function lastError()
    {
        if (!empty($this->lastError)) {
            return $this->lastError['num'] . ': ' . $this->lastError['str'];
        }
        return null;
    }

    /**
     * Set the last error.
     *
     * @param int $errNum Error code
     * @param string $errStr Error string
     * @return void
     */
    public function setLastError($errNum, $errStr)
    {
        $this->lastError = ['num' => $errNum, 'str' => $errStr];
    }

    /**
     * Write data to the socket.
     *
     * @param string $data The data to write to the socket
     * @return bool Success
     */
    public function write($data)
    {
        if (!$this->connected) {
            if (!$this->connect()) {
                return false;
            }
        }
        $totalBytes = strlen($data);
        for ($written = 0, $rv = 0; $written < $totalBytes; $written += $rv) {
            $rv = fwrite($this->connection, substr($data, $written));
            if ($rv === false || $rv === 0) {
                return $written;
            }
        }
        return $written;
    }

    /**
     * Read data from the socket. Returns false if no data is available or no connection could be
     * established.
     *
     * @param int $length Optional buffer length to read; defaults to 1024
     * @return mixed Socket data
     */
    public function read($length = 1024)
    {
        if (!$this->connected) {
            if (!$this->connect()) {
                return false;
            }
        }

        if (!feof($this->connection)) {
            $buffer = fread($this->connection, $length);
            $info = stream_get_meta_data($this->connection);
            if ($info['timed_out']) {
                $this->setLastError(E_WARNING, 'Connection timed out');
                return false;
            }
            return $buffer;
        }
        return false;
    }

    /**
     * Disconnect the socket from the current connection.
     *
     * @return bool Success
     */
    public function disconnect()
    {
        if (!is_resource($this->connection)) {
            $this->connected = false;
            return true;
        }
        $this->connected = !fclose($this->connection);

        if (!$this->connected) {
            $this->connection = null;
        }
        return !$this->connected;
    }

    /**
     * Destructor, used to disconnect from current connection.
     */
    public function __destruct()
    {
        $this->disconnect();
    }

    /**
     * Resets the state of this Socket instance to it's initial state (before Object::__construct got executed)
     *
     * @param array $state Array with key and values to reset
     * @return bool True on success
     */
    public function reset($state = null)
    {
        if (empty($state)) {
            static $initalState = [];
            if (empty($initalState)) {
                $initalState = get_class_vars(__CLASS__);
            }
            $state = $initalState;
        }

        foreach ($state as $property => $value) {
            $this->{$property} = $value;
        }
        return true;
    }

    /**
     * Encrypts current stream socket, using one of the defined encryption methods
     *
     * @param string $type can be one of 'ssl2', 'ssl3', 'ssl23' or 'tls'
     * @param string $clientOrServer can be one of 'client', 'server'. Default is 'client'
     * @param bool $enable enable or disable encryption. Default is true (enable)
     * @return bool True on success
     * @throws \InvalidArgumentException When an invalid encryption scheme is chosen.
     * @throws \Kaliba\Network\SocketException When attempting to enable SSL/TLS fails
     * @see stream_socket_enable_crypto
     */
    public function enableCrypto($type, $clientOrServer = 'client', $enable = true)
    {
        if (!array_key_exists($type . '_' . $clientOrServer, $this->encryptMethods)) {
            throw new InvalidArgumentException('Invalid encryption scheme chosen');
        }
        try {
            $enableCryptoResult = stream_socket_enable_crypto($this->connection, $enable, $this->encryptMethods[$type . '_' . $clientOrServer]);
        } catch (Exception $e) {
            $this->setLastError(null, $e->getMessage());
            throw new SocketException($e->getMessage());
        }
        if ($enableCryptoResult === true) {
            $this->encrypted = $enable;
            return true;
        }
        $errorMessage = 'Unable to perform enableCrypto operation on the current socket';
        $this->setLastError(null, $errorMessage);
        throw new SocketException($errorMessage);
    }
}