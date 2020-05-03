function changePassword() {
    const olfPassword = document.getElementById('oldPassword').value;
    const password = document.getElementById('password').value;
    const password2 = document.getElementById('password2').value;
    const error = document.getElementById('error');
    const request = new XMLHttpRequest();
    request.open('POST','verif/verifChangePassword.php');
    request.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
    request.onreadystatechange = function () {
        if(request.readyState === 4 && request.status === 200) {
            if(request.responseText == "OK") {
                document.location.href="connection.php";
            } else {
                error.style.color = "#b52626";
                error.style.display = "block";
                error.innerHTML = request.responseText;
            }
        }
    }
    request.send('oldPassword=' + olfPassword + '&password=' + password + '&password2=' + password2);
}