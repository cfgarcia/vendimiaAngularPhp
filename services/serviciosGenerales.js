"use strict";
var serviciosGenerales = angular.module("serviciosGenerales",[])
.service("servicioGeneral",function(){
  this.manejadorAlerta = function(msg,tipo) {
    $.notify({
    	message: msg
    },{
    	type: tipo,
      delay: 800
    });
  }
})
