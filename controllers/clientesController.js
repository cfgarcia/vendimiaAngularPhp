"use strict";
var clientesController = angular.module("clientesController",[])
.controller("clientesControl",function($scope,clientesServicio){
  clientesServicio.consultarClientes(function(res){
    $scope.clientes = res;
  });
  $scope.guardarCliente = function(){
    clientesServicio.guardarCliente(this.infoCliente);
  }
});
