<?php
namespace vendimia\Models;
use Phalcon\Mvc\Model as Modelo;
class vendimiaModel extends Modelo
{
  public function consultarCliente () {
    $response =array();

    $di = \Phalcon\DI::getDefault();

    $db = $di->get("conexion");

    $statement = $db -> prepare("SELECT * FROM cat_clientes;");
    $statement -> execute();
    $response = $statement -> fetchAll(\PDO::FETCH_ASSOC);
    
    return $response;
  }
}
