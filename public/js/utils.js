// Función que maneja las peticiones AJAX
function ajax (params, done, error) {
    var xhr, data = '';

    if (window.XMLHttpRequest) { // Mozilla, Safari, ...
        xhr = new XMLHttpRequest();
    } else if (window.ActiveXObject) { // IE
        try {
            xhr = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                xhr = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {}
        }
    }

    if (!xhr) {
        if (error)
            error();
        return false;
    }

    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                if (xhr.responseText)
                    var data = JSON.parse(xhr.responseText);
                done(data);
            } else {
                if (error)
                    error();
            }
        }
    };
    xhr.open(params.method || 'GET', params.url);

    if (params.method == 'post') {
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        if (params.data != undefined) {
            for(var key in params.data) {
                var value = params.data[key];
                data += key + '=' + encodeURIComponent(value) + '&';
            }
            data += 'nocache=' + Math.random();
        }
    }

    xhr.send(data);
}