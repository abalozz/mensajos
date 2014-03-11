// Funciones para los eventos

// Envía un mensaje
function sendMessage (e) {
    var content = document.getElementById('content-message');

    if (content.value) {
        this.disabled = true;
        var self = this;
        
        ajax({
            method: 'post',
            url: '?page=messages&action=store',
            data: {
                content: content.value,
            },
        }, function (data) {
            content.value = '';
            self.disabled = false;

            var message =
                '<article class="message" id="message-id-' + data.id + '"> \
                    <figure class="profile-img"> \
                        <img src="' + data.user.profile_image + '" \
                            alt="' + data.user.name + '"> \
                    </figure> \
                    <div class="content"> \
                        <a href="?page=users&amp;action=show&amp;id=' + data.user_id + '"> \
                            ' + data.user.name + ' (' + data.user.username + ') \
                        </a> \
                        <p> \
                            ' + data.content + ' \
                        </p> \
                        <menu class="actions"> \
                            <a href="#" data-message-id="' + data.id + '" class="delete-message">Eliminar</a> \
                        </menu> \
                    </div> \
                </article>';
            var messages = document.getElementById('messages');
            messages.innerHTML = message + messages.innerHTML;
            assignLinkMessagesEvent();
        }, function () {
            self.disabled = false;
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

function assignLinkMessagesEvent() {
    var deleteLinkMessages = document.getElementsByClassName('delete-message');
    for (var i = deleteLinkMessages.length - 1; i >= 0; i--) {
        deleteLinkMessages[i].addEventListener('click', deleteMessage, false);
    };
}
assignLinkMessagesEvent();


