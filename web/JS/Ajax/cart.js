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
                modif_data("StripeBuy")
                buy();
                console.log("ELSE2")
            }
        })
    })

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

function cancel() {
    const request = new XMLHttpRequest();
    const valueOnHold = document.getElementById('valueOnHold');
    const cartEmpty = document.getElementById('cartEmpty');
    request.open('POST','verif/verifCart.php');
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    request.onreadystatechange = function() {
        if(request.readyState === 4 && request.status === 200) {
            if(request.responseText == "CANCEL") {
                valueOnHold.style.display = "none";
                cartEmpty.style.display = "block";
            }
        }
    }
    request.send('button=' + 0);
}

function buy() {
    const request = new XMLHttpRequest();
    const valueOnHold = document.getElementById('valueOnHold');
    const cartEmpty = document.getElementById('cartEmpty');
    request.open('POST','verif/verifCart.php');
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    request.onreadystatechange = function() {
        if(request.readyState === 4 && request.status === 200) {
            if(request.responseText == "BILL") {
                window.open('fpdf/generatePDF.php','_blank');
                valueOnHold.style.display = "none";
                cartEmpty.style.display = "block";
            }
        }
    }
    request.send('button=' + 1);
}

function estimate() {
    const request = new XMLHttpRequest();
    const valueOnHold = document.getElementById('valueOnHold');
    const cartEmpty = document.getElementById('cartEmpty');
    request.open('POST','verif/verifCart.php');
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    request.onreadystatechange = function() {
        if(request.readyState === 4 && request.status === 200) {
            if(request.responseText == "ESTIMATE") {
                window.open('fpdf/generatePDF.php','_blank');
                valueOnHold.style.display = "none";
                cartEmpty.style.display = "block";
            }
        }
    }
    request.send('button=' + 2);
}

function detail(i) {
 const details = document.getElementsByClassName('detailsEstimate'+i);
 const less = document.getElementsByClassName('lessEstimate'+i);
 for(var i=0; i<details.length; i++) {
     details[i].style.display = "flex";
 }
 for(var i=0; i<less.length; i++) {
     less[i].style.display = "none";
 }
}

function less(i) {
    const details = document.getElementsByClassName('detailsEstimate'+i);
    const less = document.getElementsByClassName('lessEstimate'+i);
    for(var i=0; i<details.length; i++) {
        details[i].style.display = "none";
    }
    for(var i=0; i<less.length; i++) {
        less[i].style.display = "flex";
    }
}

function buyEstimate(billID,i) {
    modif_data("StripeBuy");
    const details = document.getElementsByClassName('detailsEstimate'+i);
    const less = document.getElementsByClassName('lessEstimate'+i);
    const estimate = document.getElementById('estimate'+i);
    const request = new XMLHttpRequest();
    request.open('POST','verif/verifCart.php');
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    request.onreadystatechange = function() {
        if(request.readyState === 4 && request.status === 200) {
            if(request.responseText == "BUYESTIMATE") {
                window.open('fpdf/generatePDF.php','_blank');
                for(var i=0; i<details.length; i++) {
                    details[i].style.display = "none";
                }
                for(var i=0; i<less.length; i++) {
                    less[i].style.display = "none";
                }
                estimate.style.display = "none";
            }
        }
    }
    request.send('button=' + 3 + '&billID=' + billID);
}