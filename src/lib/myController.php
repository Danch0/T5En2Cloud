<?php
// namespace lib\mycontroller;
class MyController {

  protected $db;
  protected $logger;
  protected $tableId;
  public function __construct($container,$tableName,$tableNameGet,$tableId)
  {
    // Obtenemos el objeto logger del contenedor de slim y lo asignamos a la varible $logger de la clase Ficha
    $this->logger = $container->get('logger');
    /**
    * Crear un objeto del modelo 
    * MyPDO("nombre de la tabla para PUT, DELETE, POST", "nombre de la tabla para GET", Obtenemos el objeto PDO del contenedor de slim para db)
    * este objeto lo asignamo a la variable $db de la clase Ficha
    */
    $this->db = new MyPDO($tableName,$tableNameGet,$container->get('db'));
    // Asignamos el id de la tabla
    $this->tableId = $tableId;
  }

  public function __invoke($req, $res, $args)
  {
    $response = array();
    $data = array();
    // Obtenemos el metodo del request
    $method = $req->getMethod();
    // Obtenemos la url del request
    $uri = $req->getUri();
    // Guardamos el log con el metodo y url del request
    $this->logger->addInfo($method."->".$uri);

    if($method == 'GET'){
      $response = $this->get($args);
    }
    if($method == 'DELETE') {
      $response =  $this->delete($args);
    }
    if ($method == 'POST' || $method == 'PUT') {
      $data = $req->getParsedBody();
      if (is_null($data)) {
        return json_encode(array('estado'=>false,'mensaje'=>'Los datos recibidos no corresponden a formato valido'));
      };

      if($method == 'POST') {
        $response = $this->post($data);
      }
      if($method == 'PUT') {
        $response = $this->put($args,$data);
      }
    }

    $this->logger->addInfo("Response->".$response['estado'].":Message->".$response['mensaje']);
    return json_encode($response['estado']? $response['result']:array('estado'=>$response['estado'], 'mensaje'=>$response['mensaje']));
  }

  public function get($args = null)
  {
    $limit = array_key_exists('limit', $args) ? string($args['limit']) : "1000";
    $filter = array_key_exists('filter', $args)? $args['filter']:$this->tableId;
    // Comprobamos si no existe el parametro filter ni id obtenemos todos los registros
    if(!array_key_exists('filter', $args) && !array_key_exists('id', $args))
      return $this->db->getAll($limit);
    else
      return $this->db->getRegistro(array(array_key_exists('filter', $args)? $args['filter']:$this->tableId => $args['id']));
  }

  public function delete($args = null)
  {
    return $this->db->delete(array($this->tableId => $args['id']));
  }

  public function post($data = null)
  {
    return $this->db->post($data);
  }

  public function put($args, $data = null)
  {
    return $this->db->put(array($this->tableId => $args['id']), $data);
  }
}
?>