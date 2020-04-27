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

function stripe_sub(sessionID) {
    var stripe = Stripe('pk_test_U2iCSSR4bBx2jS0pYX8tG5Of00Uy4HuV8w');
    stripe.redirectToCheckout({
        sessionId : sessionID
    })
        .then(function (result) {
            if (result.error) {
                var displayError = document.getElementById('error-message');
                displayError.textContent = result.error.message;
            }
        });
}