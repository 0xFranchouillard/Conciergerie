<?php
session_start();
$connected = isset($_SESSION['email']) ? true : false;

if(!$connected){
    header('Location: index.php');
    exit;
}

require_once ('Pages/db.php');
$db = connectionDB();
if(isset($_SESSION['provider']) && $_SESSION['provider'] == 1) {
    $requestInfoUser = $db->prepare('SELECT * FROM ServiceProvider WHERE providerID= :providerID && agency= :agency');
    $requestInfoUser->execute([
        'providerID'=>$_SESSION['id'],
        'agency'=>$_SESSION['agencyClient']
    ]);
} else {
    $requestInfoUser = $db->prepare('SELECT * FROM Client WHERE clientID= :clientID && agency= :agency');
    $requestInfoUser->execute([
       'clientID'=>$_SESSION['id'],
       'agency'=>$_SESSION['agencyClient']
    ]);
}
$resultInfoUser = $requestInfoUser->fetch();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="description" content="Projet Annuel 2i1">
    <link rel="stylesheet" type="text/css" href="CSS/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="CSS/CSS_Luxery.css">
    <script src="JS/Ajax/userProfil.js"></script>
    <title>LuxeryService</title>
</head>
<body>
<?php require_once('Pages/header.php'); ?>
<main>

    <p style="text-align:center"><img alt="separateur" id="separateur" src="Pictures/Separateur3.png"></p>
    <br>
    <section class="body_section">
        <h1><?=_YOURINFORMATION?> :</h1>
        <br/>
        <div class="container">
            <form action="" method="post">
                <!-- Nom -->
                <div class="row">
                    <div class="col showUser" style="text-align: right">
                        <label><?= _LASTNAME.' :' ?></label>
                    </div>
                    <div class="col showUser" style="text-align: left">
                        <label><?= $resultInfoUser['lastName'] ?></label>
                    </div>
                    <div class="col modifUser" style="display: none">
                        <input type="text" placeholder="<?= _LASTNAME ?>" value="<?= $resultInfoUser['lastName'] ?>" id="lastName"/>
                    </div>
                </div>
                <!-- Prénom -->
                <div class="row">
                    <div class="col showUser" style="text-align: right">
                        <label><?= _FIRSTNAME.' :' ?></label>
                    </div>
                    <div class="col showUser" style="text-align: left">
                        <label><?= $resultInfoUser['firstName'] ?></label>
                    </div>
                    <div class="col modifUser" style="display: none">
                        <input type="text" placeholder="<?= _FIRSTNAME ?>" value="<?= $resultInfoUser['firstName'] ?>" id="firstName"/>
                    </div>
                </div>
                <!-- Email -->
                <div class="row">
                    <div class="col showUser" style="text-align: right">
                        <label><?= _EMAIL.' :' ?></label>
                    </div>
                    <div class="col showUser" style="text-align: left">
                        <label><?= $resultInfoUser['email'] ?></label>
                    </div>
                    <div class="col modifUser" style="display: none">
                        <input type="email" placeholder="<?= _EMAIL ?>" value="<?= $resultInfoUser['email'] ?>" id="email"/>
                    </div>
                </div>
                <!-- Adresse -->
                <div class="row">
                    <div class="col showUser" style="text-align: right">
                        <label><?= _ADDRESS.' :' ?></label>
                    </div>
                    <div class="col showUser" style="text-align: left">
                        <label><?= $resultInfoUser['address'] ?></label>
                    </div>
                    <div class="col modifUser" style="display: none">
                        <input type="text" placeholder="<?= _ADDRESS ?>" value="<?= $resultInfoUser['address'] ?>" id="address"/>
                    </div>
                </div>
                <!-- City -->
                <div class="row">
                    <div class="col showUser" style="text-align: right">
                        <label><?= _CITY.' :' ?></label>
                    </div>
                    <div class="col showUser" style="text-align: left">
                        <label><?= $resultInfoUser['city'] ?></label>
                    </div>
                    <div class="col modifUser" style="display: none">
                        <input type="text" placeholder="<?= _CITY ?>" value="<?= $resultInfoUser['city'] ?>" id="city"/>
                    </div>
                </div>
                <!-- NumberPhone -->
                <div class="row">
                    <div class="col showUser" style="text-align: right">
                        <label><?= _PHONENUMBER.' :' ?></label>
                    </div>
                    <div class="col showUser" style="text-align: left">
                        <label><?= $resultInfoUser['phoneNumber'] ?></label>
                    </div>
                    <div class="col modifUser" style="display: none">
                        <input type="tel" placeholder="<?= _PHONENUMBER ?>" value="<?= $resultInfoUser['phoneNumber'] ?>" id="phoneNumber"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <h6 id="error"></h6>
                    </div>
                </div><br/>
                <!-- Buttons -->
                <div class="row">
                    <div class="col">
                        <input type="button" value="<?= _MODIFY ?>" onclick="modifUser()" class="showUser"/>
                        <input type="button" value="<?= _VALIDATE ?>" onclick="validModifUser()" class="modifUser" style="display: none"/>
                    </div>
                    <div class="col">
                        <input type="button" value="<?= _CHANGEPASSWORD ?>" onclick="modifPassword()" class="showUser"/>
                        <input type="button" value="<?= _CANCEL ?>" onclick="showUser()" class="modifUser" style="display: none"/>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <br/>
    <p style="text-align:center"><img alt="separateur" id="separateur" src="Pictures/Separateur3.png"></p>
</main>
<?php require_once('Pages/footer.php'); ?>
</body>
</html>
