<?php
ini_set('display_errors',1);
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

$all_type = array("Prestataire","Service");

if(in_array($type, $all_type)) {
    if($type == "Service") {
        $users = $bdd->prepare('SELECT * FROM service INNER JOIN tariff ON tariff.serviceID = service.serviceID AND service.nameService LIKE :q INNER JOIN validitydate v on tariff.dateID = v.dateID GROUP BY tariffID ORDER BY service.serviceID DESC LIMIT 0,6');
    }else{
        $users = $bdd->prepare('SELECT * FROM service INNER JOIN tariff ON tariff.serviceID = service.serviceID INNER JOIN validitydate v on tariff.dateID = v.dateID INNER JOIN serviceprovider WHERE tariff.providerID = serviceprovider.providerID AND tariff.agency = serviceprovider.agency AND ( CONCAT(serviceprovider.lastName," ",serviceprovider.firstName) LIKE :q OR CONCAT(serviceprovider.firstName," ",serviceprovider.lastName) LIKE :q) OR email LIKE :q GROUP BY tariffID ORDER BY service.serviceID DESC LIMIT 0,6');
    }

    $users->execute(array(
        'q' => '%'.$data.'%'
    ));
}


if($users->rowCount() > 0){
foreach ($users as $service_presta){

echo '
    <div class="row" id="'.$service_presta['tariffID'].' '.$service_presta['language'].'service_prestatairedata";>
                <div class="col" id="'.$service_presta['tariffID'].'data'.'" >
                    '. $service_presta['tariffID'] .' '.$service_presta['startTime'] .' '.$service_presta['endTime'].'<br>'.$service_presta['nameService'].' '.$service_presta['priceService'] .'€/'.$service_presta['priceTypeService'].'
                </div>
                <div class="col-6">
                    <input type="button" class="co btn btn-secondary" value="Supprimer le service"  onclick="suppression('.$service_presta['tariffID'].','. $service_presta['language'].',"service_prestataire")"/>
                </div>
            </div>
    ';
}
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
