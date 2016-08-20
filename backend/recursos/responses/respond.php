<?php
namespace vendimia\Responses;
use vendimia\Exceptions\HTTPException;

class Respond
{
  public function __construct() {

  }

  public function respond($recordsArray)
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
