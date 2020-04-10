<?php
require_once ('database.env.php');

function connectionDB(){
    try{
        $options = [
            'host=' . DB_HOST,
            'dbname=' . DB_NAME,
            'port=' . DB_PORT
        ];
        $pdo = new PDO(DB_DRIVER . ':' . join( ';', $options), DB_USER, DB_PASSWORD);
    } catch (Exception $e){
        die('Erreur : '.$e->getMessage());
    }

    if($pdo){
        return $pdo;
    }
}
?>
