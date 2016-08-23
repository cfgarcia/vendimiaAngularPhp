<?php
namespace vendimia\Models;
use Phalcon\Mvc\Model as Modelo;

class configuracionModel extends Modelo
{
  private $id_conf;
  private $tasa_fin;
  private $por_enganche;
  private $plazo_max;

  public function setIdConfig($idConf) {
    $this -> id_conf = $idConf;
  }

  public function setTasaFin($tasaFin) {
    $this -> tasa_fin = $tasaFin;
  }

  public function setPorEnganche($porEnganche) {
    $this -> por_enganche = $porEnganche;
  }

  public function setPlazoMax($plazoMax) {
    $this -> plazo_max = $plazoMax;
  }

  public function consultarConfiguarion () {
    $response =array();
    $di = \Phalcon\DI::getDefault();
    $db = $di->get("conexion");
    $statement = $db -> prepare("SELECT id_conf,tasa_fin,por_enganche,plazo_max FROM cat_configuracion ORDER BY id_conf DESC limit 1;");
    $statement -> execute();
    $response = $statement -> fetch(\PDO::FETCH_ASSOC);
    return $response;
  }

  public function consultarConfiguracionPorId () {
    $response =array();
    $di = \Phalcon\DI::getDefault();
    $db = $di->get("conexion");
    $statement = $db -> prepare("SELECT id_conf,tasa_fin,por_enganche,plazo_max FROM cat_configuracion where id_conf = ?;");
    $statement->bindParam(1,$this->id_conf,\PDO::PARAM_INT);
    $statement -> execute();
    $response = $statement -> fetchAll(\PDO::FETCH_ASSOC);
    return $response;
  }

  public function agregarConfiguracion() {
    $statement =false;
    $di = \Phalcon\DI::getDefault();
    $db = $di->get("conexion");
    $statement = $db -> prepare("INSERT INTO cat_configuracion(tasa_fin,por_enganche,plazo_max) VALUES(:tasaFin,:porEnganche,:plazoMax);");
    $statement->bindParam(':tasaFin',$this->tasa_fin,\PDO::PARAM_INT);
    $statement->bindParam(':porEnganche',$this->por_enganche,\PDO::PARAM_INT);
    $statement->bindParam(':plazoMax',$this->plazo_max,\PDO::PARAM_INT);
    return $statement -> execute();
  }

  public function actualizarConfiguracion() {
    $statement =false;
    $di = \Phalcon\DI::getDefault();
    $db = $di->get("conexion");
    $statement = $db->prepare("UPDATE cat_configuracion SET tasa_fin = :tasaFin,por_enganche=:porEnganche,plazo_max = :plazoMax WHERE id_conf = :idConf;");
    $statement->bindParam(':tasaFin',$this->tasa_fin,\PDO::PARAM_INT);
    $statement->bindParam(':porEnganche',$this->por_enganche,\PDO::PARAM_INT);
    $statement->bindParam(':plazoMax',$this->plazo_max,\PDO::PARAM_INT);
    $statement->bindParam(':idConf',$this->id_conf,\PDO::PARAM_INT);
    return $statement -> execute();
  }
}
