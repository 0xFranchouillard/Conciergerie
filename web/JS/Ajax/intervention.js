function selectService() {
    const serviceSelect = document.getElementById('serviceSelect').value;
    const classServiceSelect = document.getElementsByClassName('serviceSelect');
    const typeServiceIntervention = document.getElementById('typeServiceIntervention');
    const subscription = document.getElementById('subscription');
    const error = document.getElementById('error');
    const request = new XMLHttpRequest();
    request.open('POST','verif/verifIntervention.php');
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    request.onreadystatechange = function () {
        if(request.readyState === 4 && request.status === 200) {
            if(request.responseText != "ERROR") {
                typeServiceIntervention.innerHTML = request.responseText.split("OK")[0];
                for(var i=0; i < classServiceSelect.length; i++) {
                    classServiceSelect[i].style.display = "flex";
                }
                subscription.innerHTML = request.responseText.split("OK")[1];
                error.style.display = "none";
            } else {
                typeServiceIntervention.innerHTML = "";
                for(var i=0; i < classServiceSelect.length; i++) {
                    classServiceSelect[i].style.display = "none";
                }
                error.style.display = "none";
            }
        }
    }
    request.send('serviceSelect=' + serviceSelect + '&button=' + 1);
}

function createIntervention() {
    const serviceSelect = document.getElementById('serviceSelect').value;
    const nbTakeIntervention = document.getElementById('nbTakeIntervention').value;
    const dateIntervention = document.getElementById('dateIntervention').value;
    const timeIntervention = document.getElementById('timeIntervention').value;
    const subscriptionSelect = document.getElementById('subscriptionSelect').value;
    const request = new XMLHttpRequest();
    request.open('POST','verif/verifIntervention.php');
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    request.onreadystatechange = function () {
        if(request.readyState === 4 && request.status === 200) {
            if(request.responseText.split(" ")[0] == "OK") {
                displayOK(request.responseText.split("OK ")[1]);
            } else {
                R_displayError(request.responseText);
            }
        }
    }
    request.send('serviceSelect=' + serviceSelect +'&nbTakeIntervention=' + nbTakeIntervention + '&dateIntervention=' + dateIntervention + '&timeIntervention=' + timeIntervention + '&subscriptionSelect=' + subscriptionSelect + '&button=' + 2);
}

function R_displayError(e) {
    const error = document.getElementById('error');
    error.style.display = "block";
    error.style.color ="#b52626";
    error.innerHTML = e;
}

function displayOK(e) {
    const error = document.getElementById('error');
    error.style.display = "block";
    error.style.color = "green";
    error.innerHTML = e;
}

function cancelIntervention(interventionID) {
    const planning = document.getElementById('Planning' + interventionID);
    const request = new XMLHttpRequest();
    request.open('POST','verif/verifIntervention.php');
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    request.onreadystatechange = function () {
        if(request.readyState === 4 && request.status === 200) {
            if(request.responseText == "OK") {
                planning.style.display = "none";
            }
        }
    }
    request.send('interventionID=' + interventionID + '&button=' + 3);
}