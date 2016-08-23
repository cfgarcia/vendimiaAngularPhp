"use strict";

var vendimiaApp = angular.module("vendimiaApp",["ngRoute","ngAnimate","autocomplete","clientesController","clientesService","articulosController","articulosService","configuracionController","configuracionService","ventasController","ventasService","ventaController"])
.config(["$routeProvider",function($routeProvider){
  $routeProvider
    .when('/clientes',{

      templateUrl:'views/clientesVista.html',
      controller:'clientesControl'
    })
    .when('/articulos', {
        controller: 'articulosControl',
        templateUrl: 'views/articulosVista.html'
    })
    .when('/configuracion', {
        controller: 'configuracionControl',
        templateUrl: 'views/configuracionVista.html'
    })
    .when('/ventas', {
        controller: 'ventasControl',
        templateUrl: 'views/ventasVista.html'
    })
    .when('/venta', {
        controller: 'ventaControl',
        templateUrl: 'views/ventaVista.html'
    })

    .otherwise({ redirectTo: '/' });
}])
.run(function($rootScope,$location){
  // var mes = new Date().getMonth();
  // var ano = new Date().getYear();
  // var dia = new Date(Date.UTC(year, month, day, hour, minute, second))
  // debugger;
  $rootScope.fecha = new Date();
  // $rootScope.$on("$locationChangeStart",function(event, next, current){
  //   var pag=next.split("/");
  //   var pagActual=current.split("/");
  //   var temp = pag.length;
  //   $('.navbar-nav').children().removeClass("active");
  //   // if(pagActual[temp-1]=='adminManuales'){
  //   //   debugger;
  //   // }
  //   $('#' + pag[temp-1]).addClass("active");
  //
  //   if( pag[temp-1]=='agregarManual'){
  //
  //     $rootScope.direccion = false;
  //   }
  //   if( pag[temp-1]=='adminManuales'){
  //     $rootScope.direccion = true;
  //
  //   }
  //   if(next==current && pag[temp-1]=='agregarManual')
  //   {
  //     event.preventDefault();
  //     $location.url("/adminManuales");
  //   }
  // })
})
