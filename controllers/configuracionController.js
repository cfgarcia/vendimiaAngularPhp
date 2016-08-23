"use strict";
var configuracionController = angular.module("configuracionController",[])
.controller("configuracionControl",function($scope,configuracionServicio){
  $scope.traerConfiguracion = function(){
    configuracionServicio.consultarConfiguracion(function(res){
    var configuracion = res.data.data.response;
    if(!configuracion){
      $scope.infoConfiguracion = {};
      return;
    }
    $scope.editar = true;
    $scope.folio = configuracion.id_conf;
    $scope.infoConfiguracion.tasaFin = parseFloat(configuracion.tasa_fin);
    $scope.infoConfiguracion.porEnganche = parseFloat(configuracion.por_enganche);
    $scope.infoConfiguracion.plazoMax = parseInt(configuracion.plazo_max);
    });
  }

  $scope.guardarConfiguracion = function(){
    configuracionServicio.guardarConfiguracion(this.infoConfiguracion,function(){
    $scope.traerConfiguracion();
    });
  }

  $scope.editarConfiguracion = function(){

    if(!this.myForm.$valid){
      return;
    }

    this.infoConfiguracion.idConf = $scope.folio;
    configuracionServicio.editarConfiguracion(this.infoConfiguracion,function(){
      $scope.traerConfiguracion();
    });
  }
})
