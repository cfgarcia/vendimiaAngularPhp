"use strict";
var clientesService = angular.module("clientesService",[])
.factory("clientesServicio",function($http){
  var url = "backend/api/clientes/";
  var clientes  = [];
  var clientesServicio = {};
  clientesServicio.consultarClientes = function (callback){
    $http.get(url).then(function(res){
      callback(res.data.data.response);
    },function(err){
      $.notify("Error de conexion","error");
    })
  }

  clientesServicio.guardarCliente = function(cliente) {
    if(!cliente.nomCliente){
      $.notify("No es posible continuar, debe ingresar nombre es obligatorio","warn");
      return;
    }
    if(!cliente.apellidoP){
      $.notify("No es posible continuar, debe ingresar apellido paterno es obligatorio","warn");
      return;
    }
    if(!cliente.rfc){
      $.notify("No es posible continuar, debe ingresar RFC es obligatorio","warn");
      return;
    }

    $http.post(url,cliente).then(function(res){
      $.notify("Bien Hecho. El cliente ha sido registrado correctamente","success");
    },function(err){
      $.notify("Error de conexion","error")
    })
  }
  return clientesServicio;
})