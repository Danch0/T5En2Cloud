<?php
// ###########################
//          ficha 
// ###########################
// Obtener todos los Registros
$app->get('/fichas', function($req, $res, $args) {
  $this->logger->addInfo($req->getMethod()."->".$req->getUri());
  $mapper = new Fichapdo($this->db);
  $response = $mapper->getAll();
  $this->logger->addInfo("Response->".$response['estado'].":Message->".$response['mensaje']);
  return json_encode($response['estado']? $response['result']:array());
});