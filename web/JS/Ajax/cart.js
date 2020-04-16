function cancel() {
    const request = new XMLHttpRequest();
    const valueOnHold = document.getElementById('valueOnHold');
    request.open('POST','verif/verifCart.php');
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    request.onreadystatechange = function() {
        if(request.readyState === 4 && request.status === 200) {
            if(request.responseText == "CANCEL") {
                valueOnHold.innerHTML = " ";
            }
        }
    }
    request.send('button=' + 0);
}

function estimate() {
    const request = new XMLHttpRequest();
    request.open('POST','verif/verifCart.php');
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    request.onreadystatechange = function() {
        if(request.readyState === 4 && request.status === 200) {
            if(request.responseText == "ESTIMATE") {
                window.open('fpdf/generatePDF.php','_blank');
            }
        }
    }
    request.send('button=' + 2);
}