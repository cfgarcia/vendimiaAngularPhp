<?php
namespace vendimia\Models;
use Phalcon\Mvc\Model as Modelo;

class ventasModel extends Modelo
{
  private $id_venta;
  private $id_cliente;
  private $total_venta;
  private $fec_registrada;
  private $estatus;

  public function setIdVenta($idVenta) {
    $this->id_venta = $idVenta;
  }

  public function setIdCliente($idCliente) {
    $this->id_cliente = $idCliente;
  }

  public function setTotalVenta($totalVenta) {
    $this->total_venta = $totalVenta;
  }

  public function consultarVentas() {
    $response =array();
    $di = \Phalcon\DI::getDefault();
    $db = $di->get("conexion");
    $statement = $db -> prepare("SELECT cv.id_venta,cv.id_cliente,cc.nom_cliente,cv.total_venta,cv.fec_registrada,cv.estatus FROM cat_ventas as cv INNER JOIN cat_clientes as cc ON cc.id_cliente = cv.id_cliente;");
    $statement -> execute();
    $response = $statement -> fetchAll(\PDO::FETCH_ASSOC);
    return $response;
  }

  public function consultarVentasPorId () {
    $response =array();
    $di = \Phalcon\DI::getDefault();
    $db = $di->get("conexion");
    $statement = $db -> prepare("SELECT cv.id_venta,cv.id_cliente,cc.nom_cliente,cv.total_venta,cv.fec_registrada,cv.estatus FROM cat_ventas as cv INNER JOIN cat_clientes as cc ON cc.id_cliente = cv.id_cliente WHERE id_venta = ?;");
    $statement->bindParam(1,$this->id_venta,\PDO::PARAM_INT);
    $statement -> execute();
    $response = $statement -> fetchAll(\PDO::FETCH_ASSOC);
    return $response;
  }

  public function agregarVenta () {
    $statement =false;
    $di = \Phalcon\DI::getDefault();
    $db = $di->get("conexion");
    $statement = $db -> prepare("INSERT INTO cat_ventas(id_cliente,total_venta) VALUES(:idCliente,:totalVenta)");
    $statement->bindParam(':idCliente',$this->id_cliente,\PDO::PARAM_INT);
    $statement->bindParam(':totalVenta',$this->total_venta,\PDO::PARAM_INT);
    return $statement -> execute();
  }

  public function actualizarVenta () {
    $statement =false;
    $di = \Phalcon\DI::getDefault();
    $db = $di->get("conexion");
    $statement = $db->prepare("UPDATE cat_ventas SET id_cliente = :idCliente,total_venta=:totalVenta WHERE id_venta = :idVenta;");
    $statement->bindParam(':idCliente',$this->id_cliente,\PDO::PARAM_INT);
    $statement->bindParam(':totalVenta',$this->total_venta,\PDO::PARAM_INT);
    $statement->bindParam(':idVenta',$this->id_venta,\PDO::PARAM_INT);
    return $statement -> execute();
  }

  public function eliminarVenta() {
    $statement =false;
    $di = \Phalcon\DI::getDefault();
    $db = $di->get("conexion");
    $statement = $db->prepare("DELETE FROM cat_ventas WHERE id_venta = ?");
    $statement->bindParam(1,$this->id_venta);
    return $statement -> execute();
  }
}
