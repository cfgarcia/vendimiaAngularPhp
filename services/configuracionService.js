"use strict";
var configuracionService = angular.module("configuracionService",[])
.factory("configuracionServicio",function($http){
  var configuracionServicio = {};
  var url = "backend/api/configuracion/";

  configuracionServicio.consultarConfiguracion = function (callback){
    $http.get(url).then(callback,function(err){
      $.notify("Error de conexion","error");
    })
  }



  configuracionServicio.guardarConfiguracion = function(configuracion,callback) {

    if(!configuracion.plazoMax && !configuracion.porEnganche & !configuracion.tasaFin){
      $.notify("Necesita al menos ingresar un dato.","warn");
      return;
    }

    $http.post(url,configuracion).then(function(res){
      $.notify("Bien Hecho. La configuraci\u00F3n ha sido registrada.","success");
      callback();
    },function(err){
      $.notify("Error de conexion","error")
    });
  }

  configuracionServicio.editarConfiguracion = function(configuracion,callback) {

    $http.put(url + '/' + configuracion.idConf,configuracion).then(function(res){
      $.notify("Bien Hecho. La configuraci\u00F3n ha sido registrada","success");
      callback();
    },function(err){
      $.notify("Error de conexion","error")
    });
  }
  return configuracionServicio;
})
