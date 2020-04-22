<?php
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename=file.csv');
echo "\xEF\xBB\xBF";

include('config.php');
$bdd = connectionDB();

if (isset($_GET["clientID"]) && isset($_GET['agency'])) {
    $interventions = $bdd->prepare('SELECT * FROM intervention INNER JOIN service ON intervention.serviceID = service.serviceID WHERE clientID = ? AND agency = ? ORDER BY interventionID DESC');
    $interventions->execute([$_GET['clientID'],($_GET['agency'])]);
}elseif(isset($_GET['providerID']) && isset($_GET['agency'])) {
    $interventions = $bdd->prepare('SELECT * FROM intervention INNER JOIN service ON intervention.serviceID = service.serviceID WHERE providerID = ? AND agencyProvider = ? ORDER BY interventionID DESC');
    $interventions->execute([$_GET['providerID'],($_GET['agency'])]);

}else{
    exit();
}

if($interventions->rowCount() > 0){

    $output .= 'interventionID;intervention;dateIntervention;timeIntervention;pastType;statutIntervention;clientID;agency;serviceID;providerID;agencyProvider;language;nameService;description;priceService;priceRecurrentService;priceTypeService;minimumType';

    $output .= "\n";

    while($data = $interventions->fetch())
    {
        for ($i = 0 ; $i < 19 ; $i++ ){
            $output .= $data[$i];
            $output .= ';';
        }
        $output .= "\n";
    }


    echo $output;

}else {
    echo '<span style="color:red">'.'Aucune intervention dans la BDD'.'</span>';
}

?>
