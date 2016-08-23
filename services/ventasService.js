"use strict";
var ventasService = angular.module("ventasService",[])
.factory("ventasServicio",function($http){
  var url = "backend/api/ventas/";
  var ventasServicio = {};
  ventasServicio.consultarVentas = function (callback){
    $http.get(url).then(callback,function(err){
      $.notify("Error de conexion","error");
    })
  }

  ventasServicio.guardarVenta = function(venta,callback) {
    $http.post(url,venta).then(function(res){
      $.notify("Bien Hecho, Tu venta ha sido registrada correctamente","success");
      callback();
    },function(err){
      $.notify("Error de conexion","error")
    });
  }

  return ventasServicio;
})
