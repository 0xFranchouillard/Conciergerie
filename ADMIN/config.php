<?php
require_once('database.env');
function connectionDB(){
	try{
		$bdd = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
	}
	catch (Exception $e){
		die('Erreur : '.$e->getMessage());
	}
	if($bdd){
		return $bdd;
	}
}
?>
