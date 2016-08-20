<?php
namespace vendimia;
ini_set("display_errors","on");
error_reporting(E_ALL);
use Phalcon\Loader;
use Phalcon\Mvc\Micro;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\DI\FactoryDefault;
use Phalcon\Mvc\Micro\Collection;
use Phalcon\Logger;
use Phalcon\Logger\Adapter\File as FileLogger;

class modulo extends Micro
{
  private $ROOT_PROJECT_PATH;
  private $config;
  private $module;
  private $loader;
  private $di;
  private $events;
  private $services;
  private $evensManager;
  private $collections = [];
  private function validJson($json) {
      json_decode($json);
      return (json_last_error() == JSON_ERROR_NONE);
  }
  public function __construct($config)
  {
      $this->ROOT_PROJECT_PATH =__DIR__;
      $this->registerLoader();
      $this->events = require_once __DIR__ . '/recursos/events.php';
      $this->services = require_once __DIR__ . '/recursos/services.php';
      $this->registerExceptionHandler();
      $this->registerEvents();
      $this->registerServices();
      $this->registerConfig($config);
  }

  private function registerConfig($config)
  {
      $file = "{$this->ROOT_PROJECT_PATH}/configVendimia.json";
      if (!file_exists($file)) {

          throw new \vendimia\Exceptions\HTTPException(
              'Ocurrio un error al cargar uno de los módulos de configuración.',
              500,
              [
                  'dev' => "No es posible desplegar el módulo debido a que no se encuentra su config.json.",
                  'internalCode' => 'NF2000',
                  'more' => "Se buscaba el archivo config.json.",
              ]
          );
      }

      $jsonConfig = file_get_contents($file);

      if (!$this->validJson($jsonConfig)) {
          throw new \vendimia\Exceptions\HTTPException(
              'Un archivo de configuración no está bien formado.',
              500,
              [
                  'dev' => "No es posible desplegar el módulo debido a que los json del archivo config.json están mal formados",
                  'internalCode' => 'NF2000',
                  'more' => "Verificar los json del archivo config.json.",
              ]
          );
      }
      $config = json_decode($jsonConfig);
      $this->di->set('config', function () use ($config) {
          return $config;
      });
  }

  private function registerLoader()
  {
      $this->loader = new Loader();

      $this->loader->registerNamespaces(array(
          'vendimia' => __DIR__ . '/',
          'vendimia\Responses' => __DIR__ . '/recursos/responses/',
          'vendimia\Exceptions' => __DIR__ . '/recursos/exceptions/'
      ))->register();
      $this->loader->registerNamespaces(array('vendimia\Controllers' => __DIR__.'/controllers/',
        'vendimia\Models' => __DIR__.'/models/'
        ), true);
  }

  private function registerExceptionHandler()
  {
      set_exception_handler($this->events['exceptionHandler']);
  }

  public function registerEvents()
  {
      $this->eventsManager = new EventsManager();
      $this->eventsManager->attach('micro:beforeNotFound', $this->events['micro:beforeNotFound']);
      $this->eventsManager->attach('micro:afterHandleRoute', $this->events['micro:afterHandleRoute']);

  }

  public function enterServices() {
    $di = \Phalcon\DI::getDefault();
    $di->set('conexion', function() use ($di) {
      $config = $di->get('config');
      $host = $config->db->host;
      $dbname = $config->db->dbname;
      return new \PDO("mysql:host=$host;dbname=$dbname",
         $config->db->username,
         $config->db->password,
         array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION)
        );
    });
  }

  private function registerServices()
  {
      $this->di = new \Phalcon\DI\FactoryDefault();

      foreach($this->services as $key => $service) {
          if (!$this->di->has($key)) {
              $this->di->set($key, $service, true);
          }
      }

  }

  private function configureRootPath()
  {
      $this->get('/', function () {
          if (getenv('PHP_ENV') !== 'production') {

              $routes = $this->getRouter()->getRoutes();
              $routeDefinitions = array(
                  'GET' => [],
                  'POST' => [],
                  'PUT' => [],
                  'PATCH' => [],
                  'DELETE' => [],
                  'HEAD' => [],
                  'OPTIONS' => []
              );

              foreach($routes as $route){
                  $method = $route->getHttpMethods();
                  $routeDefinitions[$method][] = $route->getPattern();
              }

              return [
                  'routeDefinitions' => $routeDefinitions,
                  'registeredNamespaces' => $this->getNamespaces(),
                  'services' => array_keys($this->di->getServices())
              ];
          }

          return [];
      });

  }

  private function getNamespaces() {

      $map = require $this->ROOT_PROJECT_PATH . '/vendor/composer/autoload_namespaces.php';
      $namespaces = array();
      foreach ($map as $k => $values) {
          $k = trim($k, '\\');
          if (!isset($namespaces[$k])) {
              $dir = '/' . str_replace('\\', '/', $k) . '/';
              $namespaces[$k] = implode($dir . ';', $values) . $dir;
          }
      }
      $namespaces = array_merge($namespaces, $this->loader->getNamespaces());

      return $namespaces;
  }

  private function loadModule()
  {
      $this->enterServices();
      $collections = $this->getCollections();
      if ($collections instanceof \Phalcon\Mvc\Micro\CollectionInterface) {
          $this->mount($collections);
          return;
      }

      if (is_array($collections)) {

          foreach ($collections as $collection) {
              if ($collection instanceof \Phalcon\Mvc\Micro\CollectionInterface) {
                  $this->mount($collection);
              }
          }
      }
  }

  private function getCollections() {
    $clientes = new Collection();
    $clientes->setPrefix('/api')
    ->setHandler('\vendimia\Controllers\clientesController')
    ->setLazy(true);
    $clientes->get("/clientes","consultarClientes");
    $clientes->get("/clientes/{id}","consultarClientesPorID");
    $clientes->post("/clientes","agregarCliente");
    $clientes->put("/clientes/{idCliente}","actualizarCliente");
    $clientes->delete("/clientes/{idCliente}","eliminarCliente");

    $articulos = new Collection();
    $articulos->setPrefix('/api')
    ->setHandler('\vendimia\Controllers\articulosController')
    ->setLazy(true);
    $articulos->get("/articulos","consultarArticulos");
    $articulos->get("/articulos/{id}","consultarArticulosPorId");
    $articulos->post("/articulos","agregarArticulo");
    $articulos->put("/articulos/{id}","actualizarArticulo");
    $articulos->delete("/articulos/{id}","eliminarArticulo");

    return [$clientes,$articulos];
  }

  public function run() {
    $this->setDI($this->di);
    $this->setEventsManager($this->eventsManager);
    $this->configureRootPath();
    $this->loadModule();
    $this->handle();
  }
}
