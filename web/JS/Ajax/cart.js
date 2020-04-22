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