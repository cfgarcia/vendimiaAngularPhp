"use strict";
var clientesController = angular.module("clientesController",[])
.controller("clientesControl",function($scope,clientesServicio){

  $scope.traerClientes = function(){
    clientesServicio.consultarClientes(function(res){
    $scope.clientes = res.data.data.response;
    });
  }

  $scope.llenarClienteNuevo = function() {
    try{
      $scope.folio = parseInt($scope.clientes[Object.keys($scope.clientes)[Object.keys($scope.clientes).length - 1]].id_cliente) + 1;
    }catch(e){
      $scope.folio = 1;
    }

    $scope.infoCliente = {};
    $scope.form=true;
    $scope.editar =false;
  }
  $scope.guardarCliente = function(){
    clientesServicio.guardarCliente(this.infoCliente,function(){
      $scope.traerClientes();
      $scope.form =false;
    });
  }

  $scope.llenarCliente = function (idCliente) {
    var cliente = [];
    var temp = [];
    clientesServicio.consultarClientePorID(idCliente.id_cliente,function(res){
      cliente = res[0];
      $scope.editar = true;
      $scope.folio = idCliente.id_cliente;
      $scope.infoCliente.nomCliente = cliente.nom_cliente;
      $scope.infoCliente.apellidoP = cliente.apellido_paterno;
      $scope.infoCliente.apellidoM = cliente.apellido_materno;
      $scope.infoCliente.rfc = cliente.rfc;
      $scope.form =true;
    });

  }

  $scope.editarCliente = function(){
    this.infoCliente.idCliente = $scope.folio;
    clientesServicio.editarCliente(this.infoCliente,function(){
      $scope.traerClientes();
      $scope.form =false;
    });
  }
});
