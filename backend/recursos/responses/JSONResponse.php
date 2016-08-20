<?php
namespace vendimia\Responses;

/**
 *
 */
class JSONResponse extends Response
{
    protected $envelope = true;

    public function __construct()
    {
        parent::__construct();
    }

    public function send($data, $error = false)
    {
        $response = $this->di->get('response');
        $success = ($error) ? 'ERROR' : 'SUCCESS';

        $request = $this->di->get('request');

        if ($request->get('envelope', null, null) === 'false') {
            $this->envelope = false;
        }

        if (is_array($data)) {
            $eTag = md5(serialize($data));
        } else {
            $eTag = '';
        }

        if ($this->envelope) {

            $message = array();
            $message['meta'] = array(
                'status' => $success,
                'count' => ($error) ? 1 : count($data)
            );

            if ($message['meta']['count'] === 0) {
                $message['data'] = new \stdClass();
            } else {
                $message['data'] = $data;
            }
        } else {
            $response->setHeader('X-Record-Count', count($data));
            $response->setHeader('X-Status', $success);
            $message = $data;
        }

        if (isset($message['data']['mensajes'])) {
            $message['mensajes'] = $message['data']['mensajes'];
        }

        $response->setContentType('application/json');
        if ($success == 'ERROR') {
            $response->setStatusCode($message['data']['errorCode'], "");
            if(isset($message['data']['from'])) {
                if ($message['data']['from'] == 'DEV'){
                     $message['mensajes'] = [["mensaje" => $message['data']['devMessage'], "titulo" => "ERROR DE PROGRAMACIÓN", "severidad" => "error"]];
                } else {
                    $message['mensajes'] = [["mensaje" => $message['data']['message'], "titulo" => $message['data']['titulo'], "severidad" => $message['data']['severidad']]];
                }
            }
        }

        $response->setJsonContent($message);
        $response->send();

        return $this;
    }

    public function useEnvelope($envelope) {
        $this->envelope = (bool) $envelope;
        return $this;
    }
}
