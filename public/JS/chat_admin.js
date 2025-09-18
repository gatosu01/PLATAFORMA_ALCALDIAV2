document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.form-chat-ajax-admin').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const id = form.dataset.id;
            const textarea = form.querySelector('textarea[name="respuesta"]');
            const mensaje = textarea.value.trim();
            const accion = form.querySelector('button[type="submit"]:focus')?.value || 'seguir';
            if (!mensaje) return;
            form.querySelectorAll('button').forEach(btn => btn.disabled = true);
            fetch('/Alcaldia/public/send_message.php?action=sendMessage', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `id=${encodeURIComponent(id)}&mensaje=${encodeURIComponent(mensaje)}&tipo=admin&accion=${encodeURIComponent(accion)}`
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    fetch(`/Alcaldia/public/send_message.php?action=getChat&id=${id}`)
                        .then(res => res.text())
                        .then(html => {
                            const chatDiv = document.getElementById('chat-' + id);
                            if (chatDiv) {
                                chatDiv.innerHTML = html;
                                chatDiv.scrollTop = chatDiv.scrollHeight;
                            }
                            textarea.value = '';
                            form.querySelectorAll('button').forEach(btn => btn.disabled = false);
                        });
                } else {
                    alert('Error al enviar mensaje');
                    form.querySelectorAll('button').forEach(btn => btn.disabled = false);
                }
            })
            .catch(() => {
                alert('Error de red');
                form.querySelectorAll('button').forEach(btn => btn.disabled = false);
            });
        });
    });
    // Mantener scroll al final en todos los chats
    var chats = document.querySelectorAll('.conversacion-chat');
    chats.forEach(function(chat) {
        chat.scrollTop = chat.scrollHeight;
    });
});
