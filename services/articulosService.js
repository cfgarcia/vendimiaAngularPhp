"use strict";
var articulosService = angular.module("articulosService",[])
.factory("articulosServicio",function($http){
  var articulos = [];
  var articulosTotales = [];
  var articulosServicio = {};
  var url = "backend/api/articulos/";

  // articulosServicio.consultarArticulos = function (callback){
  //   $http.get(url).then(callback,function(err){
  //     $.notify("Error de conexion","error");
  //   })
  // }

  articulosServicio.consultarArticulos = function (callback){
    $http.get(url).then(function(res){
      articulosTotales = res.data.data.response;
      articulos = res.data.data.response;
      callback(res);
    },function(err){
      $.notify("Error de conexion","error");
    })
  }

  articulosServicio.buscarArticulos = function(seleccionado){

    if(!seleccionado){
      articulos = articulosTotales;
      return;
    }
    var letra = seleccionado.toLowerCase();
    var palabra;
    var temp=[];
    angular.forEach(articulosTotales,function(value,key){
      palabra = value.des_articulo.toLowerCase();
      if(palabra.indexOf(letra) == -1){
        return
      }
      temp.push(value);
    })
    articulos = temp;
  }

  articulosServicio.getArticulos = function(){
      return articulos;
    }

  articulosServicio.consultarArticuloPorId = function (id,callback){
    $http.get(url + '/' + id).then(function(res){
      callback(res.data.data.response);
    },function(err){
      $.notify("Error de conexion","error");
    })
  }

  articulosServicio.validarArticulo = function(articulo) {
    if(!articulo.desArticulo){
      $.notify("No es posible continuar, debe ingresar descripci\u00F3n es obligatorio","warn");
      return false;
    }
    if(!articulo.precioArticulo){
      $.notify("No es posible continuar, debe ingresar precio es obligatorio","warn");
      return false;
    }
    if(!articulo.existArticulo){
      $.notify("No es posible continuar, debe ingresar existencia es obligatorio","warn");
      return false;
    }

    if(!Number.isInteger(articulo.existArticulo)){
      $.notify("No es posible continuar, debe ingresar existencia es obligatorio","warn");
      return false;
    }
    return true;
  }

  articulosServicio.guardarArticulo = function(articulo,callback) {

    if(!articulosServicio.validarArticulo(articulo)){
      return;
    }
    $http.post(url,articulo).then(function(res){
      $.notify("Bien Hecho. El Articulo ha sido registrado correctamente","success");
      callback();
    },function(err){
      $.notify("Error de conexion","error")
    });
  }

  articulosServicio.editarArticulo = function(articulo,callback) {
    if(!articulosServicio.validarArticulo(articulo)){
      return;
    }

    $http.put(url + '/' + articulo.idArticulo,articulo).then(function(res){
      if(!articulo.ventas){
        $.notify("Bien Hecho. El Articulo ha sido registrado correctamente","success");
      }
      callback(res);
    },function(err){
      $.notify("Error de conexion","error")
    });
  }

  return articulosServicio;
})
