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
        if(request.readyState === 4){
            document.getElementById(res).innerHTML = "";
            document.getElementById(res).innerHTML = '<h6 style="color: #00b504">'+request.responseText+'</h6>';
            request.responseText = "";
            console.log(request.responseText);
        }
    }
    request.send();

}

