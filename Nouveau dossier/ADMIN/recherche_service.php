<?php
include('config.php');
$bdd = connectionDB();

if(isset($_GET['data']) && !empty($_GET['data'])){
    $data = $_GET['data'];
}else{
    $data = "";
}

$services = $bdd->prepare('SELECT * FROM service WHERE nameService LIKE :q ORDER BY serviceID DESC LIMIT 0,6');

$services->execute(array(
        'q' => '%'.$data.'%'
    ));



if($services->rowCount() > 0){
    while($service = $services->fetch()) {

        echo '<div class="row" id="'.$service['serviceID'].'">';
        echo '<div class="col" id="'.$service['serviceID'].'data">';
        echo $service['serviceID'].' - '.$service['nameService'].' '.$s;
        echo '</div>';
        echo '<div class="col-6">';
        echo '<input type="button" class="co btn btn-secondary" value="Supprimer le compte"  onclick="suppression_user('.$user[$t].')"/>';
        echo '<input type="button" class="co btn btn-secondary" onclick="modif_data(\''.$service['serviceID'].'service_search\')" value="Modifier les informations utilisateur"/><br>';
        echo '</div>';
        echo '<div class="col" id="'.$service['serviceID'].'service_search" style="display:none">';
        echo '
    <form  action="verif_admin.php?id='.$service['serviceID'].'" method="post">
    <input type="text" name="nameServiceFr" value="'.$service['nameServiceFr'].'" placeholder="Nouveau nom en français">
    <input type="text" name="nameServiceEn" value="'.$service['nameServiceEn'].'" placeholder="Nouveau nom en anglais">
    <input type="text" name="descriptionFr" value="'.$service['descriptionFr'].'" placeholder="Nouvelle description en français">
    <input type="text" name="descriptionEn" value="'.$service['descriptionEn'].'" placeholder="Nouvelle description en anglais">
    <input type="submit" value="Valider la Modification">
    <br>
  </form>
        ';
        echo '</div></div>';
    }
    echo '</div>';
}else{
        echo '<span style="color:#ff0000">' .'Aucun résultat pour : '.$data.'</span>';

}
?>
