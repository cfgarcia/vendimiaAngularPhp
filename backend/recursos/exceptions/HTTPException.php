<?php
namespace vendimia\Exceptions;

use vendimia\Responses\JSONResponse;

class HTTPException extends \Exception
{
    public $response;
    public $errorCode;
    public $devMessage;
    public $additionalInfo;

    public function __construct($message, $code, $errorArray)
    {

        $this->message = $message;
        $this->Message = array_key_exists('dev', $errorArray) ? $errorArray['dev'] : null;
        $this->additionalInfo = array_key_exists('more', $errorArray) ? $errorArray['more'] : null;
        //$this->from = array_key_exists('from', $errorArray) ? $errorArray['from'] : null;
      //  $this->titulo = array_key_exists('titulo', $errorArray) ? $errorArray['titulo'] : null;
      //  $this->severidad = array_key_exists('severidad', $errorArray) ? $errorArray['severidad'] : null;
        $this->code = $code;
        $this->response = $this->getResponseDescription($code);

    }

    public function send()
    {
        $di = \Phalcon\DI::getDefault();
        $response = $di->get('response');
        $request = $di->get('request');

        if (!$request->get('suppress_response_codes', null, null)) {
            $response->setStatusCode($this->getCode(), $this->response)->sendHeaders();
        } else {
            $response->setStatusCode('200', 'OK')->sendHeaders();
        }

        $error = [
            'errorCode' => $this->getCode(),
            'userMessage' => $this->getMessage()
        ];

        if (getenv('PHP_ENV') != 'production') {
            $error['Message'] = $this->Message;
            $error['more'] = $this->additionalInfo;
            $error['file'] = $this->getFile();
            $error['line'] = $this->getLine();
            //$error['from'] = $this->from;
            //$error['titulo'] = $this->titulo;
            //$error['severidad'] = $this->severidad;
        }

        if (!$request->get('type') || $request->get('type') == 'json') {
            $httpResponse = new \vendimia\Responses\JSONResponse();
            $httpResponse->send($error, true);

            return;
        } else if ($request->get('type') == 'csv') {
            // Something needs to be here here...
        }

        error_log('HTTPException: ' . $this->getFile() . ' at ' . $this->getLine());

        return true;
    }

    protected function getResponseDescription($code)
    {
        $codes = array(

            // Informational 1xx
            100 => 'Continue',
            101 => 'Switching Protocols',

            // Success 2xx
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',

            // Redirection 3xx
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Found',  // 1.1
            303 => 'See Other',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            // 306 is deprecated but reserved
            307 => 'Temporary Redirect',

            // Client Error 4xx
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
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Long',
            415 => 'Unsupported Media Type',
            416 => 'Requested Range Not Satisfiable',
            417 => 'Expectation Failed',

            // Server Error 5xx
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
            505 => 'HTTP Version Not Supported',
            509 => 'Bandwidth Limit Exceeded'
        );

        $result = (isset($codes[$code])) ? $codes[$code] : 'Unknown Status Code';

        return $result;
    }
}
