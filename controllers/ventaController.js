var ventaController = angular.module("ventaController",[])
.controller("ventaControl",function($scope,ventasServicio,clientesServicio,articulosServicio,configuracionServicio){

  $scope.traerConfiguracion = function(){
    configuracionServicio.consultarConfiguracion(function(res){
    $scope.configuracion = res.data.data.response;
    });
  }
  ventasServicio.consultarVentas(function(res){
    var ventas = res.data.data.response;
    try{
      $scope.folio = parseInt(ventas[Object.keys(ventas)[Object.keys(ventas).length - 1]].id_venta) + 1;
    }catch(e){
      $scope.folio = 1;
    }
  })
  $scope.traerClientes = function(){
    clientesServicio.consultarClientes(function(res){
    });
  }
  $scope.clientes = clientesServicio.getClientes;
  $scope.getCliente = function(cliente) {

    $scope.infoCliente = cliente;
    $scope.clienteSeleccionado = cliente.nom_cliente;
  }
  $scope.buscarCliente = function(){
      if($scope.infoCliente){
        $scope.infoCliente = {};
      }
      clientesServicio.buscarClientes($scope.clienteSeleccionado);

      $scope.mostrarCliente = true;
  }

  $scope.mostrarclientes = function () {
    setTimeout(function(){
      $scope.mostrarCliente = false;
      $scope.$apply();
    },200)
  }

  $scope.traerArticulos = function(){
      articulosServicio.consultarArticulos(function(res){
    });
  }
  $scope.articulos = articulosServicio.getArticulos;
  $scope.getArticulos = function(articulo) {
    if(articulo.exist_articulo < 1) {
      $.notify("El artículo seleccionado no cuenta con existencia, favor de verificar","warn");
      return;
    }
    $scope.infoArticulo = articulo;
    $scope.articuloSeleccionado = articulo.des_articulo;
  }
  $scope.buscarArticulo = function(){
      articulosServicio.buscarArticulos($scope.articuloSeleccionado);
      $scope.mostrarArticulo = true;
  }
  $scope.mostrarArticulos = function () {
    setTimeout(function(){
      $scope.mostrarArticulo = false;
      $scope.$apply();
    },200)
  }
  $scope.agregarArticulos = function() {
    var repetido = false;
    if($scope.infoArticulo.exist_articulo <= 0){
      $.notify("El artículo seleccionado no cuenta con existencia, favor de verificar","warn");
      return;
    }
    angular.forEach($scope.infoArticulos,function(value,key){
      if(value.id_articulo == $scope.infoArticulo.id_articulo){
        repetido = true;
      }
    })
    if(!repetido){
      $scope.infoArticulos.push($scope.infoArticulo);
    }


  }
  $scope.removerArticulo = function(articulo,index) {

    $scope.importeTotalNeto = $scope.importeTotalNeto - articulo.importe;
    enganche = ($scope.configuracion.por_enganche/100) * $scope.importeTotalNeto;
    bonificacionEnganche = enganche * (($scope.configuracion.tasa_fin * $scope.configuracion.plazo_max)/100);
    total = $scope.importeTotalNeto - enganche - bonificacionEnganche;

    $scope.totales.enganche = Math.round(enganche* 100) / 100;
    $scope.totales.bonificacionEnganche = Math.round(bonificacionEnganche* 100) / 100;
    $scope.totales.total = Math.round(total* 100) / 100;
    articulo.precio = null;
    articulo.importe = null;
    $scope.infoArticulos.splice(index, 1);
  }
  $scope.getCantidad = function(articulo,cantidad){
    if(articulo.exist_articulo < cantidad){
      $.notify("La cantidad solicitada es mayor que la existente","warn");
      return;
    }
    articulo.stock = articulo.exist_articulo - cantidad;
    var enganche = 0;
    var precio = 0;
    var importe = 0;
    var enganche = 0;
    var bonificacionEnganche = 0;
    var total = 0;
    var importeTotal = 0;
    if(!cantidad){
      return;
    }

    precio = (articulo.precio_articulo * (1 + ($scope.configuracion.tasa_fin * $scope.configuracion.plazo_max)/100));
    importe = precio * cantidad;
    articulo.precio = Math.round(precio* 100) / 100;
    articulo.importe = Math.round(importe* 100) / 100;
    angular.forEach($scope.infoArticulos,function(value,key){
      importeTotal = importeTotal + value.importe;

    });
    $scope.importeTotalNeto = importeTotal;
    enganche = ($scope.configuracion.por_enganche/100) * importeTotal;
    bonificacionEnganche = enganche * (($scope.configuracion.tasa_fin * $scope.configuracion.plazo_max)/100);
    total = importeTotal - enganche - bonificacionEnganche;

    $scope.totales.enganche = Math.round(enganche* 100) / 100;
    $scope.totales.bonificacionEnganche = Math.round(bonificacionEnganche* 100) / 100;
    $scope.totales.total = Math.round(total* 100) / 100;
  }

  $scope.seguiente = function () {
    if(!$.isEmptyObject($scope.infoArticulo) && !$.isEmptyObject($scope.infoCliente) && $scope.totales.total > 0){
      $scope.seguimiento = true;
      $scope.initAbonos();
    }else{
      $.notify("Los datos ingresados no son correctos, favor de verificar","warn");
    }
  }

  $scope.guardarVenta = function() {
    if(!$scope.abonoSelecionado){
      $.notify("Debe seleccionar un plazo para realizar el pago de su compra","warn");
      return;
    }
    var temp = {
      idCliente : $scope.infoCliente.id_cliente,
      totalVenta : $scope.abonoSelecionado.totalPagar
    }

    ventasServicio.guardarVenta(temp,function(){
      var articulo = {};
      angular.forEach($scope.infoArticulos,function(value,key){
        articulo.idArticulo = value.id_articulo;
        articulo.desArticulo = value.des_articulo;
        articulo.modeloArticulo = value.modelo_articulo;
        articulo.precioArticulo = value.precio_articulo;
        articulo.existArticulo = value.stock;
        articulo.ventas = true;
        articulosServicio.editarArticulo(articulo,function(res){
          window.location.href = '#/ventas';
        })
      })
    });

  }


  $scope.initAbonos = function () {
      var totalPagar = 0;
      var importeAbono = 0;
      var importeAhorra = 0;
      var precioContado = $scope.totales.total / (1 + (($scope.configuracion.tasa_fin * $scope.configuracion.plazo_max) / 100));
      $scope.infoAbonos = [{cantidadAbonos : "3 ABONOS DE",plazo : 3},{cantidadAbonos : "6 ABONOS DE",plazo : 6},{cantidadAbonos : "9 ABONOS DE",plazo : 9},{cantidadAbonos : "12 ABONOS DE",plazo : 12}]
      angular.forEach($scope.infoAbonos,function(value,key){
        value.totalAPagar = "TOTAL A PAGAR $";
        value.seAhorra = "SE AHORRA $"
        totalPagar = precioContado * (1 + ($scope.configuracion.tasa_fin * value.plazo) / 100);
        importeAbono =  totalPagar / value.plazo;
        importeAhorra = $scope.totales.total - totalPagar;
        value.totalPagar =  Math.round(totalPagar * 100) / 100;
        value.importeAbono =   Math.round(importeAbono * 100) / 100;
        value.importeAhorra =  Math.round(importeAhorra * 100) / 100;
      });

    }
  //$scope.abonoSelecionado = $scope.infoAbonos[0];

})
