function addCart(serviceID) {
    const nbTake = document.getElementById('nbTake').value;
    const request = new XMLHttpRequest();
    request.open('POST','verif/verifAddToCart.php');
    request.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
    request.onreadystatechange = function() {
        if(request.readyState === 4 && request.status === 200) {
            if(request.responseText == "OK") {
                document.location.href="cart.php";
            }
        }
    }
    request.send('nbTake=' + nbTake + "&serviceID=" + serviceID);
}