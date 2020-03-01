<?php
function connectionDB(){
	try{
		$user = "root";
		$mdp = "";
		$bdd = new PDO('mysql:host=localhost:3306;dbname=mydb;charset=utf8', $user, $mdp);
	}
	catch (Exception $e){
		die('Erreur : '.$e->getMessage());
	}

	if($bdd){
		return $bdd;
	}
}
?>