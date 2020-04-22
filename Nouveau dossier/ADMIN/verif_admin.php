<?php
include('config.php');
$bdd = connectionDB();

if(isset($_POST['nom_']) && !empty($_POST['nom_']) &&
    isset($_POST['agency']) && !empty($_POST['agency']) &&
    isset($_GET['clientID']) || isset($_GET['providerID'])) {

    if (!empty($_GET['clientID'])) {
        $update_nom = $bdd->prepare('UPDATE client SET lastName = ? WHERE clientID = ? AND agency = ? ');
        $id = $_GET['clientID'];
    }else{
        $update_nom = $bdd->prepare('UPDATE serviceprovider SET lastName = ? WHERE providerID = ? AND agency = ? ');
        $id = $_GET['providerID'];
    }

    $update_nom->execute(array(
        $_POST['nom_'],
        $id,
        $_POST['agency']
    ));
}

if(isset($_POST['prenom_']) && !empty($_POST['prenom_']) &&
    isset($_POST['agency']) && !empty($_POST['agency']) &&
    isset($_GET['clientID']) || isset($_GET['providerID'])) {

    if (!empty($_GET['clientID'])) {
        $update_prenom = $bdd->prepare('UPDATE client SET firstName = ? WHERE clientID = ? AND agency = ? ');
        $id = $_GET['clientID'];
    }else{
        $update_prenom = $bdd->prepare('UPDATE serviceprovider SET firstName = ? WHERE providerID = ? AND agency = ?');
        $id = $_GET['providerID'];
    }

    $update_prenom->execute(array(
        $_POST['prenom_'],
        $id,
        $_POST['agency']
    ));
}

if(isset($_POST['priceTypeService']) && !empty($_POST['priceTypeService']) &&
    isset($_GET['id']) && !empty($_GET['id'])) {

    if($_POST['priceTypeService']=="Facturation à la tâche") $priceType = 0; elseif ($_POST['priceTypeService']=="Facturation à l'heure") $priceType = 1; else $priceType = 2;

    $update_priceTypeService = $bdd->prepare('UPDATE service SET priceTypeService = ? WHERE serviceID = ?');

    $update_priceTypeService->execute(array(
        $priceType,
        $_GET['id']
    ));
}

if(isset($_POST['nameServiceFr']) && !empty($_POST['nameServiceFr']) &&
    isset($_GET['id']) && !empty($_GET['id'])) {

    $update_nameService = $bdd->prepare('UPDATE service SET nameService = ? WHERE serviceID = ? AND language = ?');

    $update_nameService->execute(array(
        $_POST['nameServiceFr'],
        $_GET['id'],
        "Fr"
    ));
}
if(isset($_POST['nameServiceEn']) && !empty($_POST['nameServiceEn']) &&
    isset($_GET['id']) && !empty($_GET['id'])) {

    $update_nameService = $bdd->prepare('UPDATE service SET nameService = ? WHERE serviceID = ? AND language = ?');

    $update_nameService->execute(array(
        $_POST['nameServiceEn'],
        $_GET['id'],
        "En"
    ));
}
if(isset($_POST['descriptionEn']) && !empty($_POST['descriptionEn']) &&
    isset($_GET['id']) && !empty($_GET['id'])) {

    $update_nameService = $bdd->prepare('UPDATE service SET description = ? WHERE serviceID = ? AND language = ?');

    $update_nameService->execute(array(
        $_POST['descriptionEn'],
        $_GET['id'],
        "En"
    ));
}
if(isset($_POST['descriptionFr']) && !empty($_POST['descriptionFr']) &&
    isset($_GET['id']) && !empty($_GET['id'])) {

    $update_nameService = $bdd->prepare('UPDATE service SET description = ? WHERE serviceID = ? AND language = ?');

    $update_nameService->execute(array(
        $_POST['descriptionFr'],
        $_GET['id'],
        "Fr"
    ));
}

if(isset($_GET['addService']) && !empty($_GET['addService']) &&
    isset($_POST['nameServiceFr']) && !empty($_POST['nameServiceFr']) &&
    isset($_POST['nameServiceEn']) && !empty($_POST['nameServiceEn']) &&
    isset($_POST['descriptionFr']) && !empty($_POST['descriptionFr']) &&
    isset($_POST['descriptionEn']) && !empty($_POST['descriptionEn'])) {


    $request = $bdd->prepare('SELECT serviceID FROM service WHERE serviceID= :id');
    $find = false;
    $id = 1;
    while (!$find) {
        $request->execute([
            'id' => $id
        ]);
        $n_id = $request->rowCount();
        if ($n_id != 0) {
            $id ++;
        } else {
            $find = true;
        }
    }

    $request = $bdd->prepare('INSERT INTO service(serviceID, language, nameService, description) VALUES(:serviceID, :language, :nameService, :description)');

    $request->execute([
        'serviceID' => $id,
        'language' => "fr",
        'nameService' => htmlspecialchars($_POST['nameServiceFr']),
        'description' => htmlspecialchars($_POST['descriptionFr'])
    ]);

    $request->execute([
        'serviceID' => $id,
        'language' => "en",
        'nameService' => htmlspecialchars($_POST['nameServiceEn']),
        'description' => htmlspecialchars($_POST['descriptionEn'])
    ]);

}


header('Location: index.php');

?>
<!-- Rajouter des #positions a la fin des requetes ( plus des div aux id correspondant ) -->
