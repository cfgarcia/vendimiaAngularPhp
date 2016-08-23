"use strict";

var articulosController = angular.module("articulosController",[])
.controller("articulosControl",function($scope,articulosServicio){
  $scope.traerArticulos = function(){
    articulosServicio.consultarArticulos(function(res){
    $scope.articulos = res.data.data.response;
    });
  }

  $scope.llenarArticuloNuevo = function() {
    try{
      $scope.folio = parseInt($scope.articulos[Object.keys($scope.articulos)[Object.keys($scope.articulos).length - 1]].id_articulo) + 1;
    }catch(e){
      $scope.folio = 1;
    }

    $scope.infoArticulo = {};
    $scope.form=true;
    $scope.editar =false;
  }

  $scope.llenarArticulo = function (idArticulo) {
    var articulo = [];
    var temp = [];
    articulosServicio.consultarArticuloPorId(idArticulo.id_articulo,function(res){
      articulo = res[0];
      $scope.editar = true;
      $scope.folio = idArticulo.id_articulo;
      $scope.infoArticulo.desArticulo = articulo.des_articulo;

      $scope.infoArticulo.precioArticulo = parseFloat(articulo.precio_articulo);
      $scope.infoArticulo.existArticulo = parseInt(articulo.exist_articulo);
      $scope.form =true;
    });
  }

  $scope.guardarArticulo = function(){

    articulosServicio.guardarArticulo(this.infoArticulo,function(){
      $scope.traerArticulos();
      $scope.form =false;
    });
  }

  $scope.editarArticulo = function(){
    this.infoArticulo.idArticulo = $scope.folio;
    articulosServicio.editarArticulo(this.infoArticulo,function(){
      $scope.traerArticulos();
      $scope.form =false;
    });
  }

})
