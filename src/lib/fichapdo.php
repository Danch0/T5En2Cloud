<?php
class Fichapdo {

	// public function __construct($pdo) {
	// 	parent::__construct("ficha_ma","id_ficha",$pdo);
 //  }
  protected $db;
  protected $logger;
  public function __construct($container)
  {
    $this->logger = $container->get('logger');
    $this->db = new MyPDO("ficha_ma","id_ficha",$container->get('db'));
  }

  public function __invoke($req, $res, $args)
  {
    $response = array();
    $method = $req->getMethod();
    $uri = $req->getUri();
    $this->logger->addInfo($method."->".$uri);

    if($method == 'GET'){
      if(!array_key_exists('filter', $args) && !array_key_exists('id', $args))
        $response = $this->db->getAll();
      else  
        $response = $this->db->getRegistro(array(array_key_exists('filter', $args)? $args['filter']:'id_ficha' => $args['id']));
    }
    if($method == 'POST'){
      $data = $req->getParsedBody();
      if (is_null($data)) {
        return json_encode(array('estado'=>false,'mensaje'=>'Los datos recibidos no corresponden a formato valido'));
      };
      $response = $this->db->post($data);
    }
    if($method == 'PUT')
      $response = $this->db->getAll();
    if($method == 'DELETE')
      $response = $this->db->getAll();

    $this->logger->addInfo("Response->".$response['estado'].":Message->".$response['mensaje']);
    return json_encode($response['estado']? $response['result']:array());
  }
}
?>