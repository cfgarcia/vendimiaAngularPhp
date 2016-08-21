"use strict";

var vendimiaApp = angular.module("vendimiaApp",["ngRoute","ngAnimate","clientesController","clientesService"])
.config(["$routeProvider",function($routeProvider){
  $routeProvider
    .when('/clientes',{

      templateUrl:'views/clientesVista.html',
      controller:'clientesControl'
    })
    // .when('/agregarManual', {
    //     controller: 'fileUpload',
    //     templateUrl: 'views/agregarManual.html'
    // })
    // .when('/gridPuestos',{
    //   controller:'gridPuestos',
    //   templateUrl:'views/gridPuestos.html'
    // })
    .otherwise({ redirectTo: '/' });
}])
