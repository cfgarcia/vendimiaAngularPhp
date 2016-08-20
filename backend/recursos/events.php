<?php

use vendimia\Exceptions\HTTPException;
use Phalcon\Logger;
use Phalcon\Logger\Adapter\File as FileLogger;

return [
    'db' => function ($event, $connection) {
        $logger = new FileLogger('logs/debug.log');

        if ($event->getType() == 'beforeQuery') {
            $logger->log($connection->getSQLStatement(), Logger::INFO);
        }
    },
    'exceptionHandler' => function ($exception) {
        if ($exception instanceof vendimia\Exceptions\HTTPException) {
            $exception->send();
        }

        error_log($exception);
        error_log($exception->getTraceAsString());
    },
    'micro:beforeNotFound' => function ($event, $app) {
        throw new \vendimia\Exceptions\HTTPException(
            'Not found',
            404,
            array(
                'dev' => 'La ruta no fue encontrada en el servidor',
                'internalCode' => 'NF1000',
                'more' => 'Verifique que la URI esté bien escrita',
            )
        );
    },
    'micro:afterHandleRoute' => function ($event, $app) {
        if (!$app->request->get('type') || $app->request->get('type') == 'json') {
            $records = $app->getReturnedValue();

            if ($records != null) {
                $response = new vendimia\Responses\JSONResponse();
                $response->useEnvelope(true)->send($records);
            }

            return;
        }

        return;
    }
];
