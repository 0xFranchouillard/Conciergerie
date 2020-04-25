function buySubscription(subscriptionID) {
    const error = document.getElementById('error' + subscriptionID);
    const request = new XMLHttpRequest();
    request.open('POST','verif/verifSubscription.php');
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    request.onreadystatechange = function () {
        if(request.readyState === 4 && request.status === 200) {
            if(request.responseText.split(" ")[0] == "OK") {
                error.style.display = "block";
                error.style.color = "green";
                error.innerHTML = request.responseText.split("OK ")[1];
            } else {
                error.style.display = "block";
                error.style.color = "#b52626";
                error.innerHTML = request.responseText;
            }
        }
    }
    request.send('subscriptionID=' + subscriptionID);
}