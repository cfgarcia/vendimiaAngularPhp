<?php
namespace vendimia\Models;
use Phalcon\Mvc\Model as Modelo;
class clientesModel extends Modelo
{
  private $id_cliente;
  private $nom_cliente;
  private $apellido_paterno;
  private $apellido_materno;
  private $rfc;

  public function setClienteID($id) {
    $this->id_cliente = $id;
  }

  public function setNombreCliente($nomCliente) {
    $this->nom_cliente = $nomCliente;
  }

  public function setApellidoPaterno($apellidoP) {
    $this->apellido_paterno = $apellidoP;
  }

  public function setApellidoMaterno($apellidoM) {
    $this->apellido_materno = $apellidoM;
  }
  public function setRfc($rfc) {
    $this->rfc = $rfc;
  }

  public function consultarCliente () {
    $response =array();
    $di = \Phalcon\DI::getDefault();
    $db = $di->get("conexion");
    $statement = $db -> prepare("SELECT id_cliente,nom_cliente,apellido_paterno,apellido_materno,rfc FROM cat_clientes;");
    $statement -> execute();
    $response = $statement -> fetchAll(\PDO::FETCH_ASSOC);
    return $response;
  }

  public function consultarClientePorId () {
    $response =array();
    $di = \Phalcon\DI::getDefault();
    $db = $di->get("conexion");
    $statement = $db -> prepare("SELECT id_cliente,nom_cliente,apellido_paterno,apellido_materno,rfc FROM cat_clientes WHERE id_cliente = ?;");
    $statement->bindParam(1,$this->id_cliente,\PDO::PARAM_INT);
    $statement -> execute();
    $response = $statement -> fetchAll(\PDO::FETCH_ASSOC);
    return $response;
  }

  public function consultarClientePorNombre () {
    $response =array();
    $di = \Phalcon\DI::getDefault();
    $db = $di->get("conexion");
    $statement = $db -> prepare("SELECT id_cliente,nom_cliente,apellido_paterno,apellido_materno,rfc FROM cat_clientes WHERE nom_cliente = ?;");
    $statement->bindParam(1,$this->nom_cliente,\PDO::PARAM_INT);
    $statement -> execute();
    $response = $statement -> fetchAll(\PDO::FETCH_ASSOC);
    return $response;
  }

  public function agregarCliente() {
    $statement =false;
    $di = \Phalcon\DI::getDefault();
    $db = $di->get("conexion");
    $statement = $db -> prepare("INSERT INTO cat_clientes(nom_cliente,apellido_paterno,apellido_materno,rfc) VALUES(:nomCliente,:apellidoP,:apellidoM,:rfc);");
    $statement->bindParam(':nomCliente',$this->nom_cliente);
    $statement->bindParam(':apellidoP',$this->apellido_paterno);
    $statement->bindParam(':apellidoM',$this->apellido_materno);
    $statement->bindParam(':rfc',$this->rfc);
    return $statement -> execute();
  }

  public function actualizarCliente() {
    $statement =false;
    $di = \Phalcon\DI::getDefault();
    $db = $di->get("conexion");
    $statement = $db->prepare("UPDATE cat_clientes SET nom_cliente=:nomCliente,apellido_paterno=:apellidoP,apellido_materno=:apellidoM,rfc=:rfc WHERE id_cliente = :idCliente");
    $statement->bindParam(':nomCliente',$this->nom_cliente);
    $statement->bindParam(':apellidoP',$this->apellido_paterno);
    $statement->bindParam(':apellidoM',$this->apellido_materno);
    $statement->bindParam(':rfc',$this->rfc);
    $statement->bindParam(':idCliente',$this->id_cliente);
    return $statement -> execute();
  }

  public function eliminarCliente() {
    $statement =false;
    $di = \Phalcon\DI::getDefault();
    $db = $di->get("conexion");
    $statement = $db->prepare("DELETE FROM cat_clientes WHERE id_cliente = ?");
    $statement->bindParam(1,$this->id_cliente);
    return $statement -> execute();
  }
}
