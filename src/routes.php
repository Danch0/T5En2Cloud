<?php
require __DIR__ . '/../lib/myConnection.php';
require __DIR__ . '/../lib/doctor.php';
require __DIR__ . '/../lib/especialidad.php';
$doctor = new Doctor();
$especialidad = new Especialidad();

// Routes

// Obtener todos los Registros
$app->get('/doctores', function($req, $res, $args) use($doctor) {
  // Devolvemos ese array asociativo como un string JSON.
  echo json_encode($doctor->get());
  // $res->write("Hello world");
 	// return $res;
});

// $app->get('/doctores', $doctor->get());

// Obtener registro por medio de su id
$app->get('/doctor/{id}', function($req, $res, $args) use($doctor) {
  // Devolvemos ese array asociativo como un string JSON.
  echo json_encode($doctor->getDoc($args['id']));
});
// Alta de un nuevo
$app->post('/doctor',function($req, $res, $args) use($doctor) {
	// $contentType = strtoupper($req->getContentType());
	// $data = $req->getParsedBody();

	// if (is_null($data)) {
	// 	echo json_encode(array('estado'=>false,'mensaje'=>'Los datos recibidos no corresponden a formato json'));
	// 	exit();
	// };

	// var_dump($data);

// pruebas sin middleware
	// if (preg_match('/JSON/',$contentType) != 0) {
	// 	$data = json_decode($req->getBody(),true);
 //  	if (is_null($data['nombre_txt'])) {
 //  		echo json_encode(array('estado'=>false,'mensaje'=>'Los datos recibidos no corresponden a formato json'));
 //  		exit();
 //  	};

	// 	// $data = str_replace("_", "",$app->request->getBody());
	// 	// $data = $app->request->getBody();
 //  	// echo $data;
 //  	var_dump($data);
	// }else{
	// 	// Para acceder a los datos recibidos del formulario
 //  	$data = $req->getParsedBody();
 //  	// echo $data['nombre_txt'];
 //  	var_dump($data);
	// }
// pruebas sin middleware

  
  // LLamamos la funcion put para insertar los parametros en la tabla y obtenemos el estado de la misma
  // $estado = $doctor->post($req);
  // if ($estado != 0)
  //    echo json_encode(array('estado'=>true,'mensaje'=>'Datos insertados correctamente.','newId'=>$estado));
  // else
  //    echo json_encode(array('estado'=>false,'mensaje'=>'Error al insertar datos en la tabla.'));
  echo $estado = $doctor->post($req);
});
// Programamos la ruta de borrado en la API REST (DELETE)
$app->delete('/doctor/{id}', function($req, $res, $args) use($doctor)
{
	// $estado = $doctor->delete($args['id']);
 //  if ($estado == 1)
	// 	echo json_encode(array('estado'=>true,'mensaje'=>'El doctor con id:'.$args['id'].' ha sido borrado correctamente.'));
	// else
	// 	echo json_encode(array('estado'=>false,'mensaje'=>'ERROR: ese registro no se ha encontrado en la tabla.'));
	echo $doctor->delete($args['id']);
});
// Actualización de datos de usuario (PUT)
$app->put('/doctor/{id}', function($req, $res, $args) use($doctor) {
 //  $data = $req->getParsedBody();

	// if (is_null($data)) {
	// 	echo json_encode(array('estado'=>false,'mensaje'=>'Los datos recibidos no corresponden a formato json'));
	// 	exit();
	// };
  // LLamamos la funcion put para insertar los parametros en la tabla y obtenemos el estado de la misma
  // $estado = $doctor->put($req, $args['id']);
  // if ($estado != 0)
  //    echo json_encode(array('estado'=>true,'mensaje'=>'Datos actualizados correctamente.'));
  // else
  //    echo json_encode(array('estado'=>false,'mensaje'=>'Error al insertar datos en la tabla.'));
  echo $doctor->put($req, $args['id']);
});

// Obtener todos los Registros
$app->get('/especialidades', function($req, $res, $args) use($especialidad) {
  // Devolvemos un array asociativo como un string JSON.
  echo json_encode($especialidad->get());
});
// Obtener registro por medio de su id
$app->get('/especialidad/{id}', function($req, $res, $args) use($especialidad) {
  // Devolvemos un array asociativo como un string JSON.
  echo json_encode($especialidad->getRegistro($args['id']));
});
// Alta de un nuevo
$app->post('/especialidad',function($req, $res, $args) use($especialidad) {
  echo $estado = $especialidad->post($req);
});
// Programamos la ruta de borrado en la API REST (DELETE)
$app->delete('/especialidad/{id}', function($req, $res, $args) use($especialidad) {
	echo $especialidad->delete($args['id']);
});
// Actualización de datos (PUT)
$app->put('/especialidad/{id}', function($req, $res, $args) use($especialidad) {
  echo $especialidad->put($req, $args['id']);
});