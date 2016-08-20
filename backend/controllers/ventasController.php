<?php
namespace vendimia\Controllers;
use vendimia\Exceptions\HTTPException;
use vendimia\Models as Modelos;
use vendimia\Responses as Respuesta;

class ventasController extends \Phalcon\Mvc\Controller
{
  private $model;
  private $respuesta;

  public function onConstruct() {
    $this->model = new Modelos\ventasModel();
    $this->modelClientes = new Modelos\clientesModel();
    $this->respuesta = new Respuesta\Respond();
  }

  public function consultarVentas () {
    $consulta = null;
    try{
      $consulta = $this->model->consultarVentas();
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

  public function consultarVentasPorId ($id) {
    $consulta = null;
    try{
      $this->model->setIdVenta($id);
      $consulta = $this->model->consultarVentasPorId($id);
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
              'La venta Solicitada no Existe.',
              500,array()
      );
    }
    return $this->respuesta->respond(["response"=>$consulta]);
  }

  public function agregarVenta() {
    $consulta = false;
    $ventas = null;
    try{
      $ventas = $this->request->getJsonRawBody();
      $this->modelClientes -> setClienteID($ventas->idCliente);
      $cliente = $this->modelClientes->consultarClientePorId ();
      if(!empty($cliente)){
        $this->model->setIdCliente($ventas->idCliente);
        $this->model->setTotalVenta($ventas->totalVenta);
        $consulta = $this->model->agregarVenta();
      } else {
        throw new \vendimia\Exceptions\HTTPException(
                'Ese cliente no existe.',
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
    if(!$consulta){
      throw new \vendimia\Exceptions\HTTPException(
              'Error al Guarda.',
              500,array()
      );
    }
    return $this->respuesta->respond(["response"=>$consulta]);
  }

  public function actualizarVenta($idVenta) {
    $consulta = null;
    $ventas = null;
    $cliente = null;
    try{
      $ventas = $this->request->getJsonRawBody();
      $this->model->setIdVenta($idVenta);
      $venta = $this->model->consultarVentasPorId();
      if(!empty($venta)){
        $this->modelClientes -> setClienteID($ventas->idCliente);
        $cliente = $this->modelClientes->consultarClientePorId ();
        if(!empty($cliente)){
          $this->model->setIdCliente($ventas->idCliente);
          $this->model->setTotalVenta($ventas->totalVenta);
          $consulta = $this->model->actualizarVenta();
        } else {
          throw new \vendimia\Exceptions\HTTPException(
                  'Esa cliente no exite no existe.',
                  500,array()
          );
        }
      } else {
        throw new \vendimia\Exceptions\HTTPException(
                'Esa venta no exite no existe.',
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

  public function eliminarVenta ($idVenta) {
    $consulta = null;
    $venta = null;
    try{
      $this->model->setIdVenta($idVenta);
      $venta = $this->model->consultarVentasPorId();
      if(!empty($venta)){
        $consulta = $this->model->eliminarVenta();
      } else {
        throw new \vendimia\Exceptions\HTTPException(
                'Esa venta no existe.',
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
