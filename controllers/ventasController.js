"use strict";
var ventasController =  angular.module("ventasController",[])
.controller("ventasControl",function($scope,ventasServicio){
  $scope.traerVentas = function(){
    ventasServicio.consultarVentas(function(res){
    $scope.ventas = res.data.data.response;
    });
  }
})
