<?php
namespace vendimia\Controllers;
use vendimia\Exceptions\HTTPException;
use vendimia\Models as Modelos;
use vendimia\Responses as Respuesta;

class articulosController extends \Phalcon\Mvc\Controller
{
  private $model;
  private $respuesta;
  public function onConstruct() {
    $this->model = new Modelos\articulosModel();
    $this->respuesta = new Respuesta\Respond();
  }

  public function consultarArticulos() {
    $consulta = null;
    try{
      $consulta = $this->model->consultarArticulos();
    } catch(\Exception $e) {
      $mensaje = utf8_encode($e->getMessage());
      throw new \vendimia\Exceptions\HTTPException(
          'No fue posible completar su solicitud, intente de nuevo por favor.',
          500,
          array(
              'dev' => $mensaje,
              'internalCode' => 'SIE1000',
              'more' => 'Verificar conexión con la base de datos.'
          )
      );
    }
    return $this->respuesta->respond(["response"=>$consulta]);
  }

  public function consultarArticulosPorId ($id) {
    $consulta = null;
    try{
      $this->model->setArticuloId($id);
      $consulta = $this->model->consultarArticulosPorId();
    } catch(\Exception $e) {
      $mensaje = utf8_encode($e->getMessage());
      throw new \vendimia\Exceptions\HTTPException(
          'No fue posible completar su solicitud, intente de nuevo por favor.',
          500,
          array(
              'dev' => $mensaje,
              'internalCode' => 'SIE1000',
              'more' => 'Verificar conexión con la base de datos.'
          )
      );
    }
    if(empty($consulta)){
      throw new \vendimia\Exceptions\HTTPException(
              'El cliente Articulo no Existe.',
              500,array()
      );
    }
    return $this->respuesta->respond(["response"=>$consulta]);
  }

  public function agregarArticulo () {
    $consulta = false;
    $articulo = null;
    try{
      $articulo = $this->request->getJsonRawBody();
      $this->model->setDesArticulo($articulo->desArticulo);
      $this->model->setModeloArticulo($articulo->modeloArticulo);
      $this->model->setPrecioArticulo($articulo->precioArticulo);
      $this->model->setExistArticulo($articulo->existArticulo);
      $consulta = $this->model->agregarArticulo();
    } catch(\Exception $e) {
      $mensaje = utf8_encode($e->getMessage());
      throw new \vendimia\Exceptions\HTTPException(
          'No fue posible completar su solicitud, intente de nuevo por favor.',
          500,
          array(
              'dev' => $mensaje,
              'internalCode' => 'SIE1000',
              'more' => 'Verificar conexión con la base de datos.'
          )
      );
    }
    if(!$consulta){
      throw new \vendimia\Exceptions\HTTPException(
              'Error al Guarda.',
              500,array()
      );
    }
    return $this->respuesta->respond(["response"=>$consulta]);
  }

  public function actualizarArticulo($idArticulo) {
    $consulta = null;
    $articulo = null;
    try{
      $articulo = $this->request->getJsonRawBody();
      $this->model->setArticuloId($idArticulo);
      $consulta = $this->model->consultarArticulosPorId();
      if(!empty($consulta)){
        $this->model->setDesArticulo($articulo->desArticulo);
        $this->model->setModeloArticulo($articulo->modeloArticulo);
        $this->model->setPrecioArticulo($articulo->precioArticulo);
        $this->model->setExistArticulo($articulo->existArticulo);
        $consulta = $this->model->actualizarArticulo();
      } else {
        throw new \vendimia\Exceptions\HTTPException(
                'Ese articulo no existe.',
                500,array()
        );
      }
    } catch(\Exception $e) {
      $mensaje = utf8_encode($e->getMessage());
      throw new \vendimia\Exceptions\HTTPException(
          'No fue posible completar su solicitud, intente de nuevo por favor.',
          500,
          array(
              'dev' => $mensaje,
              'internalCode' => 'SIE1000',
              'more' => 'Verificar conexión con la base de datos.'
          )
      );
    }
    return $this->respuesta->respond(["response"=>$consulta]);
  }

  public function eliminarArticulo ($idArticulo) {
    $consulta = null;
    $articulo = null;
    try{
      $this->model->setArticuloId($idArticulo);
      $articulo = $this->model->consultarArticulosPorId();
      if(!empty($articulo)){
        $consulta = $this->model->eliminarArticulo();
      } else {
        throw new \vendimia\Exceptions\HTTPException(
                'Ese Articulo no existe.',
                500,array()
        );
      }

    } catch(\Exception $e) {
      $mensaje = utf8_encode($e->getMessage());
      throw new \vendimia\Exceptions\HTTPException(
          'No fue posible completar su solicitud, intente de nuevo por favor.',
          500,
          array(
              'dev' => $mensaje,
              'internalCode' => 'SIE1000',
              'more' => 'Verificar conexión con la base de datos.'
          )
      );
    }
    return $this->respuesta->respond(["response"=>$consulta]);
  }
}
