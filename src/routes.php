<?php
// ###########################
//          ficha 
// ###########################
// Obtener todos los Registros
$app->group('/ficha[/{id}/{filter}]', function () {
  $this->map(['GET', 'DELETE', 'POST', 'PUT'], '', 'Fichapdo');
});

$app->get('/fichas', 'Fichapdo');
