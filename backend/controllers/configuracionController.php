<?php
namespace vendimia\Controllers;
use vendimia\Exceptions\HTTPException;
use vendimia\Models as Modelos;
use vendimia\Responses as Respuesta;

class configuracionController extends \Phalcon\Mvc\Controller
{
  private $model;
  private $respuesta;

  public function onConstruct() {
    $this->model = new Modelos\configuracionModel();
    $this->respuesta = new Respuesta\Respond();
  }

  public function consultarConfiguarion () {
    $consulta = null;
    try{
      $consulta = $this->model->consultarConfiguarion();
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

  public function agregarConfiguracion () {
    $consulta = false;
    $configuracion = null;
    try{
      $configuracion = $this->request->getJsonRawBody();
      $this->model->setTasaFin($configuracion->tasaFin);
      $this->model->setPorEnganche($configuracion->porEnganche);
      $this->model->setPlazoMax($configuracion->plazoMax);
      $consulta = $this->model->agregarConfiguracion();
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

  public function actualizarConfiguracion($idConfig) {
    $consulta = null;
    $configuracion = null;
    try{
      $configuracion = $this->request->getJsonRawBody();
      $this->model->setIdConfig($idConfig);
      $consulta = $this->model->consultarConfiguracionPorId();
      if(!empty($consulta)){
        $this->model->setTasaFin($configuracion->tasaFin);
        $this->model->setPorEnganche($configuracion->porEnganche);
        $this->model->setPlazoMax($configuracion->plazoMax);
        $consulta = $this->model->actualizarConfiguracion();
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
}
