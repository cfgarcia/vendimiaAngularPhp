<?php
namespace vendimia\Controllers;
use vendimia\Exceptions\HTTPException;
use vendimia\Models as Modelos;

class vendimiaController extends \Phalcon\Mvc\Controller
{
  private $model;
  public function onConstruct() {
    $this->model = new Modelos\vendimiaModel();
  }

  public function testController() {

    $consulta = $this->model->consultarCliente();

    return $this->respond(["response"=>$consulta]);
  }
  protected function respond($recordsArray)
  {
      if (!is_array($recordsArray)) {
          throw new vendimia\Exceptions\HTTPException(
              'Ocurrio un error mientras se obtenian los datos de retorno.',
              500,
              array(
                  'dev' => 'El objeto de retorno siempre deberia ser un arreglo.',
                  'internalCode' => 'A1000',
                  'more' => 'Cambie los datos de retorno para ser un arreglo.',
              )
          );
      }

      if (count($recordsArray) < 1) {
          return array();
      }

      return $recordsArray;
  }
}
