<?php

use Phalcon\Events\Manager as EventsManager;
use Phalcon\Logger;
use Phalcon\Logger\Adapter\File as FileLogger;

/*
# Ejemplo de una conexión a MySql que utiliza la classe Logger para registrar
# las consultas ejecutadas
# 11/Sep/2015

"db" => function () {

    $di = \Phalcon\DI::getDefault();
    $config = $di->get('config');

    $eventsManager = new EventsManager();

    $logger = new FileLogger('logs/debug.log');

    $eventsManager->attach('db', function ($event, $connection) use ($logger) {
        if ($event->getType() == 'beforeQuery') {
            $logger->log($connection->getSQLStatement(), Logger::INFO);
        }
    });

    $connection = new \Phalcon\Db\Adapter\Pdo\Mysql(array(
        'host' => $config->db->host,
        'dbname' => $config->db->dbname,
        'username' => $config->db->username,
        'password' => $config->db->password
    ));

    $connection->setEventsManager($eventsManager);

    return $connection;
}
*/


return ["db" => function() {
          $di = Phalcon\DI::getDefault();

          $di->set('conexion', function() use ($di) {
            $config = $di->get('config');

            $host = $config->db->host;
            var_dump($config);
            $dbname = $config->db->dbname;
            return new \PDO("mysql:host=$host;dbname=$dbname",
               $config->db->username,
               $config->db->password,
               array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
              );
          });

        }
];
