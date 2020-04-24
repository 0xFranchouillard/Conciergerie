function addCart(serviceID) {
    const nbTake = document.getElementById('nbTake').value;
    const error = document.getElementById('error');
    const request = new XMLHttpRequest();
    request.open('POST','verif/verifAddToCart.php');
    request.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
    request.onreadystatechange = function() {
        if(request.readyState === 4 && request.status === 200) {
            if(request.responseText == "OK") {
                document.location.href="cart.php";
            } else {
                if (request.responseText != "KO") {
                    error.style.display = "block";
                    error.style.color = "#b52626";
                    error.innerHTML = request.responseText;
                }
            }
        }
    }
    request.send('nbTake=' + nbTake + "&serviceID=" + serviceID);
}