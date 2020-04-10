function tr(name) {

    var urlbase = document.location.href;

    var url = urlbase.split("?");

    location.href = url[0] + '?lang=' + name;

}