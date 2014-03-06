// Funciones para los eventos

function sendMessage (e) {
    var content = document.getElementById('content-message');
    this.disabled = true;
    
    if (content.value) {
        ajax({
            method: 'post',
            url: '?page=messages&action=store',
            data: {
                content: content.value,
            },
        }, function (data) {
            content.value = '';
            this.disabled = false;
        }, function () {
            // error
        });
    }
}

// Asignar eventos
document.getElementById('send-message').addEventListener('click', sendMessage, false);


