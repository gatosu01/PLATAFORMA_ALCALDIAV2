document.addEventListener('DOMContentLoaded', function() {
    function actualizarChats() {
        document.querySelectorAll('.conversacion-chat').forEach(chat => {
            const id = chat.id.replace('chat-', '');
            fetch(`get_chat.php?id=${id}`)
                .then(res => res.text())
                .then(html => {
                    // Solo actualizamos si el contenido cambió
                    if (chat.innerHTML.trim() !== html.trim()) {
                        chat.innerHTML = html;
                        chat.scrollTop = chat.scrollHeight; // bajar al final
                    }
                })
                .catch(err => console.error('Error cargando chat', err));
        });
    }

    // Actualiza cada 5 segundos
    setInterval(actualizarChats, 5000);
});
