<?php
namespace vendimia\Models;
use Phalcon\Mvc\Model as Modelo;

class articulosModel extends Modelo
{
  private $id_articulo;
  private $des_articulo;
  private $modelo_articulo;
  private $precio_articulo;
  private $exist_articulo;

  public function setArticuloId($articuloId) {
    $this->id_articulo =$articuloId;
  }

  public function setDesArticulo($desArticulo) {
    $this->des_articulo =$desArticulo;
  }

  public function setModeloArticulo($modeloArticulo) {
    $this->modelo_articulo =$modeloArticulo;
  }

  public function setPrecioArticulo($precioArticulo) {
    $this->precio_articulo =$precioArticulo;
  }

  public function setExistArticulo($existArticulo) {
    $this->exist_articulo =$existArticulo;
  }

  public function consultarArticulos () {
    $response =array();
    $di = \Phalcon\DI::getDefault();
    $db = $di->get("conexion");
    $statement = $db -> prepare("SELECT id_articulo,des_articulo,modelo_articulo,precio_articulo,exist_articulo FROM cat_articulos;");
    $statement -> execute();
    $response = $statement -> fetchAll(\PDO::FETCH_ASSOC);
    return $response;
  }

  public function consultarArticulosPorId () {
    $response =array();
    $di = \Phalcon\DI::getDefault();
    $db = $di->get("conexion");
    $statement = $db -> prepare("SELECT id_articulo,des_articulo,modelo_articulo,precio_articulo,exist_articulo FROM cat_articulos WHERE id_articulo = ?;");
    $statement->bindParam(1,$this->id_articulo,\PDO::PARAM_INT);
    $statement -> execute();
    $response = $statement -> fetchAll(\PDO::FETCH_ASSOC);
    return $response;
  }

  public function agregarArticulo() {
    $statement =false;
    $di = \Phalcon\DI::getDefault();
    $db = $di->get("conexion");
    $statement = $db -> prepare("INSERT INTO cat_articulos(des_articulo,modelo_articulo,precio_articulo,exist_articulo) VALUES(:desArticulo,:modeloArticulo,:precioArticulo,:existArticulo);");
    $statement->bindParam(':desArticulo',$this->des_articulo);
    $statement->bindParam(':modeloArticulo',$this->modelo_articulo);
    $statement->bindParam(':precioArticulo',$this->precio_articulo,\PDO::PARAM_INT);
    $statement->bindParam(':existArticulo',$this->exist_articulo,\PDO::PARAM_INT);
    return $statement -> execute();
  }

  public function actualizarArticulo() {
    $statement =false;
    $di = \Phalcon\DI::getDefault();
    $db = $di->get("conexion");
    $statement = $db->prepare("UPDATE cat_articulos SET des_articulo=:desArticulo,modelo_articulo=:modeloArticulo,precio_articulo=:precioArticulo,exist_articulo=:existArticulo WHERE id_articulo = :idArticulo;");
    $statement->bindParam(':desArticulo',$this->des_articulo);
    $statement->bindParam(':modeloArticulo',$this->modelo_articulo);
    $statement->bindParam(':precioArticulo',$this->precio_articulo,\PDO::PARAM_INT);
    $statement->bindParam(':existArticulo',$this->exist_articulo,\PDO::PARAM_INT);
    $statement->bindParam(':idArticulo',$this->id_articulo,\PDO::PARAM_INT);
    return $statement -> execute();
  }

  public function eliminarArticulo() {
    $statement =false;
    $di = \Phalcon\DI::getDefault();
    $db = $di->get("conexion");
    $statement = $db->prepare("DELETE FROM cat_articulos WHERE id_articulo = ?");
    $statement->bindParam(1,$this->id_articulo);
    return $statement -> execute();
  }
}
