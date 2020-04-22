<?php
include('config.php');
$bdd = connectionDB();

if(isset($_GET['data']) && !empty($_GET['data'])){
    $data = $_GET['data'];
}else{
    $data = "";
}

if(isset($_GET['type']) && !empty($_GET['type'])) {
    $type = $_GET['type'];
}

$all_type = array("Prestataire","Client"); // a rendre dynamique

if(in_array($type, $all_type)) {
    if($type == "Client") {
        $users = $bdd->prepare('SELECT * FROM client WHERE ( CONCAT(lastName," ",FirstName) LIKE :q OR CONCAT(FirstName," ",lastName) LIKE :q OR email LIKE :q ) ORDER BY lastName DESC LIMIT 0,6');
    }else{
        $users = $bdd->prepare('SELECT * FROM serviceprovider WHERE ( CONCAT(lastName," ",FirstName) LIKE :q OR CONCAT(FirstName," ",lastName) LIKE :q OR email LIKE :q ) ORDER BY lastName DESC LIMIT 0,6');
    }

    $users->execute(array(
        'q' => '%'.$data.'%'
    ));
}


if($users->rowCount() > 0){
while($user = $users->fetch()) {
    $i = $bdd->prepare('SELECT interventionID FROM intervention WHERE clientID = :id');
    $i->execute(array(':id'=>$user['clientID']));

    $t = $user['clientID']?"clientID":"providerID";

    echo '<div class="row" id="'.$user[$t].'">';
    echo '<div class="col" id="'.$user[$t].$user["agency"].$t.'">';
    echo $user[$t].' - '.$user['lastName'].' '.$user['firstName'];

    echo '</div>';
    echo '<div class="col-6">';
    echo '<input type="button" class="co btn btn-secondary" value="Supprimer le compte"  onclick="suppression_user('.$user[$t].','.$t.')"/>';
    echo '<input type="button" class="co btn btn-secondary" onclick="modif_data(\''.$user[$t].$user["agency"].$t.'data_search\')" value="Modifier les informations utilisateur"/><br>';
if($i->rowCount() > 0) {
    echo '<input type="button" value="Exporter les interventions" OnClick="window.location.href=recherche_intervention?'.$t.'='.$user[$t].'&agency'.$user["agency"].'" readonly  />';
}
    echo '</div>';
    echo '<div class="col" id="'.$user[$t].$user["agency"].$t.'data_search" style="display:none">';
    echo '
    <form  action="verif_admin.php?'.$t.'='.$user[$t].'" method="post">
    <input type="hidden" name="agency" value="'.$user["agency"].'">
    <input type="text" name="nom_" value="'.$user['lastName'].'" placeholder="Nouveau nom">
    <br>
    <input type="text" name="prenom_" value="'.$user['firstName'].'" placeholder="Nouveau Prénom">
    <br>
    <input type="submit" value="Valider la Modification">
    <br>
  </form>
        ';
    echo '</div></div>';
    }
    echo '</div>';
}else{
    
    if(!in_array($type, $all_type)) {
    echo '<span style="color:#ff0000">' .'Aucun résultat pour : '.$data.'</span>';

    }elseif($data) {
        echo '<span style="color:#ff0000">' .'Aucun résultat pour un '.$type.' nommé '.$data.'</span>';

    }else{
        echo '<span style="color:#ff0000">' .'Aucun résultat pour un '.$type.'</span>';
    }
}
?>
