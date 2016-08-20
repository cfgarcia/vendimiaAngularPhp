<?php

use Phalcon\Events\Manager as EventsManager;
use Phalcon\Logger;
use Phalcon\Logger\Adapter\File as FileLogger;

return ["db" => function() {
          $di = Phalcon\DI::getDefault();

          $di->set('conexion', function() use ($di) {
            $config = $di->get('config');

            $host = $config->db->host;
            $dbname = $config->db->dbname;
            return new \PDO("mysql:host=$host;dbname=$dbname",
               $config->db->username,
               $config->db->password,
               array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
              );
          });

        }
];
