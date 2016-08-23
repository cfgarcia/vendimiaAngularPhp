"use strict";
var clientesService = angular.module("clientesService",[])
.factory("clientesServicio",function($http){
  var url = "backend/api/clientes/";
  var clientes  = [];
  var clientesTotales  = [];
  var clientesServicio = {};

  clientesServicio.consultarClientes = function (callback){
    $http.get(url).then(function(res){
      clientesTotales = res.data.data.response;
      clientes = res.data.data.response;
      callback(res);
    },function(err){
      $.notify("Error de conexion","error");
    })
  }

  clientesServicio.buscarClientes = function(seleccionado){
    if(!seleccionado){
      clientes = clientesTotales;
      return;
    }
    var letra = seleccionado.toLowerCase();
    var palabra;
    var temp=[];
    angular.forEach(clientesTotales,function(value,key){
      palabra = value.nom_cliente.toLowerCase();
      if(palabra.indexOf(letra) == -1){
        return
      }
      temp.push(value);
    })
    clientes = temp;
  }

  clientesServicio.consultarClientePorID = function (id,callback){
    $http.get(url + '/' + id).then(function(res){
      clientesTotales = res.data.data.response;
      callback(res.data.data.response);
    },function(err){
      $.notify("Error de conexion","error");
    })
  }

  clientesServicio.getClientes = function(){
      return clientes;
    }

  clientesServicio.guardarCliente = function(cliente,callback) {
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
      callback();
    },function(err){
      $.notify("Error de conexion","error")
    });

  }

  clientesServicio.editarCliente = function(cliente,callback) {

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

    $http.put(url + '/' + cliente.idCliente,cliente).then(function(res){
      $.notify("Bien Hecho. El cliente ha sido registrado correctamente","success");
      callback();
    },function(err){
      $.notify("Error de conexion","error")
    })
  }

  return clientesServicio;
})
