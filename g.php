<?php

ob_start();
//session_start();

require_once 'includes/ConnectionManager.php';
require_once 'includes/PeopleManager.php';

/* For Angular */
$post = json_decode(file_get_contents('php://input'),true);

if(!isset($post['json'])) {
	$responseMessage = "Empty Data!";
	echo $response = json_encode(array(-1, $responseMessage));
	exit();
}

$cm = ConnectionManager::getInstance();
$pm = new PeopleManager();

$params = json_decode($post["json"], true);
$action = filter_var($params['action'], FILTER_SANITIZE_STRING);

if($action == "get-people") {
	$people = $pm->getPeople();
	echo json_encode($people);
	exit();
} else if($action == "add-person") {
	$name    = filter_var($params["name"], FILTER_SANITIZE_STRING);
	$surname = filter_var($params["surname"], FILTER_SANITIZE_STRING);
	$age     = filter_var($params["age"], FILTER_SANITIZE_NUMBER_INT);
	$email   = filter_var($params["email"], FILTER_SANITIZE_STRING);

	if($pm->addPerson($name, $surname, $age, $email)) {
		echo json_encode($pm->getPeople());
		exit();
	} else {

	}
} else if($action == "delete-person") {
	$id = filter_var($params["id"], FILTER_SANITIZE_NUMBER_INT);
	if($pm->deletePerson($id)){
		echo json_encode($pm->getPeople());
		exit();
	}
}

?>