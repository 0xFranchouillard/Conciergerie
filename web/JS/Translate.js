function tr(name) {

    var urlbase = document.location.href;

    if(urlbase.includes("?lang=")) {
        var url = urlbase.split("?lang=");
        location.href = url[0] + '?lang=' + name;
    } else if(urlbase.includes("&lang=")) {
        var url = urlbase.split("&lang=");
        location.href = url[0] + '&lang=' + name;
    } else if(urlbase.includes("?")) {
        location.href = urlbase + '&lang=' + name;
    } else {
        location.href = urlbase + '?lang=' + name;
    }
}