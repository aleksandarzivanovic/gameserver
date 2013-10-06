<?php

session_start();

// Report all PHP errors
error_reporting(-1);

// Same as error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);



include './core/VariablesTypes.php';
include './core/Variables.php';
include './core/VariableAssignment.php';
include './core/ServerControll.php';
include './core/Database.php';
include './core/Arrays.php';

$query123[0] = Database::prepare ('SELECT * FROM `server_list` WHERE `ownerID` = :ownerID ORDER BY `createdAt` ASC;');
$query123[1] = Database::prepare ('SELECT * FROM `messages` WHERE `clientID` = :ownerID ORDER BY `createdAt` DESC;');


Database::set(':ownerID', 'Owner One', $query123[0]);
Database::set(':ownerID', 'Owner Two', $query123[1]);

//Database::_queries();
/*
VariableFactory::assignValue($serverList, [
		'callback' => function () {
			Database::prepare ('SELECT * FROM `server_list`;');
			$return['value'] = Database::multiFetch ();
			$return['message'] = 'List of all servers.';

			return $return;
		},
	]);
	8?