<?php
session_start();

$connected = isset($_SESSION['email']) ? true : false;

$json = file_get_contents("http://localhost/Conciergerie/API_TEST_URI/v1/tariff/".$_GET['serviceID'], false);
$tariff_info = json_decode($json, true);

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="description" content="Projet Annuel">
    <link rel="stylesheet" type="text/css" href="CSS/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="CSS/CSS_luxery.css">
    <script src="api_link.js" charset="utf-8"></script>
    <title>Orbis</title>
</head>
<body>
<?php	require_once('Pages/header.php'); ?>
<main>
    <section class="corps" id="first_section">
        <form action="" method="post">
            <h1>Nom du Service : <?php echo $tariff_info[0]['nameService'];$i=0; ?></h1>
            <br/>
            <?php foreach ($tariff_info as $t) {
                $min=$tariff_info[$i]["minimumType"];
                $id=$tariff_info[$i]["tariffID"];
                echo sprintf("<div id=\"table\" class=\"container\">
                <div class=\"row\">
                    <div class=\"col\">
                        <!-- Description -->
                        <div class=\"row\">
                            <div class=\"col\">
                                <label class=\"text\">Service %s</label>
                            </div>
                        </div>
                        <br/>
                        <!-- Coût -->
                        <div class=\"row\">
                            <div class=\"col\">
                                <label>%s : %s €</label>
                            </div>
                        </div>
                        <br/>
                        <div class=\"row\">
                            <div class=\"col\">
                                <input class=\"co btn btn-secondary\" type=\"button\" onclick=\"modif_data($id)\" value=\"Réservé (volume minimum : $min)\" id=\"reserve\" name=\"ajouter_panier\"/>
                            </div>
                        </div>
                    </div>
                    <div class=\"col\" id=\"$id\" style=\"display:none\">
                      <form  action=\"verif_service.php?tariffID=$id\" method=\"post\">
                        <input type=\"text\" name=\"sujet\" placeholder=\"Nouveau sujet\">
                        <br>
                        <input type=\"submit\"  value=\"Valider la Modification\">
                        <br>
                      </form>
                    </div>
                </div>
            </div>",$tariff_info[$i]["typeService"]=="1"?"récurrent":"non récurrent",$tariff_info[$i]["priceTypeService"]==0?"Tarif Horaire":"Tarif", $tariff_info[$i++]["priceService"]);
            }
            ?>
        </form>
    </section>
</main>
<?php require_once('Pages/footer.php'); ?>
