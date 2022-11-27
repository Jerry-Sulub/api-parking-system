<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once "config/Core.php";
require_once "config/Routes.php";
require_once "config/Connection.php";
require_once "controllers/Place.php";
require_once "controllers/Fee.php";
require_once "models/PlaceModel.php";
require_once "models/FeeModel.php";

if (isset($_GET['c'])) {

	$controlador = cargarControlador($_GET['c']);

	if (isset($_GET['a'])) {
		if (isset($_GET['id'])) {
			cargarAccion($controlador, $_GET['a'], $_GET['id']);
		} else {
			cargarAccion($controlador, $_GET['a']);
		}
	} else {
		cargarAccion($controlador, ACCION_PRINCIPAL);
	}
} else {

	$controlador = cargarControlador(CONTROLADOR_PRINCIPAL);
	$accionTmp = ACCION_PRINCIPAL;
	$controlador->$accionTmp();
}
