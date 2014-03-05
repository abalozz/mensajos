// Funciones para los eventos

function sendMessage (e) {
    var content = document.getElementById('content-message');
    
    if (content.value) {
        ajax({
            method: 'post',
            url: '?page=messages&action=store',
            data: {
                content: content.value,
            },
        }, function (data) {
            content.value = '';
        }, function () {
            // error
        });
    }
}

// Asignar eventos
document.getElementById('send-message').addEventListener('click', sendMessage, false);


