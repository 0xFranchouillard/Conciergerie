function Connexion(lang) {
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const type = document.getElementById('type').value;
    const request = new XMLHttpRequest();
    request.open('POST','verif_connexion.php');
    request.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
    request.onreadystatechange = function() {
        if(request.readyState === 4 && request.status === 200) {
            if(request.responseText == "OK") {
                document.location.href="index.php";
            } else {
                displayError(request.responseText);
            }
        }
    }
    request.send('email=' + email + '&password=' + password + '&type=' + type + '&lang=' + lang);
}

function displayError(e) {
    const error = document.getElementById('error');
    error.style.display = "block";
    error.innerHTML = e;
}

function Registration(lang) {
    const lastName = document.getElementById('lastName').value;
    const firstName = document.getElementById('firstName').value;
    const email = document.getElementById('R_email').value;
    const phoneNumber = document.getElementById('phoneNumber').value;
    const address = document.getElementById('address').value;
    const city = document.getElementById('city').value;
    const agency = document.getElementById('agency').value;
    const password = document.getElementById('R_password').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    const request = new XMLHttpRequest();
    request.open('POST','verif_inscription.php');
    request.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
    request.onreadystatechange = function() {
        if(request.readyState === 4 && request.status === 200) {
            if(request.responseText.split(" ")[0] == "OK") {
                displayOK(request.responseText.split("OK ")[1]);
            } else {
                R_displayError(request.responseText);
            }
        }
    }
    request.send('lastName=' + lastName + '&firstName=' + firstName + '&email=' + email + '&phoneNumber=' + phoneNumber + '&address=' + address + '&city=' + city + '&agency=' + agency + '&password=' + password + '&confirmPassword=' + confirmPassword + '&lang=' + lang);
}

function R_displayError(e) {
    const error = document.getElementById('R_error');
    error.style.display = "block";
    error.innerHTML = e;
}

function displayOK(e) {
    const error = document.getElementById('R_error');
    error.style.display = "block";
    error.style.color = "green";
    error.innerHTML = e;
}