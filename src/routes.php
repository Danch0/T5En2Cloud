<?php
// ###########################
//          ficha 
// ###########################
// Creamos un grupo con parametros opcionales para ficha
// Responde a /ficha para POST y PUT, /ficha/id para GET por id_ficha y DELETE por id_ficha,
// ficha/parametro/nombreColumna para GET especificos, ejem: ficha/F0001/ficha_txt
$app->group('/ficha[/{id}[/{filter}]]', function () {
	// Asignamos el controlador correspondiente
  $this->map(['GET', 'DELETE', 'POST', 'PUT'], '', 'Ficha');
});
// En prural retornamos todas las fichas, asignamos el controlador correspondiente
$app->get('/fichas[/{limit}]', 'Ficha');
// ########################### Login  ###########################
// Validar usuario
$app->post('/login', 'Login');
// Desactivar token
$app->put('/logout', 'Login');
// ########################### doctor ###########################
$app->group('/doctor[/{id}[/{filter}]]', function () {
  $this->map(['GET', 'DELETE', 'POST', 'PUT'], '', 'Doctor');
});
$app->get('/doctores', 'Doctor');
// ########################### endododoncia ###########################
$app->group('/endodoncia[/{id}[/{filter}]]', function () {
  $this->map(['GET', 'DELETE', 'POST', 'PUT'], '', 'Endodoncia');
});
$app->get('/endodoncias', 'Endodoncia');
// ########################### endododoncia-diente ###########################
$app->group('/endodoncia-diente[/{id}[/{filter}]]', function () {
  $this->map(['GET', 'DELETE', 'POST', 'PUT'], '', 'EndodonciaDiente');
});
$app->get('/endodoncias-dientes', 'EndodonciaDiente');
// ########################### Funciones ###########################
$app->post('/function', 'Functions');
// ########################### especialidad ###########################
$app->group('/especialidad[/{id}[/{filter}]]', function () {
  $this->map(['GET', 'DELETE', 'POST', 'PUT'], '', 'Especialidad');
});
$app->get('/especialidades', 'Especialidad');
// ########################### expediente ###########################
$app->group('/expediente[/{id}[/{filter}]]', function () {
  $this->map(['GET', 'DELETE', 'PUT'], '', 'Expediente');
});
$app->get('/expedientes', 'Expediente');
// ########################### factura ###########################
$app->group('/factura[/{id}[/{filter}]]', function () {
  $this->map(['GET', 'DELETE', 'POST', 'PUT'], '', 'Factura');
});
$app->get('/facturas', 'Factura');
// ########################### media ###########################
$app->group('/media[/{id}[/{filter}]]', function () {
  $this->map(['GET', 'DELETE', 'POST', 'PUT'], '', 'Media');
});
// ########################### nota ###########################
$app->group('/nota[/{id}[/{filter}]]', function () {
  $this->map(['GET', 'DELETE', 'POST', 'PUT'], '', 'Nota');
});
$app->get('/notas', 'Nota');
// ########################### paciente ###########################
$app->group('/paciente[/{id}[/{filter}]]', function () {
  $this->map(['GET', 'DELETE', 'POST', 'PUT'], '', 'Paciente');
});
$app->get('/pacientes[/{limit}]', 'Paciente');
// ########################### referencia ###########################
$app->group('/referencia[/{id}[/{filter}]]', function () {
  $this->map(['GET', 'DELETE', 'POST', 'PUT'], '', 'Referencia');
});
$app->get('/referencias', 'Referencia');
// ########################### rol ###########################
$app->group('/rol[/{id}[/{filter}]]', function () {
  $this->map(['GET', 'DELETE', 'POST', 'PUT'], '', 'Rol');
});
$app->get('/roles', 'Rol');
// ########################### usuario ###########################
$app->group('/usuario[/{id}[/{filter}]]', function () {
  $this->map(['GET', 'DELETE', 'POST', 'PUT'], '', 'Usuario');
});
$app->get('/usuarios', 'Usuario');