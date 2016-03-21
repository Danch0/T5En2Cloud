<?php
require __DIR__ . '/../lib/myConnection.php';
require __DIR__ . '/../lib/doctor.php';
require __DIR__ . '/../lib/especialidad.php';
require __DIR__ . '/../lib/paciente.php';
require __DIR__ . '/../lib/login.php';
require __DIR__ . '/../lib/usuario.php';
require __DIR__ . '/../lib/rol.php';
require __DIR__ . '/../lib/tipoMedia.php';
require __DIR__ . '/../lib/ficha.php';
require __DIR__ . '/../lib/endodoncia.php';
require __DIR__ . '/../lib/endodonciaDiente.php';
require __DIR__ . '/../lib/nota.php';
require __DIR__ . '/../lib/media.php';
$doctor = new Doctor();
$especialidad = new Especialidad();
$paciente = new Paciente();
$login = new Login();
$usuario = new Usuario();
$rol = new Rol();
$tipoMedia = new TipoMedia();
$ficha = new Ficha();
$endodoncia = new Endodoncia();
$endodoncia_diente = new EndodonciaDiente();
$nota = new Nota();
$media = new Media();
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
  echo $doctor->post($req);
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
// ###########################
//         Pacientes 
// ###########################
// Obtener todos los Registros
$app->get('/pacientes', function($req, $res, $args) use($paciente) {
  // Devolvemos un array asociativo como un string JSON.
  echo json_encode($paciente->get());
});
// Obtener registro por medio de su id
$app->get('/paciente/{id}', function($req, $res, $args) use($paciente) {
  // Devolvemos un array asociativo como un string JSON.
  echo json_encode($paciente->getRegistro($args['id']));
});
// Alta de un nuevo
$app->post('/paciente',function($req, $res, $args) use($paciente) {
  echo $estado = $paciente->post($req);
});
// Programamos la ruta de borrado en la API REST (DELETE)
$app->delete('/paciente/{id}', function($req, $res, $args) use($paciente) {
  echo $paciente->delete($args['id']);
});
// Actualización de datos (PUT)
$app->put('/paciente/{id}', function($req, $res, $args) use($paciente) {
  echo $paciente->put($req, $args['id']);
});
// ###########################
//         Usuarios 
// ###########################
// Obtener todos los Registros
$app->get('/usuarios', function($req, $res, $args) use($usuario) {
  // Devolvemos un array asociativo como un string JSON.
  echo json_encode($usuario->get());
});
// Obtener registro por medio de su id
$app->get('/usuario/{id}', function($req, $res, $args) use($usuario) {
  // Devolvemos un array asociativo como un string JSON.
  echo json_encode($usuario->getRegistro($args['id']));
});
// Alta de un nuevo
$app->post('/usuario',function($req, $res, $args) use($usuario) {
  echo $estado = $usuario->post($req);
});
// Programamos la ruta de borrado en la API REST (DELETE)
$app->delete('/usuario/{id}', function($req, $res, $args) use($usuario) {
  echo $usuario->delete($args['id']);
});
// Actualización de datos (PUT)
$app->put('/usuario/{id}', function($req, $res, $args) use($usuario) {
  echo $usuario->put($req, $args['id']);
});
// ###########################
//         Login 
// ###########################
// Validar usuario
$app->post('/login',function($req, $res, $args) use($login) {
  echo $login->login($req);
});
// ###########################
//         Roles 
// ###########################
// Obtener todos los Registros
$app->get('/roles', function($req, $res, $args) use($rol) {
  // Devolvemos un array asociativo como un string JSON.
  echo json_encode($rol->get());
});
// Obtener registro por medio de su id
$app->get('/rol/{id}', function($req, $res, $args) use($rol) {
  // Devolvemos un array asociativo como un string JSON.
  echo json_encode($rol->getRegistro($args['id']));
});
// Alta de un nuevo
$app->post('/rol',function($req, $res, $args) use($rol) {
  echo $estado = $rol->post($req);
});
// Programamos la ruta de borrado en la API REST (DELETE)
$app->delete('/rol/{id}', function($req, $res, $args) use($rol) {
  echo $rol->delete($args['id']);
});
// Actualización de datos (PUT)
$app->put('/rol/{id}', function($req, $res, $args) use($rol) {
  echo $rol->put($req, $args['id']);
});
// ###########################
//         tipo_media 
// ###########################
// Obtener todos los Registros
$app->get('/tipos-media', function($req, $res, $args) use($tipoMedia) {
  // Devolvemos un array asociativo como un string JSON.
  echo json_encode($tipoMedia->get());
});
// Obtener registro por medio de su id
$app->get('/tipo-media/{id}', function($req, $res, $args) use($tipoMedia) {
  // Devolvemos un array asociativo como un string JSON.
  echo json_encode($tipoMedia->getRegistro($args['id']));
});
// Alta de un nuevo
$app->post('/tipo-media',function($req, $res, $args) use($tipoMedia) {
  echo $tipoMedia->post($req);
});
// Programamos la ruta de borrado en la API REST (DELETE)
$app->delete('/tipo-media/{id}', function($req, $res, $args) use($tipoMedia) {
  echo $tipoMedia->delete($args['id']);
});
// Actualización de datos (PUT)
$app->put('/tipo-media/{id}', function($req, $res, $args) use($tipoMedia) {
  echo $tipoMedia->put($req, $args['id']);
});
// ###########################
//          ficha 
// ###########################
// Obtener todos los Registros
$app->get('/fichas', function($req, $res, $args) use($ficha) {
  // Devolvemos un array asociativo como un string JSON.
  echo json_encode($ficha->get());
});
// Obtener registro por medio de su id
$app->get('/ficha/{id}', function($req, $res, $args) use($ficha) {
  // Devolvemos un array asociativo como un string JSON.
  echo json_encode($ficha->getRegistro($args['id']));
});
// Alta de un nuevo
$app->post('/ficha',function($req, $res, $args) use($ficha) {
  echo $ficha->post($req);
});
// Programamos la ruta de borrado en la API REST (DELETE)
$app->delete('/ficha/{id}', function($req, $res, $args) use($ficha) {
  echo $ficha->delete($args['id']);
});
// Actualización de datos (PUT)
$app->put('/ficha/{id}', function($req, $res, $args) use($ficha) {
  echo $ficha->put($req, $args['id']);
});

// ###########################
//          endodoncia 
// ###########################
// Obtener todos los Registros
$app->get('/endodoncias', function($req, $res, $args) use($endodoncia) {
  // Devolvemos un array asociativo como un string JSON.
  echo json_encode($endodoncia->get());
});
// Obtener registro por medio de su id
$app->get('/endodoncia/{id}', function($req, $res, $args) use($endodoncia) {
  // Devolvemos un array asociativo como un string JSON.
  echo json_encode($endodoncia->getRegistro($args['id']));
});
// Alta de un nuevo
$app->post('/endodoncia',function($req, $res, $args) use($endodoncia) {
  echo $endodoncia->post($req);
});
// Programamos la ruta de borrado en la API REST (DELETE)
$app->delete('/endodoncia/{id}', function($req, $res, $args) use($endodoncia) {
  echo $endodoncia->delete($args['id']);
});
// Actualización de datos (PUT)
$app->put('/endodoncia/{id}', function($req, $res, $args) use($endodoncia) {
  echo $endodoncia->put($req, $args['id']);
});
// ###########################
//          endodoncia_diente 
// ###########################
// Obtener todos los Registros
$app->get('/endodoncias-dientes', function($req, $res, $args) use($endodoncia_diente) {
  // Devolvemos un array asociativo como un string JSON.
  echo json_encode($endodoncia_diente->get());
});
// Obtener registro por medio de su id
$app->get('/endodoncia-diente/{id}[/{filter}]', function($req, $res, $args) use($endodoncia_diente) {
  // Devolvemos un array asociativo como un string JSON.
  echo json_encode($endodoncia_diente->getRegistro($args['id'],array_key_exists('filter', $args)? $args['filter']:""));
});
// Alta de un nuevo
$app->post('/endodoncia-diente',function($req, $res, $args) use($endodoncia_diente) {
  echo $endodoncia_diente->post($req);
});
// Programamos la ruta de borrado en la API REST (DELETE)
$app->delete('/endodoncia-diente/{id}', function($req, $res, $args) use($endodoncia_diente) {
  echo $endodoncia_diente->delete($args['id']);
});
// Actualización de datos (PUT)
$app->put('/endodoncia-diente/{id}', function($req, $res, $args) use($endodoncia_diente) {
  echo $endodoncia_diente->put($req, $args['id']);
});
// ###########################
//          Notas 
// ###########################
// Obtener todos los Registros
$app->get('/notas', function($req, $res, $args) use($nota) {
  // Devolvemos un array asociativo como un string JSON.
  echo json_encode($nota->get());
});
// Obtener registro por medio de su id
$app->get('/nota/{id}', function($req, $res, $args) use($nota) {
  // Devolvemos un array asociativo como un string JSON.
  echo json_encode($nota->getRegistro($args['id']));
});
// Obtener registro por medio de su id
$app->get('/nota/{idFicha}/{idDiente}', function($req, $res, $args) use($nota) {
  // Devolvemos un array asociativo como un string JSON.
  echo json_encode($nota->getRegistros($args['idFicha'],$args['idDiente']));
});
// Alta de un nuevo
$app->post('/nota',function($req, $res, $args) use($nota) {
  echo $nota->post($req);
});
// Programamos la ruta de borrado en la API REST (DELETE)
$app->delete('/nota/{id}', function($req, $res, $args) use($nota) {
  echo $nota->delete($args['id']);
});
// Actualización de datos (PUT)
$app->put('/nota/{id}', function($req, $res, $args) use($nota) {
  echo $nota->put($req, $args['id']);
});
// ###########################
//           Media 
// ###########################
// Obtener todos los Registros
$app->get('/medias', function($req, $res, $args) use($media) {
  // Devolvemos un array asociativo como un string JSON.
  echo json_encode($media->get());
});
// Obtener registro por medio de su id
$app->get('/media/{id}', function($req, $res, $args) use($media) {
  // Devolvemos un array asociativo como un string JSON.
  echo json_encode($media->getRegistro($args['id']));
});
// Alta de un nuevo
$app->post('/media',function($req, $res, $args) use($media) {
  echo $media->post($req);
});
// Programamos la ruta de borrado en la API REST (DELETE)
$app->delete('/media/{id}', function($req, $res, $args) use($media) {
  echo $media->delete($args['id']);
});
// Actualización de datos (PUT)
$app->put('/media/{id}', function($req, $res, $args) use($media) {
  echo $media->put($req, $args['id']);
});