<?php
namespace  vendimia\Responses;

/**
 *
 */
class Response extends \Phalcon\DI\Injectable
{

    function __construct()
    {
        $di = \Phalcon\DI::getDefault();
        $this->setDI($di);
    }
}
