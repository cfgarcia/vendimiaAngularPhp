<?php
namespace vendimia\Controllers;
use vendimia\Exceptions\HTTPException;
use vendimia\Models as Modelos;
use vendimia\Responses as Respuesta;

class clientesController extends \Phalcon\Mvc\Controller
{
  private $model;
  private $respuesta;
  public function onConstruct() {
    $this->model = new Modelos\clientesModel();
    $this->respuesta = new Respuesta\Respond();
  }

  public function consultarClientes() {
    $consulta = null;
    try{
      $consulta = $this->model->consultarCliente();
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

  public function consultarClientesPorID ($id) {
    $consulta = null;
    try{
      $this->model->setClienteID($id);
      $consulta = $this->model->consultarClientePorId($id);
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
              'El cliente Solicitado no Existe.',
              500,array()
      );
    }
    return $this->respuesta->respond(["response"=>$consulta]);
  }

  public function agregarCliente () {
    $consulta = false;
    $cliente = null;
    try{
      $cliente = $this->request->getJsonRawBody();
      $this->model->setNombreCliente($cliente->nomCliente);
      $this->model->setApellidoPaterno($cliente->apellidoP);
      $this->model->setApellidoMaterno($cliente->apellidoM);
      $this->model->setRfc($cliente->rfc);
      $consulta = $this->model->agregarCliente();
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

  public function actualizarCliente($idCliente) {
    $consulta = null;
    $cliente = null;
    try{
      $cliente = $this->request->getJsonRawBody();
      $this->model->setClienteID($idCliente);
      $consulta = $this->model->consultarClientePorId($id);
      if(!empty($consulta)){
        $this->model->setNombreCliente($cliente->nomCliente);
        $this->model->setApellidoPaterno($cliente->apellidoP);
        $this->model->setApellidoMaterno($cliente->apellidoM);
        $this->model->setRfc($cliente->rfc);
        $consulta = $this->model->actualizarCliente();
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
    return $this->respuesta->respond(["response"=>$consulta]);
  }

  public function eliminarCliente ($idCliente) {
    $consulta = null;
    try{
      $this->model->setClienteID($idCliente);
      $cliente = $this->model->consultarClientePorId($id);
      if(!empty($cliente)){
        $consulta = $this->model->eliminarCliente();
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
    return $this->respuesta->respond(["response"=>$consulta]);
  }

}
