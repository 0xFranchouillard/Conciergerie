function modifUser() {
    const modif = document.getElementsByClassName('modifUser');
    const show = document.getElementsByClassName('showUser');
    for(let i = 0; i < modif.length; i++) {
        modif[i].style.display = "inline-block";
    }
    for(let j = 0; j < show.length; j++) {
        show[j].style.display = "none";
    }
}

function showUser() {
    const error = document.getElementById('error');
    const modif = document.getElementsByClassName('modifUser');
    const show = document.getElementsByClassName('showUser');
    error.style.display = "none";
    for(let i = 0; i < modif.length; i++) {
        modif[i].style.display = "none";
    }
    for(let j = 0; j < show.length; j++) {
        show[j].style.display = "inline-block";
    }
}

function validModifUser() {
    const lastName = document.getElementById('lastName').value;
    const firstName = document.getElementById('firstName').value;
    const email = document.getElementById('email').value;
    const address = document.getElementById('address').value;
    const city = document.getElementById('city').value;
    const phoneNumber = document.getElementById('phoneNumber').value;
    const error = document.getElementById('error');
    const modif = document.getElementsByClassName('modifUser');
    const show = document.getElementsByClassName('showUser');
    const request = new XMLHttpRequest();
    request.open('POST','verif/verifUserProfil.php');
    request.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
    request.onreadystatechange = function () {
        if(request.readyState === 4 && request.status === 200) {
            if(request.responseText.split(" ")[0] == "OK") {
                error.style.display = "block";
                error.style.color = "green";
                error.innerHTML = request.responseText.split("OK ")[1];
                for(let i = 0; i < modif.length; i++) {
                    modif[i].style.display = "none";
                }
                for(let j = 0; j < show.length; j++) {
                    show[j].style.display = "inline-block";
                }
            } else {
                error.style.display = "block";
                error.style.color = "#b52626";
                error.innerHTML = request.responseText;
            }
        }
    }
    request.send('lastName=' + lastName + '&firstName=' + firstName + '&email=' + email + '&address=' + address + '&city=' + city + '&phoneNumber=' + phoneNumber);
}

function modifPassword() {
    document.location.href = "changePassword.php";
}