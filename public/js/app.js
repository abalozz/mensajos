// Funciones para los eventos

// Envía un mensaje
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

// Elimina un mensaje
function deleteMessage (e) {
    this.removeEventListener('click', deleteMessage);
    this.textContent = 'Eliminando...';

    var self = this;
    ajax({
        method: 'post',
        url: '?page=messages&action=delete&id=' + this.getAttribute('data-message-id'),
        data: {
            content: content.value,
        },
    }, function (data) {
        var message = document.getElementById('message-id-' + self.getAttribute('data-message-id'));
        message.parentNode.removeChild(message);
    }, function () {
        this.addEventListener('click', deleteMessage, false);
        this.textContent = 'Borrar';
    });
}

// Asignar eventos
document.getElementById('send-message').addEventListener('click', sendMessage, false);

var deleteLinkMessages = document.getElementsByClassName('delete-message');
for (var i = deleteLinkMessages.length - 1; i >= 0; i--) {
    deleteLinkMessages[i].addEventListener('click', deleteMessage, false);
};

