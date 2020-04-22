function send(id,res){
    const data = document.getElementById(id).value;

    const request = new XMLHttpRequest();
    tab = ["providerID","userID","lastName","firstName","email","UserFunction","city","address","phoneNumber","qrcode","hash","agency"];

    if(tab.indexOf(id) !== -1)
        stmt = id+'='+data;
    else
        if (id === "password")
            stmt = "old_password" + '=' + document.getElementById("old_password").value;
            stmt += "&passwd" + '=' + document.getElementById("passwd").value;
            stmt += "&" + id + '=' + data;

    request.open('GET', 'http://localhost/Conciergerie/web/verif_user_profil.php?'+stmt);

    console.log('http://localhost/Conciergerie/web/verif_user_profil.php?'+stmt);

    request.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
    request.setRequestHeader('Access-Control-Allow-Origin','http://172.0.0.1');


    request.onreadystatechange = function(search) {
        if (request.readyState === 4) {
            document.getElementById(res).innerHTML = "";
            if (request.responseText == "nothing to modified") {
            document.getElementById(res).innerHTML = '<h6 style="color: #b56915">' + request.responseText + '</h6>';
            } else if (request.responseText == "Account has been updated ! ") {
                        document.getElementById(res).innerHTML = '<h6 style="color: #00b504">' + request.responseText + '</h6>';
                    }else if(request.responseText == "Connection error !" || request.responseText == "Wrong password !")
                            document.getElementById(res).innerHTML = '<h6 style="color: #b50e21">'+request.responseText+'</h6>';

            console.log(request.responseText);
        }
    }
    request.send();

}

    const data = document.getElementById(id+arg+type+'data');
    const request = new XMLHttpRequest();
    console.log(type);
    console.log(data);

    if(type == "service")  request.open('GET', 'suppression_service.php?id='+ id);
    if(type == "prestataire")  request.open('GET', 'suppression_prestataire.php?id='+ id + '&agency=' + arg);
    if(type == "client")  request.open('GET', 'suppression_client.php?id='+ id + '&agency=' + arg);
    if(type == "service_prestataire")  request.open('GET', 'suppression_service_prestataire.php?id='+ id);
    if(id == "sub")request.open('GET', 'suppression_subscription.php?id='+ data + '&language=' + arg);

    request.onreadystatechange = function() {
        if(request.readyState === 4) {
            console.log(request.responseText)
            data.innerHTML = '<br>';
        }
    };
    request.send();
}
function modif_data(id) {
    var form = document.getElementById(id);
    console.log(id);
    if(form.style.display === "none") {
        form.style.display = "inline";
    }else{
        form.style.display = "none";
    }
}


function search3(id,res){
    const data = document.getElementById(id).value;
    const request = new XMLHttpRequest();

    console.log(data);

    if(id == "recherche_user") {
        var type = input_type.options[document.getElementById('input_type').selectedIndex].innerHTML;
        request.open('GET', 'recherche_user.php?data=' + data + '&type=' + type);    console.log(type);

    }

    if(id == "recherche_service")request.open('GET', 'recherche_service.php?data='+ data);
    if(id == "recherche_planning")request.open('GET', 'recherche_planning.php?data='+ data);
    if(id == "recherche_prestation"){
        var type = input_type_prestatation.options[document.getElementById('input_type_prestatation').selectedIndex].innerHTML;
        request.open('GET', 'recherche_prestation.php?data='+ data + '&type=' + type);    console.log(type);
    }
    if(id == "recherche_intervention"){
        var type = input_type_prestatation.options[document.getElementById('input_type_intervention').selectedIndex].innerHTML;
        request.open('GET', 'recherche_intervention.php?data='+ data + '&type=' + type);    console.log(type);
    }

    request.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
    request.onreadystatechange = function(search) {
        if(request.readyState === 4){
            console.log(search);
            document.getElementById(res).innerHTML = request.responseText ;
        }
    }
    request.send();

}
