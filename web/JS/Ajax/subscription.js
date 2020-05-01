window.onload = () => {
    // Variables
    let stripe = Stripe('pk_test_U2iCSSR4bBx2jS0pYX8tG5Of00Uy4HuV8w')
    let elements = stripe.elements()

    // Objets de la page
    let cardHolderName = document.getElementById("cardholder-name")
    let cardButton = document.getElementById("card-button")
    let clientSecret = cardButton.dataset.secret;

    // Crée les éléments du formulaire de carte bancaire
    let card = elements.create("card")
    card.mount("#card-elements")

    // On gère la saisie
    card.addEventListener("change", (event) => {
        let displayError = document.getElementById("card-errors")
        if(event.error){
            console.log("IF1")

            displayError.textContent = event.error.message;
        }else{
            console.log("ELSE1")

            displayError.textContent = "";
        }
    })

    // On gère le paiement
    cardButton.addEventListener("click", () => {
        stripe.handleCardPayment(
            clientSecret, card, {
                payment_method_data: {
                    billing_details: {name: cardHolderName.value}
                }
            }
        ).then((result) => {
            if(result.error){
                console.log("IF2");
                document.getElementById("errors").innerHTML = '<h6 style="color: #b50b00">' + result.error.message + '</h6>';
            }else{
                modif_data("StripeBuy");
                buySubscription(subscriptionID);
                console.log("ELSE2")
            }
        })
    })

}
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

function refund(subscriptionID) {
    const error = document.getElementById('error_ref' + subscriptionID);
    const request = new XMLHttpRequest();
    request.open('POST','refund.php');
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    request.onreadystatechange = function () {
        if(request.readyState === 4 && request.status === 200) {
            if(request.responseText.split(" ")[0] == "OK") {
                error.style.display = "block";
                error.style.color = "green";
                error.innerHTML = request.responseText.split(" ")[1];
            } else {
                error.style.display = "block";
                error.style.color = "#b52626";
                error.innerHTML = request.responseText;
            }
        }
    }
    request.send('sub='+subscriptionID);
}

function modif_data(id,value) {
    document.getElementById("card-button").dataset.secret = value
    var form = document.getElementById(id);
    console.log(id);
    if(form.style.display === "none") {
        form.style.display = "inline";
    }else{
        form.style.display = "none";
    }
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